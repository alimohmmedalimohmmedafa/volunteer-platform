<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Notification;
use App\Mail\ApplicationStatusMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrganizationApplicationController extends Controller
{
    public function index()
    {
        $organization = auth()->user()->organization;

        $applications = Application::query()
            ->whereHas('job', function ($query) use ($organization) {
                $query->where('organization_id', $organization->id);
            })
            ->with([
                'job',
                'volunteer.user',
            ])
            ->latest('applied_at')
            ->paginate(15);

        return view(
            'pages.organization.applications.index',
            compact('applications')
        );
    }

    public function volunteerProfile(Application $application)
    {
        $this->authorizeApplication($application);

        $application->load([
            'job',
            'volunteer.user',
        ]);

        return view(
            'pages.organization.applications.volunteer-profile',
            compact('application')
        );
    }

    /**
     * Accept a pending application.
     */
    public function accept(Application $application)
    {
        $this->authorizeApplication($application);

        if ($application->status !== 'pending') {
            return back()->with(
                'error',
                'لا يمكن تغيير حالة الطلب بعد اتخاذ القرار.'
            );
        }

        $application->update(['status' => 'accepted']);

        $this->notifyAndEmail($application, 'accepted');

        return back()->with('success', 'تم قبول طلب المتطوع.');
    }

    /**
     * Reject a pending application.
     */
    public function reject(Application $application)
    {
        $this->authorizeApplication($application);

        if ($application->status !== 'pending') {
            return back()->with(
                'error',
                'لا يمكن تغيير حالة الطلب بعد اتخاذ القرار.'
            );
        }

        $application->update(['status' => 'rejected']);

        $this->notifyAndEmail($application, 'rejected');

        return back()->with('success', 'تم رفض طلب المتطوع.');
    }

    /**
     * Download the CV attached to an application of this organization.
     */
    public function downloadCv(Application $application)
    {
        $this->authorizeApplication($application);

        if (!$application->cv
            || !Storage::disk('local')->exists($application->cv)) {
            abort(404, 'ملف السيرة الذاتية غير موجود.');
        }

        $filename = 'cv_' . preg_replace(
            '/[^A-Za-z0-9_.-]/',
            '_',
            (string) $application->volunteer?->user?->name
        ) . '.pdf';

        return Storage::disk('local')
            ->download($application->cv, $filename);
    }

    private function authorizeApplication(Application $application): void
    {
        $organization = auth()->user()->organization;

        if ($application->job->organization_id !== $organization->id) {
            abort(404, 'Application not found.');
        }
    }

    private function notifyAndEmail(
        Application $application,
        string $status
    ): void {
        $volunteer = $application->volunteer?->user;

        if (!$volunteer) {
            return;
        }

        $title = $status === 'accepted'
            ? 'Application Accepted'
            : 'Application Update';

        $message = $status === 'accepted'
            ? "Your application for {$application->job->title} has been accepted by {$application->job->organization->name}."
            : "Your application for {$application->job->title} has been rejected by {$application->job->organization->name}.";

        Notification::create([
            'user_id' => $volunteer->id,
            'title' => $title,
            'message' => $message,
            'type' => $status === 'accepted'
                ? 'application_accepted'
                : 'application_rejected',
            'is_read' => false,
        ]);

        Mail::to($volunteer->email)->send(
            new ApplicationStatusMail(
                volunteerName: $volunteer->name,
                jobTitle: $application->job->title,
                organizationName: $application->job->organization->name,
                status: $status,
            )
        );
    }
}