<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // @desc   Show user profile
    // @route  PUT /profile
    public function update(Request $request): RedirectResponse
    {
        // Get logged in user
        $user = Auth::user();

        // Validate form data
        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100',
            'avatar' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ]);

        // Get user name and email
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        // Check if avatar is uploaded
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar) {
                Storage::delete('public/' . $user->avatar);
            }

            // Store new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        // Update user profile
        $user->save();

        return redirect()->route('dashboard')->with('success', 'Profile updated successfully');
    }

}