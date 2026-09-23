<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminEmployeeTest extends TestCase
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

    public function test_index_lists_employees_only(): void
    {
        User::create([
            'name' => 'Emp One',
            'email' => 'emp1@example.com',
            'password' => bcrypt('password'),
            'role' => 'employee',
        ]);

        User::create([
            'name' => 'Volunteer',
            'email' => 'vol@example.com',
            'password' => bcrypt('password'),
            'role' => 'volunteer',
        ]);

        $this->actingAs($this->admin)
            ->get(route('admin.employees.index'))
            ->assertOk()
            ->assertSee('Emp One')
            ->assertDontSee('Volunteer');
    }

    public function test_admin_can_create_employee(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.employees.store'), [
                'name' => 'New Employee',
                'email' => 'newemp@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => 'newemp@example.com',
            'role' => 'employee',
            'status' => 'active',
        ]);
    }

    public function test_created_employee_can_login(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.employees.store'), [
                'name' => 'New Employee',
                'email' => 'newemp@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

        $this->actingAs($this->admin)->post(route('logout'));

        $this->post(route('login'), [
            'email' => 'newemp@example.com',
            'password' => 'password123',
        ])->assertRedirect(route('employee.dashboard'));

        $this->assertAuthenticated();
    }

    public function test_admin_cannot_create_employee_with_duplicate_email(): void
    {
        User::create([
            'name' => 'Existing',
            'email' => 'existing@example.com',
            'password' => bcrypt('password'),
            'role' => 'volunteer',
        ]);

        $this->from(route('admin.employees.create'))
            ->actingAs($this->admin)
            ->post(route('admin.employees.store'), [
                'name' => 'New Employee',
                'email' => 'existing@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ])
            ->assertSessionHasErrors('email');
    }
}