<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;

class OrganizationJobController extends Controller
{
    public function index()
    {
        $organization = auth()->user()->organization;

        $jobs = $organization->jobs()
            ->withCount('applications')
            ->latest()
            ->paginate(10);

        return view('pages.organization.jobs.index', compact('jobs'));
    }

    public function create()
    {
        return view('pages.organization.jobs.create');
    }




public function store(Request $request)
    {
        $validated = $request->validate([
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
        ], [
            'title.required' => 'يرجى إدخال عنوان الفرصة.',
            'title.string' => 'عنوان الفرصة يجب أن يكون نصاً.',
            'title.max' => 'عنوان الفرصة يجب ألا يتجاوز 255 حرفاً.',
            'description.required' => 'يرجى إدخال وصف الفرصة.',
            'requirements.required' => 'يرجى إدخال متطلبات الفرصة.',
            'location.required' => 'يرجى إدخال موقع الفرصة.',
            'location.string' => 'الموقع يجب أن يكون نصاً.',
            'location.max' => 'الموقع يجب ألا يتجاوز 255 حرفاً.',
            'start_date.required' => 'يرجى إدخال تاريخ بداية الفرصة.',
            'start_date.date' => 'تاريخ البداية غير صحيح.',
            'start_date.after_or_equal' => 'تاريخ البداية يجب أن يكون اليوم أو بعده.',
            'end_date.required' => 'يرجى إدخال تاريخ نهاية الفرصة.',
            'end_date.date' => 'تاريخ النهاية غير صحيح.',
            'end_date.after' => 'تاريخ النهاية يجب أن يكون بعد تاريخ البداية.',
            'application_deadline.required' => 'يرجى إدخال آخر موعد للتقديم.',
            'application_deadline.date' => 'آخر موعد للتقديم غير صحيح.',
            'application_deadline.before_or_equal' =>
                'آخر موعد للتقديم يجب أن يكون قبل أو في تاريخ بداية الفرصة.',
        ]);

        $job = auth()->user()->organization->jobs()->create([
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
            ->route('organization.jobs.show', $job)
            ->with('success', 'تم إنشاء فرصة التطوع بنجاح.');
    }


    public function show(Job $job)
    {
        $this->authorizeOrganizationJob($job);

        $job->load('organization');
        $job->loadCount('applications');

        return view('pages.organization.jobs.show', compact('job'));
    }

    public function edit(Job $job)
    {
        $this->authorizeOrganizationJob($job);

        return view('pages.organization.jobs.edit', compact('job'));
    }

    public function update(Request $request, Job $job)
    {
        $this->authorizeOrganizationJob($job);

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
            ->route('organization.jobs.show', $job)
            ->with('success', 'تم تحديث الوظيفة بنجاح.');
    }

    public function cancel(Job $job)
    {
        $this->authorizeOrganizationJob($job);

        $job->update([
            'status' => 'cancelled',
        ]);

        return redirect()
            ->route('organization.jobs.show', $job)
            ->with('success', 'تم إلغاء فرصة التطوع.');
    }

    public function complete(Job $job)
    {
        $this->authorizeOrganizationJob($job);

        $job->update([
            'status' => 'completed',
        ]);

        return redirect()
            ->route('organization.jobs.show', $job)
            ->with('success', 'تم إكمال فرصة التطوع.');
    }

    private function authorizeOrganizationJob(Job $job)
    {
        $organization = auth()->user()->organization;

        if ($job->organization_id !== $organization->id) {
            abort(403, 'لا يمكنك الوصول إلى هذه الوظيفة.');
        }
    }
}