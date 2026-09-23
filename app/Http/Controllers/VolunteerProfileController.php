<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VolunteerProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        $profile = $user->volunteerProfile;

        return view('pages.volunteer.profile.show', compact(
            'user',
            'profile'
        ));
    }

    public function edit()
    {
        $user = Auth::user();

        $profile = $user->volunteerProfile;

        return view('pages.volunteer.profile.edit', compact(
            'user',
            'profile'
        ));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'skills' => ['nullable', 'string', 'max:2000'],

            'photo' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:2048',
            ],

            'cv' => [
                'nullable',
                'file',
                'mimes:pdf',
                'max:5120',
            ],
        ]);

        $user->update([
            'name' => $validated['name'],
        ]);

        $profile = $user->volunteerProfile;

        if (!$profile) {
            $profile = $user->volunteerProfile()->create([]);
        }

        $profile->update([
            'phone' => $validated['phone'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'city' => $validated['city'] ?? null,
            'country' => $validated['country'] ?? null,
            'skills' => $validated['skills'] ?? null,
        ]);

        /*
         * Photo
         */
        if ($request->hasFile('photo')) {

            if ($profile->photo) {
                Storage::disk('public')->delete($profile->photo);
            }

            $photoPath = $request->file('photo')
                ->store('volunteers/photos', 'public');

            $profile->update([
                'photo' => $photoPath,
            ]);
        }

        /*
         * CV
         */
        if ($request->hasFile('cv')) {

            if ($profile->cv) {

                $cvUsedByApplications = \App\Models\Application::query()
                    ->where('cv', $profile->cv)
                    ->exists();

                if (!$cvUsedByApplications) {
                    Storage::disk('local')->delete($profile->cv);
                }
            }

            $cvPath = $request->file('cv')
                ->store('volunteers/cvs', 'local');

            $profile->update([
                'cv' => $cvPath,
            ]);
        }

        return redirect()
            ->route('volunteer.profile')
            ->with('success', 'تم تحديث الملف الشخصي بنجاح.');
    }
}