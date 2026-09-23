<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    /**
     * Display approved organizations.
     */
    public function index(Request $request)
    {
        $query = Organization::query()
            ->where('status', 'approved');

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('bio', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $organizations = $query
            ->withCount([
                'jobs' => function ($query) {
                    $query->where('status', 'published');
                }
            ])
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('pages.organizations.index', compact('organizations'));
    }

    /**
     * Display an approved organization.
     */
    public function show(Organization $organization)
    {
        // Only approved organizations are publicly accessible.
        abort_unless($organization->status === 'approved', 404);

        $organization->load([
            'jobs' => function ($query) {
                $query->where('status', 'published')
                    ->latest();
            }
        ]);

        return view('pages.organizations.show', compact('organization'));
    }
}