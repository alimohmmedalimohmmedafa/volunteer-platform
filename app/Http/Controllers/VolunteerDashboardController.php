<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class VolunteerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $profile = $user->volunteerProfile;

        $applications = $profile
            ? $profile->applications()
            : collect();

        $applicationsCount = $profile
            ? $profile->applications()->count()
            : 0;

        $pendingCount = $profile
            ? $profile->applications()
                ->where('status', 'pending')
                ->count()
            : 0;

        $acceptedCount = $profile
            ? $profile->applications()
                ->where('status', 'accepted')
                ->count()
            : 0;

        $rejectedCount = $profile
            ? $profile->applications()
                ->where('status', 'rejected')
                ->count()
            : 0;

        $recentNotifications = $user->notifications()
            ->latest()
            ->take(5)
            ->get();

        $unreadNotificationsCount = $user->notifications()
            ->where('is_read', false)
            ->count();

        return view('pages.volunteer.dashboard', compact(
            'user',
            'profile',
            'applicationsCount',
            'pendingCount',
            'acceptedCount',
            'rejectedCount',
            'recentNotifications',
            'unreadNotificationsCount'
        ));
    }
}