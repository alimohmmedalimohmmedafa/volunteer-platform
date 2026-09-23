<?php

namespace App\Http\Controllers\Employee;

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
            'pages.employee.jobs.index',
            compact('jobs', 'organizations', 'organizationId')
        );
    }

    public function edit(Job $job)
    {
        $job->load('organization');

        return view('pages.employee.jobs.edit', compact('job'));
    }

    public function update(Request $request, Job $job): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'requirements' => ['required', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'application_deadline' => [
                'required',
                'date',
                'before_or_equal:start_date',
            ],
        ]);

        $job->update($validated);

        return redirect()
            ->route('employee.jobs.index')
            ->with('success', 'تم تحديث الوظيفة بنجاح.');
    }

    public function cancel(Job $job): RedirectResponse
    {
        $job->update(['status' => 'cancelled']);

        return redirect()
            ->route('employee.jobs.index')
            ->with('success', 'تم إلغاء الوظيفة.');
    }

    public function complete(Job $job): RedirectResponse
    {
        $job->update(['status' => 'completed']);

        return redirect()
            ->route('employee.jobs.index')
            ->with('success', 'تم إكمال الوظيفة.');
    }
}