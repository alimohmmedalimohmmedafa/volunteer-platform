<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrganizationProfileController extends Controller
{
    public function show()
    {
        $organization = auth()->user()->organization;

        return view('pages.organization.profile.show', compact('organization'));
    }

    public function edit()
    {
        $organization = auth()->user()->organization;

        return view('pages.organization.profile.edit', compact('organization'));
    }

    public function update(Request $request)
    {
        $organization = auth()->user()->organization;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'website' => ['nullable', 'url', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
        ]);

        if ($request->hasFile('logo')) {

            if ($organization->logo) {
                Storage::disk('public')->delete($organization->logo);
            }

            $validated['logo'] = $request->file('logo')
                ->store('organizations/logos', 'public');
        }

        $organization->update($validated);

        return redirect()
            ->route('organization.profile')
            ->with('success', 'تم تحديث بيانات المنظمة بنجاح.');
    }
}