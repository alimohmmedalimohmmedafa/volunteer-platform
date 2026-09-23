<?php

namespace Tests\Feature\Employee;

use App\Models\Job;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeJobTest extends TestCase
{
    use RefreshDatabase;

    private User $employee;
    private Organization $organization;
    private Job $job;

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

        $orgUser = User::create([
            'name' => 'Org User',
            'email' => 'org@example.com',
            'password' => bcrypt('password'),
            'role' => 'organization',
        ]);

        $this->organization = Organization::create([
            'user_id' => $orgUser->id,
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

    public function test_index_lists_all_jobs(): void
    {
        $this->actingAs($this->employee)
            ->get(route('employee.jobs.index'))
            ->assertOk()
            ->assertSee('Community Help')
            ->assertSee('Org A');
    }

    public function test_edit_page_shows_job_form(): void
    {
        $this->actingAs($this->employee)
            ->get(route('employee.jobs.edit', $this->job))
            ->assertOk()
            ->assertSee('Community Help');
    }

    public function test_employee_can_update_a_job(): void
    {
        $this->actingAs($this->employee)
            ->patch(route('employee.jobs.update', $this->job), [
                'title' => 'Edited Job',
                'description' => 'Desc',
                'requirements' => 'Req',
                'location' => 'Omdurman',
                'start_date' => $this->job->start_date->format('Y-m-d'),
                'end_date' => $this->job->end_date->format('Y-m-d'),
                'application_deadline' => $this->job->application_deadline->format('Y-m-d'),
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('jobs', [
            'id' => $this->job->id,
            'title' => 'Edited Job',
            'location' => 'Omdurman',
        ]);
    }

    public function test_employee_can_cancel_a_job(): void
    {
        $this->actingAs($this->employee)
            ->patch(route('employee.jobs.cancel', $this->job))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('jobs', [
            'id' => $this->job->id,
            'status' => 'cancelled',
        ]);
    }

    public function test_employee_can_complete_a_job(): void
    {
        $this->actingAs($this->employee)
            ->patch(route('employee.jobs.complete', $this->job))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('jobs', [
            'id' => $this->job->id,
            'status' => 'completed',
        ]);
    }

    public function test_employee_cannot_create_a_job(): void
    {
        $response = $this->actingAs($this->employee)
            ->post('employee/jobs', [
                'organization_id' => $this->organization->id,
                'title' => 'New Job',
                'description' => 'Desc',
                'requirements' => 'Req',
                'location' => 'Khartoum',
                'start_date' => now()->addDays(3)->format('Y-m-d'),
                'end_date' => now()->addDays(13)->format('Y-m-d'),
                'application_deadline' => now()->addDay()->format('Y-m-d'),
            ]);

        $this->assertTrue(
            in_array($response->getStatusCode(), [404, 405]),
            'Expected 404/405 but got ' . $response->getStatusCode()
        );

        $this->assertDatabaseMissing('jobs', [
            'title' => 'New Job',
        ]);
    }

    public function test_employee_cannot_assign_role_to_anyone(): void
    {
        $who = User::create([
            'name' => 'Who',
            'email' => 'who@example.com',
            'password' => bcrypt('password'),
            'role' => 'volunteer',
        ]);

        $this->actingAs($this->employee)
            ->patch('employee/users/' . $who->id . '/role', ['role' => 'admin'])
            ->assertNotFound();

        $this->assertDatabaseHas('users', [
            'id' => $who->id,
            'role' => 'volunteer',
        ]);
    }
}