<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Services\ActivityLogger;

use Illuminate\Support\Str;

class ProfileController extends Controller
{
    // Show profile
    public function show()
    {
        $user = Auth::user();
        return view('vendor.profile.show', compact('user'));
    }

    // Update profile
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        // Update name & email
        $user->name = $request->name;
        $user->email = $request->email;

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo && file_exists(public_path('uploads/profile_photos/' . $user->profile_photo))) {
                @unlink(public_path('uploads/profile_photos/' . $user->profile_photo));
            }
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            // Store new image in public/uploads/profile_photos
            $file = $request->file('profile_photo');
            $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profile_photos'), $filename);
            $user->profile_photo = $filename;
        }

        $user->save();

        ActivityLogger::log('profile_updated', 'Updated vendor profile details', $user, $user);

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // Validate input
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Check if current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password does not match.']);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        ActivityLogger::log('password_changed', 'Changed account password', $user, $user);

        return back()->with('success', 'Password changed successfully.');
    }
    public function checkCurrentPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
        ]);

        $user = Auth::user();

        $isValid = Hash::check($request->current_password, $user->password);

        return response()->json(['valid' => $isValid]);
    }

}
