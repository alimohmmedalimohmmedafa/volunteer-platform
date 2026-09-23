<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Organization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $organizations = Organization::orderBy('name')->get();

        $organizationId = $request->query('organization');

        $jobs = Job::with('organization')
            ->withCount('applications')
            ->when($organizationId, function ($query) use ($organizationId) {
                $query->where('organization_id', $organizationId);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'pages.admin.jobs.index',
            compact('jobs', 'organizations', 'organizationId')
        );
    }

    public function create()
    {
        $organizations = Organization::where('status', 'approved')
            ->orderBy('name')
            ->get();

        return view('pages.admin.jobs.create', compact('organizations'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateJob($request);

        $organization = Organization::findOrFail($validated['organization_id']);

        if ($organization->status !== 'approved') {
            return back()
                ->withInput()
                ->with('error', 'لا يمكن إضافة وظائف لمنظمة غير معتمدة.');
        }

        $job = $organization->jobs()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'requirements' => $validated['requirements'],
            'location' => $validated['location'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'application_deadline' => $validated['application_deadline'],
            'status' => 'published',
        ]);

        return redirect()
            ->route('admin.jobs.index')
            ->with('success', "تم إنشاء وظيفة «{$job->title}» بنجاح.");
    }

    public function edit(Job $job)
    {
        $organizations = Organization::where('status', 'approved')
            ->orderBy('name')
            ->get();

        return view(
            'pages.admin.jobs.edit',
            compact('job', 'organizations')
        );
    }

    public function update(Request $request, Job $job): RedirectResponse
    {
        $validated = $this->validateJob($request);

        $job->update([
            'organization_id' => $validated['organization_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'requirements' => $validated['requirements'],
            'location' => $validated['location'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'application_deadline' => $validated['application_deadline'],
        ]);

        return redirect()
            ->route('admin.jobs.index')
            ->with('success', 'تم تحديث الوظيفة بنجاح.');
    }

    public function cancel(Job $job): RedirectResponse
    {
        $job->update(['status' => 'cancelled']);

        return redirect()
            ->route('admin.jobs.index')
            ->with('success', 'تم إلغاء الوظيفة.');
    }

    public function complete(Job $job): RedirectResponse
    {
        $job->update(['status' => 'completed']);

        return redirect()
            ->route('admin.jobs.index')
            ->with('success', 'تم إكمال الوظيفة.');
    }

    private function validateJob(Request $request): array
    {
        return $request->validate([
            'organization_id' => ['required', 'exists:organizations,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'requirements' => ['required', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'start_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],
            'end_date' => [
                'required',
                'date',
                'after:start_date',
            ],
            'application_deadline' => [
                'required',
                'date',
                'before_or_equal:start_date',
            ],
        ]);
    }
}