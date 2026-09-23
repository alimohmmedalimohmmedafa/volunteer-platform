<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use App\Models\Message;
use App\Models\Organization;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'totalOrganizations' => Organization::count(),
            'totalJobs' => Job::count(),
            'totalVolunteers' => User::where('role', 'volunteer')->count(),
            'totalApplications' => Application::count(),
            'unreadMessages' => Message::where('is_read', false)->count(),
        ];

        return view('pages.employee.dashboard', compact('stats'));
    }
}