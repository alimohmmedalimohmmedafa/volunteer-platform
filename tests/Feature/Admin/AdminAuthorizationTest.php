<?php

namespace Tests\Feature\Admin;

use App\Models\Application;
use App\Models\Job;
use App\Models\Notification;
use App\Models\Organization;
use App\Models\User;
use App\Models\VolunteerProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthorizationTest extends TestCase
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

    public function test_guest_redirected_to_login(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_volunteer_cannot_access_admin_areas(): void
    {
        $volunteer = User::create([
            'name' => 'Vol',
            'email' => 'vol@example.com',
            'password' => bcrypt('password'),
            'role' => 'volunteer',
        ]);

        $this->actingAs($volunteer)
            ->get(route('admin.dashboard'))
            ->assertForbidden();

        $this->actingAs($volunteer)
            ->get(route('admin.messages.index'))
            ->assertForbidden();
    }

    public function test_organization_cannot_access_admin_areas(): void
    {
        $user = User::create([
            'name' => 'Org',
            'email' => 'org@example.com',
            'password' => bcrypt('password'),
            'role' => 'organization',
        ]);

        Organization::create([
            'user_id' => $user->id,
            'name' => 'Org',
            'status' => 'approved',
        ]);

        $this->actingAs($user)
            ->get(route('admin.users.index'))
            ->assertForbidden();
    }

    public function test_employee_cannot_access_admin_areas(): void
    {
        $employee = User::create([
            'name' => 'Emp',
            'email' => 'emp@example.com',
            'password' => bcrypt('password'),
            'role' => 'employee',
        ]);

        $this->actingAs($employee)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_admin_can_access_all_admin_pages(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.dashboard'))
            ->assertOk();

        $this->actingAs($this->admin)
            ->get(route('admin.organizations.index'))
            ->assertOk();

        $this->actingAs($this->admin)
            ->get(route('admin.jobs.index'))
            ->assertOk();

        $this->actingAs($this->admin)
            ->get(route('admin.applications.index'))
            ->assertOk();

        $this->actingAs($this->admin)
            ->get(route('admin.users.index'))
            ->assertOk();

        $this->actingAs($this->admin)
            ->get(route('admin.employees.index'))
            ->assertOk();

        $this->actingAs($this->admin)
            ->get(route('admin.messages.index'))
            ->assertOk();
    }
}