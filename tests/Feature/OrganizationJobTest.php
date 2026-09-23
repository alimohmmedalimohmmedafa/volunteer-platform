<?php

namespace Tests\Feature;

use App\Models\Job;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizationJobTest extends TestCase
{
    use RefreshDatabase;

    private User $orgUserA;
    private User $orgUserB;
    private Organization $orgA;
    private Organization $orgB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->orgUserA = User::create([
            'name' => 'Org A',
            'email' => 'orga@example.com',
            'password' => bcrypt('password'),
            'role' => 'organization',
            'status' => 'active',
        ]);

        $this->orgUserB = User::create([
            'name' => 'Org B',
            'email' => 'orgb@example.com',
            'password' => bcrypt('password'),
            'role' => 'organization',
            'status' => 'active',
        ]);

        $this->orgA = Organization::create([
            'user_id' => $this->orgUserA->id,
            'name' => 'Org A',
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $this->orgUserA->id,
        ]);

        $this->orgB = Organization::create([
            'user_id' => $this->orgUserB->id,
            'name' => 'Org B',
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $this->orgUserB->id,
        ]);
    }

    private function validJobData(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Community Help',
            'description' => 'Helping the community',
            'requirements' => 'Interest and commitment',
            'location' => 'Khartoum',
            'start_date' => now()->addDays(10)->format('Y-m-d'),
            'end_date' => now()->addDays(20)->format('Y-m-d'),
            'application_deadline' => now()->addDays(5)->format('Y-m-d'),
        ], $overrides);
    }

    public function test_pending_organization_cannot_access_jobs_area(): void
    {
        $this->orgA->update(['status' => 'pending']);

        $this->actingAs($this->orgUserA)
            ->get(route('organization.jobs.index'))
            ->assertRedirect(route('organization.pending'));
    }

    public function test_organization_can_create_a_job_as_published(): void
    {
        $response = $this->actingAs($this->orgUserA)
            ->post(route('organization.jobs.store'), $this->validJobData());

        $response->assertRedirect();

        $this->assertDatabaseHas('jobs', [
            'title' => 'Community Help',
            'organization_id' => $this->orgA->id,
            'status' => 'published',
        ]);
    }

    public function test_job_validation_rejects_invalid_dates(): void
    {
        $this->actingAs($this->orgUserA)
            ->post(route('organization.jobs.store'), $this->validJobData([
                'end_date' => now()->subDay()->format('Y-m-d'),
            ]))
            ->assertSessionHasErrors('end_date');

        $this->assertDatabaseMissing('jobs', [
            'title' => 'Community Help',
        ]);
    }

    public function test_organization_can_see_its_own_jobs_only(): void
    {
        $own = $this->orgA->jobs()->create([
            'title' => 'Own Job',
            'description' => 'Desc',
            'requirements' => 'Req',
            'location' => 'Khartoum',
            'start_date' => now()->addDays(10),
            'end_date' => now()->addDays(20),
            'application_deadline' => now()->addDays(5),
            'status' => 'published',
        ]);

        $other = $this->orgB->jobs()->create([
            'title' => 'Other Job',
            'description' => 'Desc',
            'requirements' => 'Req',
            'location' => 'Omdurman',
            'start_date' => now()->addDays(10),
            'end_date' => now()->addDays(20),
            'application_deadline' => now()->addDays(5),
            'status' => 'published',
        ]);

        $this->actingAs($this->orgUserA)
            ->get(route('organization.jobs.index'))
            ->assertOk()
            ->assertSee('Own Job')
            ->assertDontSee('Other Job');
    }

    public function test_organization_cannot_access_another_organizations_job(): void
    {
        $other = $this->orgB->jobs()->create([
            'title' => 'Other Job',
            'description' => 'Desc',
            'requirements' => 'Req',
            'location' => 'Omdurman',
            'start_date' => now()->addDays(10),
            'end_date' => now()->addDays(20),
            'application_deadline' => now()->addDays(5),
            'status' => 'published',
        ]);

        $this->actingAs($this->orgUserA)
            ->get(route('organization.jobs.show', $other))
            ->assertForbidden();

        $this->actingAs($this->orgUserA)
            ->patch(route('organization.jobs.update', $other), $this->validJobData())
            ->assertForbidden();

        $this->actingAs($this->orgUserA)
            ->patch(route('organization.jobs.cancel', $other))
            ->assertForbidden();
    }

    public function test_organization_can_cancel_own_job(): void
    {
        $job = $this->orgA->jobs()->create([
            'title' => 'Job A',
            'description' => 'Desc',
            'requirements' => 'Req',
            'location' => 'Khartoum',
            'start_date' => now()->addDays(10),
            'end_date' => now()->addDays(20),
            'application_deadline' => now()->addDays(5),
            'status' => 'published',
        ]);

        $this->actingAs($this->orgUserA)
            ->patch(route('organization.jobs.cancel', $job))
            ->assertRedirect();

        $this->assertDatabaseHas('jobs', [
            'id' => $job->id,
            'status' => 'cancelled',
        ]);
    }

    public function test_organization_can_complete_own_job(): void
    {
        $job = $this->orgA->jobs()->create([
            'title' => 'Job A',
            'description' => 'Desc',
            'requirements' => 'Req',
            'location' => 'Khartoum',
            'start_date' => now()->addDays(10),
            'end_date' => now()->addDays(20),
            'application_deadline' => now()->addDays(5),
            'status' => 'published',
        ]);

        $this->actingAs($this->orgUserA)
            ->patch(route('organization.jobs.complete', $job))
            ->assertRedirect();

        $this->assertDatabaseHas('jobs', [
            'id' => $job->id,
            'status' => 'completed',
        ]);
    }

    public function test_organization_can_update_own_job(): void
    {
        $job = $this->orgA->jobs()->create([
            'title' => 'Job A',
            'description' => 'Desc',
            'requirements' => 'Req',
            'location' => 'Khartoum',
            'start_date' => now()->addDays(10),
            'end_date' => now()->addDays(20),
            'application_deadline' => now()->addDays(5),
            'status' => 'published',
        ]);

        $this->actingAs($this->orgUserA)
            ->patch(route('organization.jobs.update', $job), $this->validJobData([
                'title' => 'Updated Title',
            ]))
            ->assertRedirect();

        $this->assertDatabaseHas('jobs', [
            'id' => $job->id,
            'title' => 'Updated Title',
        ]);
    }
}