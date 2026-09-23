<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $volunteer;

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

        $this->volunteer = User::create([
            'name' => 'Ahmad Hassan',
            'email' => 'ahmad@example.com',
            'password' => bcrypt('password'),
            'role' => 'volunteer',
            'status' => 'active',
        ]);
    }

    public function test_index_lists_users_with_roles(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.users.index'))
            ->assertOk()
            ->assertSee('Admin')
            ->assertSee('Ahmad Hassan');
    }

    public function test_index_filters_by_search(): void
    {
        User::create([
            'name' => 'Sara Ali',
            'email' => 'sara@example.com',
            'password' => bcrypt('password'),
            'role' => 'volunteer',
        ]);

        $this->actingAs($this->admin)
            ->get(route('admin.users.index', ['search' => 'Ahmad']))
            ->assertOk()
            ->assertSee('Ahmad Hassan')
            ->assertDontSee('Sara Ali');
    }

    public function test_index_filters_by_role(): void
    {
        User::create([
            'name' => 'Org User',
            'email' => 'org@example.com',
            'password' => bcrypt('password'),
            'role' => 'organization',
        ]);

        $this->actingAs($this->admin)
            ->get(route('admin.users.index', ['role' => 'organization']))
            ->assertOk()
            ->assertSee('Org User')
            ->assertDontSee('Ahmad Hassan');
    }

    public function test_admin_can_suspend_a_user(): void
    {
        $this->actingAs($this->admin)
            ->patch(route('admin.users.suspend', $this->volunteer))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $this->volunteer->id,
            'status' => 'suspended',
        ]);
    }

    public function test_suspend_does_not_delete_user(): void
    {
        $this->actingAs($this->admin)
            ->patch(route('admin.users.suspend', $this->volunteer));

        $this->assertDatabaseHas('users', [
            'id' => $this->volunteer->id,
            'role' => 'volunteer',
        ]);

        $this->assertTrue($this->volunteer->fresh()->exists);
    }

    public function test_admin_can_activate_a_suspended_user(): void
    {
        $this->volunteer->update(['status' => 'suspended']);

        $this->actingAs($this->admin)
            ->patch(route('admin.users.activate', $this->volunteer))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $this->volunteer->id,
            'status' => 'active',
        ]);
    }

    public function test_suspended_user_cannot_login(): void
    {
        $this->volunteer->update(['status' => 'suspended']);

        $this->post(route('login'), [
            'email' => 'ahmad@example.com',
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_suspended_user_cannot_access_areas(): void
    {
        $this->volunteer->update(['status' => 'suspended']);

        $this->actingAs($this->volunteer)
            ->get(route('volunteer.dashboard'))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');
    }

    public function test_admin_cannot_suspend_another_admin(): void
    {
        $otherAdmin = User::create([
            'name' => 'Admin Two',
            'email' => 'admin2@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->actingAs($this->admin)
            ->patch(route('admin.users.suspend', $otherAdmin))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', [
            'id' => $otherAdmin->id,
            'status' => 'active',
        ]);
    }
}