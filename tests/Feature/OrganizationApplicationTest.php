<?php

namespace Tests\Feature;

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

class OrganizationApplicationTest extends TestCase
{
    use RefreshDatabase;

    private Organization $organization;
    private Job $job;
    private VolunteerProfile $profile;
    private User $volunteerUser;
    private Application $application;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
        Storage::fake('local');

        Storage::disk('local')->put(
            'volunteers/cvs/cv-one.pdf',
            'fake-pdf'
        );

        $this->organization = $this->makeOrganization('approved');
        $this->job = $this->makeJob($this->organization);

        $this->volunteerUser = User::create([
            'name' => 'Volunteer One',
            'email' => 'volunteer@example.com',
            'password' => bcrypt('password'),
            'role' => 'volunteer',
        ]);

        $this->profile = $this->volunteerUser->volunteerProfile()->create([
            'cv' => 'volunteers/cvs/cv-one.pdf',
        ]);

        $this->application = $this->profile->applications()->create([
            'job_id' => $this->job->id,
            'cv' => 'volunteers/cvs/cv-one.pdf',
            'status' => 'pending',
            'applied_at' => now(),
        ]);
    }

    private function makeOrganization(string $status): Organization
    {
        $user = User::create([
            'name' => 'Org User',
            'email' => fake()->unique()->safeEmail(),
            'password' => bcrypt('password'),
            'role' => 'organization',
        ]);

        return Organization::create([
            'user_id' => $user->id,
            'name' => 'Rescue Org',
            'status' => $status,
        ]);
    }

    private function makeJob(Organization $organization): Job
    {
        return $organization->jobs()->create([
            'title' => 'Tutoring kids',
            'description' => 'Tutor kids after school',
            'requirements' => 'Teaching interest',
            'location' => 'Khartoum',
            'start_date' => now()->addDays(10),
            'end_date' => now()->addDays(20),
            'application_deadline' => now()->addDays(5),
            'status' => 'published',
        ]);
    }

    private function makeOtherOrganization(): Organization
    {
        $user = User::create([
            'name' => 'Other Org User',
            'email' => fake()->unique()->safeEmail(),
            'password' => bcrypt('password'),
            'role' => 'organization',
        ]);

        return Organization::create([
            'user_id' => $user->id,
            'name' => 'Other Org',
            'status' => 'approved',
        ]);
    }

    public function test_approved_organization_can_see_its_own_applications(): void
    {
        $this->actingAs($this->organization->user)
            ->get(route('organization.applications.index'))
            ->assertOk()
            ->assertSee('Tutoring kids')
            ->assertSee('Volunteer One');
    }

    public function test_organization_sees_only_its_own_applications(): void
    {
        $otherOrg = $this->makeOtherOrganization();
        $otherJob = $this->makeJob($otherOrg);

        $otherVolunteer = User::create([
            'name' => 'Volunteer Other',
            'email' => fake()->unique()->safeEmail(),
            'password' => bcrypt('password'),
            'role' => 'volunteer',
        ]);

        $otherProfile = $otherVolunteer->volunteerProfile()->create([
            'cv' => 'volunteers/cvs/cv-two.pdf',
        ]);

        $otherProfile->applications()->create([
            'job_id' => $otherJob->id,
            'cv' => 'volunteers/cvs/cv-two.pdf',
            'status' => 'pending',
            'applied_at' => now(),
        ]);

        $this->actingAs($this->organization->user)
            ->get(route('organization.applications.index'))
            ->assertOk()
            ->assertSee('Tutoring kids')
            ->assertDontSee('Volunteer Other');
    }

    public function test_pending_organization_is_redirected(): void
    {
        $pendingOrg = $this->makeOrganization('pending');

        $this->actingAs($pendingOrg->user)
            ->get(route('organization.applications.index'))
            ->assertRedirect(route('organization.pending'));
    }

    public function test_accept_sets_status_and_creates_notification_and_email(): void
    {
        $this->actingAs($this->organization->user)
            ->patch(route('organization.applications.accept', $this->application))
            ->assertRedirect();

        $this->assertDatabaseHas('applications', [
            'id' => $this->application->id,
            'status' => 'accepted',
        ]);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->volunteerUser->id,
            'title' => 'Application Accepted',
            'type' => 'application_accepted',
            'is_read' => 0,
        ]);

        $notification = Notification::where('user_id', $this->volunteerUser->id)
            ->first();

        $this->assertStringContainsString(
            'Tutoring kids',
            $notification->message
        );

        Mail::assertSent(
            ApplicationStatusMail::class,
            function ($mail) {
                return $mail->hasTo($this->volunteerUser->email)
                    && $mail->status === 'accepted';
            }
        );
    }

    public function test_reject_sets_status_and_creates_notification_and_email(): void
    {
        $this->actingAs($this->organization->user)
            ->patch(route('organization.applications.reject', $this->application))
            ->assertRedirect();

        $this->assertDatabaseHas('applications', [
            'id' => $this->application->id,
            'status' => 'rejected',
        ]);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->volunteerUser->id,
            'title' => 'Application Update',
            'type' => 'application_rejected',
            'is_read' => 0,
        ]);

        Mail::assertSent(
            ApplicationStatusMail::class,
            function ($mail) {
                return $mail->hasTo($this->volunteerUser->email)
                    && $mail->status === 'rejected';
            }
        );
    }

    public function test_cannot_accept_application_that_was_rejected(): void
    {
        $this->application->update(['status' => 'rejected']);

        $this->actingAs($this->organization->user)
            ->patch(route('organization.applications.accept', $this->application))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('applications', [
            'id' => $this->application->id,
            'status' => 'rejected',
        ]);

        Mail::assertNothingSent();
    }

    public function test_cannot_reject_application_that_was_accepted(): void
    {
        $this->application->update(['status' => 'accepted']);

        $this->actingAs($this->organization->user)
            ->patch(route('organization.applications.reject', $this->application))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('applications', [
            'id' => $this->application->id,
            'status' => 'accepted',
        ]);

        Mail::assertNothingSent();
    }

    public function test_organization_cannot_accept_another_organizations_application(): void
    {
        $otherOrg = $this->makeOtherOrganization();

        $this->actingAs($otherOrg->user)
            ->patch(route('organization.applications.accept', $this->application))
            ->assertNotFound();

        $this->assertDatabaseHas('applications', [
            'id' => $this->application->id,
            'status' => 'pending',
        ]);
    }

    public function test_organization_cannot_reject_another_organizations_application(): void
    {
        $otherOrg = $this->makeOtherOrganization();

        $this->actingAs($otherOrg->user)
            ->patch(route('organization.applications.reject', $this->application))
            ->assertNotFound();

        $this->assertDatabaseHas('applications', [
            'id' => $this->application->id,
            'status' => 'pending',
        ]);
    }

    public function test_organization_can_download_cv_of_its_application(): void
    {
        $this->actingAs($this->organization->user)
            ->get(route('organization.applications.cv', $this->application))
            ->assertOk();
    }

    public function test_organization_cannot_download_cv_of_another_organization(): void
    {
        $otherOrg = $this->makeOtherOrganization();

        $this->actingAs($otherOrg->user)
            ->get(route('organization.applications.cv', $this->application))
            ->assertNotFound();
    }

    public function test_volunteer_cannot_access_organization_applications(): void
    {
        $this->actingAs($this->volunteerUser)
            ->get(route('organization.applications.index'))
            ->assertForbidden();
    }

    public function test_organization_cannot_access_volunteer_applications(): void
    {
        $this->actingAs($this->organization->user)
            ->get(route('volunteer.applications.index'))
            ->assertForbidden();
    }
}