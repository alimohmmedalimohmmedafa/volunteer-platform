<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->query('search', ''));

        $role = $request->query('role');
        $role = in_array($role, ['volunteer', 'organization', 'admin', 'employee'], true)
            ? $role
            : null;

        $users = User::query()
            ->withCount('notifications')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($role, function ($query) use ($role) {
                $query->where('role', $role);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view(
            'pages.admin.users.index',
            compact('users', 'search', 'role')
        );
    }

    public function suspend(User $user): RedirectResponse
    {
        if ($user->role === 'admin') {
            return back()->with(
                'error',
                'لا يمكن تعطيل حساب مدير آخر.'
            );
        }

        if ($user->id === auth()->id()) {
            return back()->with(
                'error',
                'لا يمكنك تعطيل حسابك بنفسك.'
            );
        }

        $user->update(['status' => 'suspended']);

        return back()->with(
            'success',
            "تم تعطيل حساب «{$user->name}»."
        );
    }

    public function activate(User $user): RedirectResponse
    {
        $user->update(['status' => 'active']);

        return back()->with(
            'success',
            "تم تفعيل حساب «{$user->name}»."
        );
    }
}