<?php

namespace App\Http\Controllers;

use App\Models\VolunteerMessage;

class VolunteerMessageController extends Controller
{
    public function index()
    {
        $messages = auth()->user()->volunteerMessages()
            ->with(['organization', 'application.job'])
            ->latest()
            ->paginate(12);

        return view('pages.volunteer.messages.index', compact('messages'));
    }

    public function show(VolunteerMessage $message)
    {
        abort_if($message->volunteer_id !== auth()->id(), 403);

        $message->load(['organization', 'application.job']);
        $message->update(['is_read' => true]);

        return view('pages.volunteer.messages.show', compact('message'));
    }
}