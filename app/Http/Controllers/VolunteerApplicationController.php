<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VolunteerApplicationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $profile = $user->volunteerProfile;

        if (!$profile) {
            $applications = collect();
        } else {
            $applications = $profile->applications()
                ->with([
                    'job.organization',
                ])
                ->latest('applied_at')
                ->paginate(10);
        }

        return view(
            'pages.volunteer.applications.index',
            compact('applications')
        );
    }

    /**
     * Apply to a published volunteer opportunity.
     */
    public function store(Request $request, Job $job)
    {
        $user = Auth::user();

        $profile = $user->volunteerProfile;

        if (!$profile) {
            return back()->with('error', 'يرجى استكمال ملفك الشخصي أولاً.');
        }

        if (!$profile->cv) {
            return back()->with(
                'error',
                'يرجى رفع السيرة الذاتية قبل التقديم.'
            );
        }

        $deadline = \Illuminate\Support\Carbon::parse(
            $job->application_deadline
        );

        $closed = $job->status !== 'published'
            || $deadline->isPast();

        if ($closed) {
            return back()->with('error', 'لقد أُغلقت فرصة التقديم.');
        }

        $alreadyApplied = $profile->applications()
            ->where('job_id', $job->id)
            ->exists();

        if ($alreadyApplied) {
            return back()->with(
                'error',
                'You have already applied for this opportunity.'
            );
        }

        $profile->applications()->create([
            'job_id' => $job->id,
            'cv' => $profile->cv,
            'status' => 'pending',
            'applied_at' => now(),
        ]);

        return back()->with('success', 'تم تقديم طلبك بنجاح.');
    }

    /**
     * Download the CV of the volunteer's own application.
     */
    public function downloadCv(Application $application)
    {
        $profile = Auth::user()->volunteerProfile;

        if (!$profile || $application->volunteer_id !== $profile->id) {
            abort(403, 'غير مسموح لك بالوصول إلى هذا الملف.');
        }

        return $this->streamCv($application);
    }

    private function streamCv(Application $application): StreamedResponse
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
}