<?php

namespace Tests\Feature\Employee;

use App\Models\Application;
use App\Models\Job;
use App\Models\Message;
use App\Models\Organization;
use App\Models\User;
use App\Models\VolunteerProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private User $employee;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->employee = User::create([
            'name' => 'Employee',
            'email' => 'emp@example.com',
            'password' => bcrypt('password'),
            'role' => 'employee',
            'status' => 'active',
        ]);

        $this->admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);
    }

    public function test_employee_can_login(): void
    {
        $this->post(route('login'), [
            'email' => 'emp@example.com',
            'password' => 'password',
        ])->assertRedirect(route('employee.dashboard'));

        $this->assertAuthenticated();
    }

    public function test_employee_can_access_own_dashboard(): void
    {
        $this->actingAs($this->employee)
            ->get(route('employee.dashboard'))
            ->assertOk()
            ->assertSee('لوحة التحكم');
    }

    public function test_guest_cannot_access_employee_areas(): void
    {
        $this->get(route('employee.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_admin_cannot_access_employee_areas(): void
    {
        $this->actingAs($this->admin)
            ->get(route('employee.dashboard'))
            ->assertForbidden();
    }

    public function test_volunteer_cannot_access_employee_areas(): void
    {
        $volunteer = User::create([
            'name' => 'Vol',
            'email' => 'vol@example.com',
            'password' => bcrypt('password'),
            'role' => 'volunteer',
        ]);

        $this->actingAs($volunteer)
            ->get(route('employee.organizations.index'))
            ->assertForbidden();
    }

    public function test_organization_cannot_access_employee_areas(): void
    {
        $user = User::create([
            'name' => 'Org User',
            'email' => 'org@example.com',
            'password' => bcrypt('password'),
            'role' => 'organization',
        ]);

        $this->actingAs($user)
            ->get(route('employee.jobs.index'))
            ->assertForbidden();
    }

    public function test_employee_cannot_access_admin_dashboard(): void
    {
        $this->actingAs($this->employee)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_employee_cannot_access_admin_management_pages(): void
    {
        $this->actingAs($this->employee)
            ->get(route('admin.users.index'))
            ->assertForbidden();

        $this->actingAs($this->employee)
            ->get(route('admin.employees.index'))
            ->assertForbidden();
    }

    public function test_employee_cannot_reach_employees_or_users_management_routes(): void
    {
        $this->actingAs($this->employee)
            ->get('employee/employees')
            ->assertNotFound();

        $this->actingAs($this->employee)
            ->get('employee/users')
            ->assertNotFound();

        $this->actingAs($this->employee)
            ->get('employee/settings')
            ->assertNotFound();
    }

    public function test_employee_cannot_create_another_employee(): void
    {
        $this->actingAs($this->employee)
            ->post('employee/employees', [
                'name' => 'New Emp',
                'email' => 'new@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ])
            ->assertNotFound();

        $this->assertDatabaseMissing('users', [
            'email' => 'new@example.com',
        ]);
    }

    public function test_employee_cannot_create_admin(): void
    {
        $this->actingAs($this->employee)
            ->post('employee/admins', [
                'name' => 'New Admin',
                'email' => 'nadmin@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ])
            ->assertNotFound();

        $this->assertDatabaseMissing('users', [
            'email' => 'nadmin@example.com',
        ]);
    }

    public function test_employee_cannot_change_roles(): void
    {
        $volunteer = User::create([
            'name' => 'Vol',
            'email' => 'vol@example.com',
            'password' => bcrypt('password'),
            'role' => 'volunteer',
        ]);

        $this->actingAs($this->employee)
            ->patch("employee/users/{$volunteer->id}/role", [
                'role' => 'admin',
            ])
            ->assertNotFound();

        $this->assertDatabaseHas('users', [
            'id' => $volunteer->id,
            'role' => 'volunteer',
        ]);
    }

    public function test_dashboard_shows_employee_stats(): void
    {
        $volunteer = User::create([
            'name' => 'Vol',
            'email' => 'vol2@example.com',
            'password' => bcrypt('password'),
            'role' => 'volunteer',
        ]);

        $orgUser = User::create([
            'name' => 'Org User',
            'email' => 'org2@example.com',
            'password' => bcrypt('password'),
            'role' => 'organization',
        ]);

        Organization::create([
            'user_id' => $orgUser->id,
            'name' => 'Org A',
            'status' => 'approved',
        ]);

        Message::create([
            'name' => 'Visitor',
            'email' => 'v@example.com',
            'subject' => 'Hi',
            'message' => 'Hello',
            'is_read' => false,
        ]);

        $response = $this->actingAs($this->employee)
            ->get(route('employee.dashboard'))
            ->assertOk()
            ->assertSee('المنظمات')
            ->assertSee('الوظائف')
            ->assertSee('المتطوعون')
            ->assertSee('الطلبات')
            ->assertSee('رسائل غير مقروءة');

        $response->assertViewHas('stats', function (array $stats) {
            return $stats['totalOrganizations'] === 1
                && $stats['totalJobs'] === 0
                && $stats['totalVolunteers'] === 1
                && $stats['totalApplications'] === 0
                && $stats['unreadMessages'] === 1;
        });
    }
}