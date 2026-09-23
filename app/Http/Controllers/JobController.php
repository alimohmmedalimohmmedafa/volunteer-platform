<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Organization;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Display published volunteer opportunities.
     */
    public function index(Request $request)
    {
        $query = Job::with('organization')
            ->where('status', 'published');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Location filter
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        // Organization filter
        if ($request->filled('organization_id')) {
            $query->where(
                'organization_id',
                $request->organization_id
            );
        }

        // Application deadline filter
        if ($request->filled('deadline')) {
            if ($request->deadline === 'open') {
                $query->whereDate(
                    'application_deadline',
                    '>=',
                    now()->toDateString()
                );
            }

            if ($request->deadline === 'closed') {
                $query->whereDate(
                    'application_deadline',
                    '<',
                    now()->toDateString()
                );
            }
        }

        $jobs = $query
            ->withCount('applications')
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $organizations = Organization::query()
            ->whereHas('jobs', function ($q) {
                $q->where('status', 'published');
            })
            ->orderBy('name')
            ->get();

        return view('pages.jobs.index', compact(
            'jobs',
            'organizations'
        ));
    }

    /**
     * Display a volunteer opportunity.
     */
    public function show(Job $job)
    {
        abort_if($job->status !== 'published', 404);

        $job->load('organization');
        $job->loadCount('applications');

        return view('pages.jobs.show', compact('job'));
    }
}