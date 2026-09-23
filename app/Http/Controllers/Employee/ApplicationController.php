<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
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
            'pages.employee.applications.index',
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
}