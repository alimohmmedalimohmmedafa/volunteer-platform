<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use App\Models\Organization;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'totalVolunteers' => User::where('role', 'volunteer')->count(),
            'totalOrganizations' => Organization::count(),
            'totalJobs' => Job::count(),
            'totalApplications' => Application::count(),
            'pendingOrganizations' => Organization::where('status', 'pending')->count(),
            'acceptedApplications' => Application::where('status', 'accepted')->count(),
            'rejectedApplications' => Application::where('status', 'rejected')->count(),
        ];

        return view('pages.admin.dashboard', compact('stats'));
    }
}