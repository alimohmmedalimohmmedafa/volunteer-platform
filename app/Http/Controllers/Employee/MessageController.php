<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter');
        $filter = $filter === 'unread' ? 'unread' : null;

        $messages = Message::query()
            ->when($filter === 'unread', function ($query) {
                $query->where('is_read', false);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $unreadCount = Message::where('is_read', false)->count();

        return view(
            'pages.employee.messages.index',
            compact('messages', 'filter', 'unreadCount')
        );
    }

    public function show(Message $message)
    {
        $message->update(['is_read' => true]);

        return view('pages.employee.messages.show', compact('message'));
    }

    public function markRead(Message $message): RedirectResponse
    {
        $message->update(['is_read' => true]);

        return redirect()
            ->route('employee.messages.index')
            ->with('success', 'تم تحديد الرسالة كمقروءة.');
    }
}