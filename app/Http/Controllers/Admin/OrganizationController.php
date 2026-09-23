<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class OrganizationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $status = in_array($status, ['pending', 'approved', 'rejected'], true)
            ? $status
            : null;

        $organizations = Organization::with('user')
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'pages.admin.organizations.index',
            compact('organizations', 'status')
        );
    }

    public function create()
    {
        return view('pages.admin.organizations.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],

            'phone' => ['required', 'string', 'max:30'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'website' => ['nullable', 'url', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'organization',
            'status' => 'active',
        ]);

        $organization = Organization::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'bio' => $validated['bio'] ?? null,
            'website' => $validated['website'] ?? null,
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'],
            'country' => $validated['country'],
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        return redirect()
            ->route('admin.organizations.show', $organization)
            ->with('success', 'تمت إضافة المنظمة واعتمادها مباشرة.');
    }

    public function show(Organization $organization)
    {
        $organization->load('user', 'approver');

        return view(
            'pages.admin.organizations.show',
            compact('organization')
        );
    }

    public function approve(Organization $organization): RedirectResponse
    {
        $organization->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        return redirect()
            ->route('admin.organizations.show', $organization)
            ->with('success', 'تمت الموافقة على المنظمة بنجاح.');
    }

    public function reject(Organization $organization): RedirectResponse
    {
        $organization->update([
            'status' => 'rejected',
            'approved_at' => null,
            'approved_by' => null,
        ]);

        return redirect()
            ->route('admin.organizations.show', $organization)
            ->with('success', 'تم رفض المنظمة.');
    }
}