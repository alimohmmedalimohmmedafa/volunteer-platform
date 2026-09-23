<?php

namespace Tests\Feature\Admin;

use App\Models\Job;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminJobTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Organization $organization;
    private Job $job;

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

        $user = User::create([
            'name' => 'Org User',
            'email' => 'org@example.com',
            'password' => bcrypt('password'),
            'role' => 'organization',
        ]);

        $this->organization = Organization::create([
            'user_id' => $user->id,
            'name' => 'Org A',
            'status' => 'approved',
        ]);

        $this->job = $this->organization->jobs()->create([
            'title' => 'Community Help',
            'description' => 'Helping the community',
            'requirements' => 'Interest',
            'location' => 'Khartoum',
            'start_date' => now()->addDays(10),
            'end_date' => now()->addDays(20),
            'application_deadline' => now()->addDays(5),
            'status' => 'published',
        ]);
    }

    private function makeSecondOrg(): Organization
    {
        $user = User::create([
            'name' => 'Org User B',
            'email' => 'orgb@example.com',
            'password' => bcrypt('password'),
            'role' => 'organization',
        ]);

        return Organization::create([
            'user_id' => $user->id,
            'name' => 'Org B',
            'status' => 'approved',
        ]);
    }

    private function validJobData(array $overrides = []): array
    {
        return array_merge([
            'organization_id' => $this->organization->id,
            'title' => 'New Job',
            'description' => 'Desc',
            'requirements' => 'Req',
            'location' => 'Omdurman',
            'start_date' => now()->addDays(3)->format('Y-m-d'),
            'end_date' => now()->addDays(13)->format('Y-m-d'),
            'application_deadline' => now()->addDay()->format('Y-m-d'),
        ], $overrides);
    }

    public function test_index_lists_all_jobs(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.jobs.index'))
            ->assertOk()
            ->assertSee('Community Help')
            ->assertSee('Org A');
    }

    public function test_index_filters_jobs_by_organization(): void
    {
        $orgB = $this->makeSecondOrg();

        $orgB->jobs()->create([
            'title' => 'Only Org B Job',
            'description' => 'Desc',
            'requirements' => 'Req',
            'location' => 'Khartoum',
            'start_date' => now()->addDays(10),
            'end_date' => now()->addDays(20),
            'application_deadline' => now()->addDays(5),
            'status' => 'published',
        ]);

        $this->actingAs($this->admin)
            ->get(route('admin.jobs.index', ['organization' => $orgB->id]))
            ->assertOk()
            ->assertSee('Only Org B Job')
            ->assertDontSee('Community Help');
    }

    public function test_admin_can_create_job_for_an_organization(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.jobs.store'), $this->validJobData())
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('jobs', [
            'title' => 'New Job',
            'organization_id' => $this->organization->id,
            'status' => 'published',
        ]);
    }

    public function test_admin_cannot_create_job_for_a_non_approved_organization(): void
    {
        $pendingOrgUser = User::create([
            'name' => 'Pending Org User',
            'email' => 'pend@example.com',
            'password' => bcrypt('password'),
            'role' => 'organization',
        ]);

        $pendingOrg = Organization::create([
            'user_id' => $pendingOrgUser->id,
            'name' => 'Pending Org',
            'status' => 'pending',
        ]);

        $this->actingAs($this->admin)
            ->post(route('admin.jobs.store'), $this->validJobData([
                'organization_id' => $pendingOrg->id,
            ]))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseMissing('jobs', [
            'organization_id' => $pendingOrg->id,
        ]);
    }

    public function test_admin_can_edit_and_update_a_job(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.jobs.edit', $this->job))
            ->assertOk()
            ->assertSee('Community Help');

        $this->actingAs($this->admin)
            ->patch(route('admin.jobs.update', $this->job), $this->validJobData([
                'title' => 'Updated Job Title',
            ]))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('jobs', [
            'id' => $this->job->id,
            'title' => 'Updated Job Title',
        ]);
    }

    public function test_admin_can_cancel_a_job(): void
    {
        $this->actingAs($this->admin)
            ->patch(route('admin.jobs.cancel', $this->job))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('jobs', [
            'id' => $this->job->id,
            'status' => 'cancelled',
        ]);
    }

    public function test_admin_can_complete_a_job(): void
    {
        $this->actingAs($this->admin)
            ->patch(route('admin.jobs.complete', $this->job))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('jobs', [
            'id' => $this->job->id,
            'status' => 'completed',
        ]);
    }
}