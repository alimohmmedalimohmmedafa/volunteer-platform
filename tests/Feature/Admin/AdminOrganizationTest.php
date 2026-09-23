<?php

namespace Tests\Feature\Admin;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOrganizationTest extends TestCase
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

    public function test_index_lists_all_organizations(): void
    {
        $this->makeOrganization('approved');
        $this->makeOrganization('pending');

        $this->actingAs($this->admin)
            ->get(route('admin.organizations.index'))
            ->assertOk()
            ->assertSee('إدارة المنظمات');
    }

    public function test_index_filters_by_status(): void
    {
        $approved = $this->makeOrganization('approved');
        $pending = $this->makeOrganization('pending');

        $this->actingAs($this->admin)
            ->get(route('admin.organizations.index', ['status' => 'pending']))
            ->assertOk()
            ->assertSee($pending->name)
            ->assertDontSee($approved->name);
    }

    public function test_admin_can_approve_a_pending_organization(): void
    {
        $organization = $this->makeOrganization('pending');

        $this->actingAs($this->admin)
            ->patch(route('admin.organizations.approve', $organization))
            ->assertRedirect();

        $this->assertDatabaseHas('organizations', [
            'id' => $organization->id,
            'status' => 'approved',
            'approved_by' => $this->admin->id,
        ]);
    }

    public function test_admin_can_reject_a_pending_organization(): void
    {
        $organization = $this->makeOrganization('pending');

        $this->actingAs($this->admin)
            ->patch(route('admin.organizations.reject', $organization))
            ->assertRedirect();

        $this->assertDatabaseHas('organizations', [
            'id' => $organization->id,
            'status' => 'rejected',
        ]);
    }

    public function test_admin_can_add_organization_manually_and_it_is_approved(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.organizations.store'), [
                'name' => 'New Org',
                'email' => 'neworg@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'phone' => '0911111111',
                'city' => 'Khartoum',
                'country' => 'SD',
                'bio' => 'A new organization',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => 'neworg@example.com',
            'role' => 'organization',
            'status' => 'active',
        ]);

        $this->assertDatabaseHas('organizations', [
            'name' => 'New Org',
            'status' => 'approved',
            'approved_by' => $this->admin->id,
        ]);
    }

    public function test_admin_cannot_add_organization_with_duplicate_email(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.organizations.store'), [
                'name' => 'New Org',
                'email' => 'neworg@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'phone' => '0911111111',
                'city' => 'Khartoum',
                'country' => 'SD',
            ])
            ->assertRedirect();
    }
}