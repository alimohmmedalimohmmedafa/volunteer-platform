<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
            'pages.employee.organizations.index',
            compact('organizations', 'status')
        );
    }

    public function show(Organization $organization)
    {
        $organization->load('user', 'approver');

        return view(
            'pages.employee.organizations.show',
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
            ->route('employee.organizations.show', $organization)
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
            ->route('employee.organizations.show', $organization)
            ->with('success', 'تم رفض المنظمة.');
    }
}