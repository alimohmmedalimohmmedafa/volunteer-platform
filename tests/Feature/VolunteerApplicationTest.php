<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Job;
use App\Models\Organization;
use App\Models\User;
use App\Models\VolunteerProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VolunteerApplicationTest extends TestCase
{
    use RefreshDatabase;

    private User $volunteerUser;
    private VolunteerProfile $profile;
    private Organization $organization;
    private Job $job;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');

        $this->organization = $this->makeOrganization('approved');

        $this->job = $this->makeJob($this->organization, [
            'status' => 'published',
        ]);

        $this->volunteerUser = User::create([
            'name' => 'Volunteer One',
            'email' => 'volunteer@example.com',
            'password' => bcrypt('password'),
            'role' => 'volunteer',
        ]);

        $this->profile = $this->volunteerUser->volunteerProfile()->create([
            'cv' => 'volunteers/cvs/cv-one.pdf',
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
            'name' => 'Org ' . fake()->unique()->word(),
            'status' => $status,
        ]);
    }

    private function makeJob(Organization $organization, array $overrides = []): Job
    {
        return $organization->jobs()->create(array_merge([
            'title' => 'Help the community',
            'description' => 'A great opportunity',
            'requirements' => 'Willingness',
            'location' => 'Khartoum',
            'start_date' => now()->addDays(10),
            'end_date' => now()->addDays(20),
            'application_deadline' => now()->addDays(5),
            'status' => 'published',
        ], $overrides));
    }

    private function makeVolunteer(array $overrides = []): User
    {
        return User::create(array_merge([
            'name' => 'Volunteer Two',
            'email' => fake()->unique()->safeEmail(),
            'password' => bcrypt('password'),
            'role' => 'volunteer',
        ], $overrides));
    }

    public function test_guest_cannot_apply_and_is_redirected_to_login(): void
    {
        $this->post(route('volunteer.applications.apply', $this->job))
            ->assertRedirect(route('login'));

        $this->assertDatabaseCount('applications', 0);
    }

    public function test_volunteer_with_cv_can_apply(): void
    {
        $this->actingAs($this->volunteerUser)
            ->post(route('volunteer.applications.apply', $this->job))
            ->assertRedirect();

        $this->assertDatabaseHas('applications', [
            'job_id' => $this->job->id,
            'volunteer_id' => $this->profile->id,
            'cv' => 'volunteers/cvs/cv-one.pdf',
            'status' => 'pending',
        ]);
    }

    public function test_volunteer_without_cv_cannot_apply(): void
    {
        $this->profile->update(['cv' => null]);

        $this->actingAs($this->volunteerUser)
            ->post(route('volunteer.applications.apply', $this->job))
            ->assertRedirect()
            ->assertSessionHas('error', 'يرجى رفع السيرة الذاتية قبل التقديم.');

        $this->assertDatabaseCount('applications', 0);
    }

    public function test_volunteer_without_profile_cannot_apply(): void
    {
        $newVolunteer = $this->makeVolunteer();

        $this->actingAs($newVolunteer)
            ->post(route('volunteer.applications.apply', $this->job))
            ->assertRedirect();

        $this->assertDatabaseCount('applications', 0);
    }

    public function test_duplicate_application_is_rejected(): void
    {
        Application::create([
            'job_id' => $this->job->id,
            'volunteer_id' => $this->profile->id,
            'cv' => $this->profile->cv,
            'status' => 'pending',
            'applied_at' => now(),
        ]);

        $this->actingAs($this->volunteerUser)
            ->post(route('volunteer.applications.apply', $this->job))
            ->assertRedirect()
            ->assertSessionHas(
                'error',
                'You have already applied for this opportunity.'
            );

        $this->assertDatabaseCount('applications', 1);
    }

    public function test_cannot_apply_to_cancelled_job(): void
    {
        $this->job->update(['status' => 'cancelled']);

        $this->actingAs($this->volunteerUser)
            ->post(route('volunteer.applications.apply', $this->job))
            ->assertRedirect()
            ->assertSessionHas('error', 'لقد أُغلقت فرصة التقديم.');

        $this->assertDatabaseCount('applications', 0);
    }

    public function test_cannot_apply_to_completed_job(): void
    {
        $this->job->update(['status' => 'completed']);

        $this->actingAs($this->volunteerUser)
            ->post(route('volunteer.applications.apply', $this->job))
            ->assertRedirect()
            ->assertSessionHas('error', 'لقد أُغلقت فرصة التقديم.');

        $this->assertDatabaseCount('applications', 0);
    }

    public function test_cannot_apply_after_deadline(): void
    {
        $this->job->update([
            'application_deadline' => now()->subDay(),
        ]);

        $this->actingAs($this->volunteerUser)
            ->post(route('volunteer.applications.apply', $this->job))
            ->assertRedirect()
            ->assertSessionHas('error', 'لقد أُغلقت فرصة التقديم.');

        $this->assertDatabaseCount('applications', 0);
    }

    public function test_organization_user_cannot_apply(): void
    {
        $orgUser = $this->organization->user;

        $this->actingAs($orgUser)
            ->post(route('volunteer.applications.apply', $this->job))
            ->assertForbidden();

        $this->assertDatabaseCount('applications', 0);
    }

    public function test_admin_cannot_apply(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->post(route('volunteer.applications.apply', $this->job))
            ->assertForbidden();

        $this->assertDatabaseCount('applications', 0);
    }

    public function test_volunteer_my_applications_shows_only_own(): void
    {
        $otherVolunteer = $this->makeVolunteer();
        $otherProfile = $otherVolunteer->volunteerProfile()->create([
            'cv' => 'volunteers/cvs/cv-two.pdf',
        ]);

        $this->profile->applications()->create([
            'job_id' => $this->job->id,
            'cv' => $this->profile->cv,
            'status' => 'pending',
            'applied_at' => now(),
        ]);

        $this->actingAs($this->volunteerUser)
            ->get(route('volunteer.applications.index'))
            ->assertOk()
            ->assertSee('Help the community')
            ->assertDontSee('cv-two.pdf');

        $this->actingAs($otherVolunteer)
            ->get(route('volunteer.applications.index'))
            ->assertOk()
            ->assertDontSee('volunteers/cvs/cv-one.pdf');
    }

    public function test_volunteer_cannot_download_another_volunteers_cv(): void
    {
        $otherVolunteer = $this->makeVolunteer();
        $otherProfile = $otherVolunteer->volunteerProfile()->create([
            'cv' => 'volunteers/cvs/cv-two.pdf',
        ]);

        $application = $otherProfile->applications()->create([
            'job_id' => $this->job->id,
            'cv' => 'volunteers/cvs/cv-two.pdf',
            'status' => 'pending',
            'applied_at' => now(),
        ]);

        Storage::disk('local')->put(
            'volunteers/cvs/cv-two.pdf',
            'pdf-content'
        );

        $this->actingAs($this->volunteerUser)
            ->get(route('volunteer.applications.cv', $application))
            ->assertForbidden();
    }
}