<?php

namespace Tests\Feature\Employee;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeOrganizationTest extends TestCase
{
    use RefreshDatabase;

    private User $employee;

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
    }

    private function makeOrganization(string $status = 'pending'): Organization
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

    public function test_index_lists_organizations_with_review_action(): void
    {
        $organization = $this->makeOrganization('pending');

        $this->actingAs($this->employee)
            ->get(route('employee.organizations.index'))
            ->assertOk()
            ->assertSee($organization->name)
            ->assertSee('مراجعة');
    }

    public function test_index_filters_by_status(): void
    {
        $approved = $this->makeOrganization('approved');
        $pending = $this->makeOrganization('pending');

        $this->actingAs($this->employee)
            ->get(route('employee.organizations.index', ['status' => 'pending']))
            ->assertOk()
            ->assertSee($pending->name)
            ->assertDontSee($approved->name);
    }

    public function test_show_displays_organization_details(): void
    {
        $organization = $this->makeOrganization('pending');

        $this->actingAs($this->employee)
            ->get(route('employee.organizations.show', $organization))
            ->assertOk()
            ->assertSee($organization->name)
            ->assertSee('الموافقة')
            ->assertSee('رفض');
    }

    public function test_employee_can_approve_an_organization(): void
    {
        $organization = $this->makeOrganization('pending');

        $this->actingAs($this->employee)
            ->patch(route('employee.organizations.approve', $organization))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('organizations', [
            'id' => $organization->id,
            'status' => 'approved',
            'approved_by' => $this->employee->id,
        ]);
    }

    public function test_employee_can_reject_an_organization(): void
    {
        $organization = $this->makeOrganization('pending');

        $this->actingAs($this->employee)
            ->patch(route('employee.organizations.reject', $organization))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('organizations', [
            'id' => $organization->id,
            'status' => 'rejected',
        ]);
    }

    public function test_employee_cannot_add_an_organization(): void
    {
        $response = $this->actingAs($this->employee)
            ->post('employee/organizations', [
                'name' => 'New Org',
                'email' => 'new@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

        $this->assertTrue(
            in_array($response->getStatusCode(), [404, 405]),
            'Expected 404/405 but got ' . $response->getStatusCode()
        );

        $this->assertDatabaseMissing('organizations', [
            'name' => 'New Org',
        ]);
    }
}