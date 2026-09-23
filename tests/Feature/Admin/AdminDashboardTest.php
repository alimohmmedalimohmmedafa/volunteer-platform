<?php

namespace Tests\Feature\Admin;

use App\Models\Job;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);
    }

    private function makeOrganization(string $status = 'approved'): Organization
    {
        $user = User::create([
            'name' => 'Org User',
            'email' => fake()->unique()->safeEmail(),
            'password' => bcrypt('password'),
            'role' => 'organization',
        ]);

        return Organization::create([
            'user_id' => $user->id,
            'name' => fake()->company(),
            'status' => $status,
        ]);
    }

    public function test_dashboard_shows_real_counts(): void
    {
        User::create([
            'name' => 'Volunteer One',
            'email' => 'v1@example.com',
            'password' => bcrypt('password'),
            'role' => 'volunteer',
        ]);

        User::create([
            'name' => 'Volunteer Two',
            'email' => 'v2@example.com',
            'password' => bcrypt('password'),
            'role' => 'volunteer',
        ]);

        $org = $this->makeOrganization('approved');
        $this->makeOrganization('pending');

        $job = $org->jobs()->create([
            'title' => 'Job X',
            'description' => 'Desc',
            'requirements' => 'Req',
            'location' => 'Khartoum',
            'start_date' => now()->addDays(5),
            'end_date' => now()->addDays(15),
            'application_deadline' => now()->addDays(2),
            'status' => 'published',
        ]);

        $job2 = $org->jobs()->create([
            'title' => 'Job Y',
            'description' => 'Desc',
            'requirements' => 'Req',
            'location' => 'Omdurman',
            'start_date' => now()->addDays(7),
            'end_date' => now()->addDays(17),
            'application_deadline' => now()->addDays(3),
            'status' => 'published',
        ]);

        $volunteer = User::where('email', 'v1@example.com')->first();
        $profile = $volunteer->volunteerProfile()->create();

        $profile->applications()->create([
            'job_id' => $job->id,
            'status' => 'pending',
            'applied_at' => now(),
        ]);

        $profile->applications()->create([
            'job_id' => $job2->id,
            'status' => 'accepted',
            'applied_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('إجمالي المتطوعين')
            ->assertSee('إجمالي المنظمات')
            ->assertSee('إجمالي الوظائف')
            ->assertSee('إجمالي الطلبات')
            ->assertSee('منظمات قيد المراجعة')
            ->assertSee('طلبات مقبولة')
            ->assertSee('طلبات مرفوضة');

        $response->assertViewHas('stats', function (array $stats) {
            return $stats['totalVolunteers'] === 2
                && $stats['totalOrganizations'] === 2
                && $stats['totalJobs'] === 2
                && $stats['totalApplications'] === 2
                && $stats['pendingOrganizations'] === 1
                && $stats['acceptedApplications'] === 1
                && $stats['rejectedApplications'] === 0;
        });
    }

    public function test_dashboard_zero_counts_when_empty(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.dashboard'))
            ->assertOk();

        $response->assertViewHas('stats', function (array $stats) {
            return $stats['totalVolunteers'] === 0
                && $stats['totalOrganizations'] === 0
                && $stats['totalJobs'] === 0
                && $stats['totalApplications'] === 0;
        });
    }
}