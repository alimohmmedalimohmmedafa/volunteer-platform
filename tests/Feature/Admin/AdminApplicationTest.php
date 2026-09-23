<?php

namespace Tests\Feature\Admin;

use App\Mail\ApplicationStatusMail;
use App\Models\Application;
use App\Models\Job;
use App\Models\Notification;
use App\Models\Organization;
use App\Models\User;
use App\Models\VolunteerProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminApplicationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $volunteer;
    private VolunteerProfile $profile;
    private Job $job;
    private Application $application;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
        Storage::fake('local');

        $this->admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->volunteer = User::create([
            'name' => 'Volunteer',
            'email' => 'vol@example.com',
            'password' => bcrypt('password'),
            'role' => 'volunteer',
        ]);

        $this->profile = $this->volunteer->volunteerProfile()->create([
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

        $this->job = $organization->jobs()->create([
            'title' => 'Community Help',
            'description' => 'Helping the community',
            'requirements' => 'Interest',
            'location' => 'Khartoum',
            'start_date' => now()->addDays(10),
            'end_date' => now()->addDays(20),
            'application_deadline' => now()->addDays(5),
            'status' => 'published',
        ]);

        $this->application = $this->profile->applications()->create([
            'job_id' => $this->job->id,
            'cv' => 'volunteers/cvs/cv-test.pdf',
            'status' => 'pending',
            'applied_at' => now(),
        ]);
    }

    public function test_index_shows_application_with_job_org_and_volunteer(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.applications.index'))
            ->assertOk()
            ->assertSee('Volunteer')
            ->assertSee('Community Help')
            ->assertSee('Test Org');
    }

    public function test_index_filters_by_status(): void
    {
        $this->application->update(['status' => 'accepted']);

        $this->actingAs($this->admin)
            ->get(route('admin.applications.index', ['status' => 'pending']))
            ->assertOk()
            ->assertDontSee('Community Help');

        $this->actingAs($this->admin)
            ->get(route('admin.applications.index', ['status' => 'accepted']))
            ->assertOk()
            ->assertSee('Community Help');
    }

    public function test_admin_can_download_cv(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.applications.cv', $this->application))
            ->assertOk();
    }

    public function test_admin_can_accept_application_and_notifies_volunteer(): void
    {
        $this->actingAs($this->admin)
            ->patch(route('admin.applications.accept', $this->application))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('applications', [
            'id' => $this->application->id,
            'status' => 'accepted',
        ]);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->volunteer->id,
            'title' => 'Application Accepted',
            'type' => 'application_accepted',
            'is_read' => 0,
        ]);

        Mail::assertSent(
            ApplicationStatusMail::class,
            fn ($mail) => $mail->hasTo($this->volunteer->email)
        );
    }

    public function test_admin_can_reject_application_and_notifies_volunteer(): void
    {
        $this->actingAs($this->admin)
            ->patch(route('admin.applications.reject', $this->application))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('applications', [
            'id' => $this->application->id,
            'status' => 'rejected',
        ]);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->volunteer->id,
            'title' => 'Application Update',
            'type' => 'application_rejected',
            'is_read' => 0,
        ]);

        Mail::assertSent(
            ApplicationStatusMail::class,
            fn ($mail) => $mail->hasTo($this->volunteer->email)
        );
    }

    public function test_admin_cannot_change_status_of_a_decided_application(): void
    {
        $this->application->update(['status' => 'accepted']);

        $this->actingAs($this->admin)
            ->patch(route('admin.applications.accept', $this->application))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('applications', [
            'id' => $this->application->id,
            'status' => 'accepted',
        ]);
    }
}