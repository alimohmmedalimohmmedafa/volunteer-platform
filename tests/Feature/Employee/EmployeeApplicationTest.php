<?php

namespace Tests\Feature\Employee;

use App\Models\Application;
use App\Models\Job;
use App\Models\Organization;
use App\Models\User;
use App\Models\VolunteerProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EmployeeApplicationTest extends TestCase
{
    use RefreshDatabase;

    private User $employee;
    private Application $application;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
        Storage::fake('local');

        $this->employee = User::create([
            'name' => 'Employee',
            'email' => 'emp@example.com',
            'password' => bcrypt('password'),
            'role' => 'employee',
            'status' => 'active',
        ]);

        $volunteer = User::create([
            'name' => 'Volunteer',
            'email' => 'vol@example.com',
            'password' => bcrypt('password'),
            'role' => 'volunteer',
        ]);

        $profile = $volunteer->volunteerProfile()->create([
            'cv' => 'volunteers/cvs/cv-test.pdf',
        ]);

        Storage::disk('local')->put(
            'volunteers/cvs/cv-test.pdf',
            'fake pdf content'
        );

        $orgUser = User::create([
            'name' => 'Org User',
            'email' => 'org@example.com',
            'password' => bcrypt('password'),
            'role' => 'organization',
        ]);

        $organization = Organization::create([
            'user_id' => $orgUser->id,
            'name' => 'Test Org',
            'status' => 'approved',
        ]);

        $job = $organization->jobs()->create([
            'title' => 'Community Help',
            'description' => 'Helping the community',
            'requirements' => 'Interest',
            'location' => 'Khartoum',
            'start_date' => now()->addDays(10),
            'end_date' => now()->addDays(20),
            'application_deadline' => now()->addDays(5),
            'status' => 'published',
        ]);

        $this->application = $profile->applications()->create([
            'job_id' => $job->id,
            'cv' => 'volunteers/cvs/cv-test.pdf',
            'status' => 'pending',
            'applied_at' => now(),
        ]);
    }

    public function test_index_shows_application_with_job_org_and_status(): void
    {
        $this->actingAs($this->employee)
            ->get(route('employee.applications.index'))
            ->assertOk()
            ->assertSee('Volunteer')
            ->assertSee('Community Help')
            ->assertSee('Test Org')
            ->assertSee('قيد المراجعة');
    }

    public function test_employee_can_download_cv(): void
    {
        $this->actingAs($this->employee)
            ->get(route('employee.applications.cv', $this->application))
            ->assertOk();
    }

    public function test_employee_cannot_accept_application(): void
    {
        $this->actingAs($this->employee)
            ->patch('employee/applications/' . $this->application->id . '/accept')
            ->assertNotFound();

        $this->assertDatabaseHas('applications', [
            'id' => $this->application->id,
            'status' => 'pending',
        ]);
    }

    public function test_employee_cannot_reject_application(): void
    {
        $this->actingAs($this->employee)
            ->patch('employee/applications/' . $this->application->id . '/reject')
            ->assertNotFound();

        $this->assertDatabaseHas('applications', [
            'id' => $this->application->id,
            'status' => 'pending',
        ]);
    }
}