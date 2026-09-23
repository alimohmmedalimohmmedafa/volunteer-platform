<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ApplicationStatusMail;
use App\Models\Application;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $status = in_array($status, ['pending', 'accepted', 'rejected'], true)
            ? $status
            : null;

        $applications = Application::query()
            ->with([
                'job.organization',
                'volunteer.user',
            ])
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest('applied_at')
            ->paginate(15)
            ->withQueryString();

        return view(
            'pages.admin.applications.index',
            compact('applications', 'status')
        );
    }

    public function downloadCv(Application $application)
    {
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

    public function accept(Application $application): RedirectResponse
    {
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

    public function reject(Application $application): RedirectResponse
    {
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