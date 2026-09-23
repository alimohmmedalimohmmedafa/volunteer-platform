<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class VolunteerNotificationController extends Controller
{
    /**
     * Show all notifications of the current volunteer.
     */
    public function index()
    {
        $notifications = auth()->user()->notifications()
            ->latest()
            ->paginate(12);

        return view(
            'pages.volunteer.notifications.index',
            compact('notifications')
        );
    }

    /**
     * Mark a single notification as read.
     */
    public function read(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403, 'غير مسموح لك بالوصول إلى هذا الإشعار.');
        }

        if (!$notification->is_read) {
            $notification->update(['is_read' => true]);
        }

        return back()->with('success', 'تم تحديد الإشعار كمقروء.');
    }

    /**
     * Mark all notifications of the current volunteer as read.
     */
    public function readAll()
    {
        auth()->user()->notifications()
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'تم تحديد جميع الإشعارات كمقروءة.');
    }
}