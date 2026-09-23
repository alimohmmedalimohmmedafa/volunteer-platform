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
use Tests\TestCase;

class VolunteerNotificationTest extends TestCase
{
    use RefreshDatabase;

    private User $volunteer;
    private User $otherVolunteer;
    private VolunteerProfile $profile;
    private Organization $organization;
    private Job $job;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();

        $this->volunteer = User::create([
            'name' => 'Volunteer A',
            'email' => 'a@example.com',
            'password' => bcrypt('password'),
            'role' => 'volunteer',
        ]);

        $this->profile = $this->volunteer->volunteerProfile()->create([
            'cv' => 'volunteers/cvs/cv-a.pdf',
        ]);

        $this->otherVolunteer = User::create([
            'name' => 'Volunteer B',
            'email' => 'b@example.com',
            'password' => bcrypt('password'),
            'role' => 'volunteer',
        ]);

        $orgUser = User::create([
            'name' => 'Org User',
            'email' => 'org@example.com',
            'password' => bcrypt('password'),
            'role' => 'organization',
        ]);

        $this->organization = Organization::create([
            'user_id' => $orgUser->id,
            'name' => 'Test Org',
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

    private function makeNotification(User $user, array $overrides = []): Notification
    {
        return Notification::create(array_merge([
            'user_id' => $user->id,
            'title' => 'Application Accepted',
            'message' => 'Your application for Community Help has been accepted.',
            'type' => 'application_accepted',
            'is_read' => false,
        ], $overrides));
    }

    public function test_user_sees_only_own_notifications(): void
    {
        $this->makeNotification($this->volunteer, [
            'title' => 'My Own Notification',
        ]);

        $this->makeNotification($this->otherVolunteer, [
            'title' => 'Other Users Notification',
        ]);

        $this->actingAs($this->volunteer)
            ->get(route('volunteer.notifications.index'))
            ->assertOk()
            ->assertSee('My Own Notification')
            ->assertDontSee('Other Users Notification');
    }

    public function test_user_cannot_mark_another_users_notification_as_read(): void
    {
        $otherNotification = $this->makeNotification($this->otherVolunteer);

        $this->actingAs($this->volunteer)
            ->patch(route('volunteer.notifications.read', $otherNotification))
            ->assertForbidden();

        $this->assertDatabaseHas('notifications', [
            'id' => $otherNotification->id,
            'is_read' => 0,
        ]);
    }

    public function test_mark_single_notification_as_read(): void
    {
        $notification = $this->makeNotification($this->volunteer);

        $this->actingAs($this->volunteer)
            ->patch(route('volunteer.notifications.read', $notification))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'is_read' => 1,
        ]);
    }

    public function test_mark_all_notifications_as_read(): void
    {
        $this->makeNotification($this->volunteer, ['title' => 'One']);
        $this->makeNotification($this->volunteer, ['title' => 'Two']);

        $this->actingAs($this->volunteer)
            ->patch(route('volunteer.notifications.readAll'))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->volunteer->id,
            'is_read' => 1,
        ]);
    }

    public function test_mark_all_does_not_touch_other_users_notifications(): void
    {
        $this->makeNotification($this->volunteer, ['title' => 'Mine']);
        $otherNotification = $this->makeNotification($this->otherVolunteer, [
            'title' => 'Theirs',
        ]);

        $this->actingAs($this->volunteer)
            ->patch(route('volunteer.notifications.readAll'))
            ->assertRedirect();

        $this->assertDatabaseHas('notifications', [
            'id' => $otherNotification->id,
            'is_read' => 0,
        ]);
    }

    public function test_volunteer_dashboard_shows_unread_count(): void
    {
        $this->makeNotification($this->volunteer);

        $this->actingAs($this->volunteer)
            ->get(route('volunteer.dashboard'))
            ->assertOk()
            ->assertSee('إشعاراتي')
            ->assertSee('غير مقروء');
    }

    public function test_navigation_badge_shows_number_of_unread_notifications(): void
    {
        $this->makeNotification($this->volunteer);
        $this->makeNotification($this->volunteer);

        $response = $this->actingAs($this->volunteer)
            ->get(route('volunteer.dashboard'))
            ->assertOk();

        $response->assertSee('class="badge bg-danger rounded-pill ms-1"', false);
    }

    public function test_accept_creates_notification_and_sends_email(): void
    {
        $application = $this->profile->applications()->create([
            'job_id' => $this->job->id,
            'cv' => 'volunteers/cvs/cv-a.pdf',
            'status' => 'pending',
            'applied_at' => now(),
        ]);

        $this->actingAs($this->organization->user)
            ->patch(route('organization.applications.accept', $application))
            ->assertRedirect();

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

    public function test_reject_creates_notification_and_sends_email(): void
    {
        $application = $this->profile->applications()->create([
            'job_id' => $this->job->id,
            'cv' => 'volunteers/cvs/cv-a.pdf',
            'status' => 'pending',
            'applied_at' => now(),
        ]);

        $this->actingAs($this->organization->user)
            ->patch(route('organization.applications.reject', $application))
            ->assertRedirect();

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

    public function test_guest_cannot_access_notifications(): void
    {
        $this->get(route('volunteer.notifications.index'))
            ->assertRedirect(route('login'));
    }

    public function test_organization_cannot_access_volunteer_notifications(): void
    {
        $this->actingAs($this->organization->user)
            ->get(route('volunteer.notifications.index'))
            ->assertForbidden();
    }
}