<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;

class OrganizationDashboardController extends Controller
{
    public function index()
    {
        $organization = auth()->user()->organization;

        $jobs = $organization->jobs();

        $publishedJobs = (clone $jobs)
            ->where('status', 'published')
            ->count();

        $completedJobs = (clone $jobs)
            ->where('status', 'completed')
            ->count();

        $cancelledJobs = (clone $jobs)
            ->where('status', 'cancelled')
            ->count();

        $totalApplicants = $organization->jobs()
            ->withCount('applications')
            ->get()
            ->sum('applications_count');

        return view('pages.organization.dashboard', compact(
            'organization',
            'publishedJobs',
            'completedJobs',
            'cancelledJobs',
            'totalApplicants'
        ));
    }
}