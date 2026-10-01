<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function profileUpdate(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . $user->id,
            'profileImage' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user->name  = $validated['name'];
        $user->email = $validated['email'];

        if ($request->hasFile('profileImage')) {

            // Delete old profile image
            if ($user->profileImage && file_exists(public_path($user->profileImage))) {
                unlink(public_path($user->profileImage));
            }

            // Store new profile image inside public/img/profileImage
            $file = $request->file('profileImage');

            $fileName = time() . '_' . $file->getClientOriginalName();

            $file->move(
                public_path('img/profileImage'),
                $fileName
            );

            // Store relative path in database
            $user->profileImage = 'img/profileImage/' . $fileName;
        }

        $user->save();

        return response()->json([
            'status'            => true,
            'message'           => 'Profile updated successfully.',
            'name'              => $user->name,
            'profile_image_url' => $user->profileImage ? asset('storage/' . $user->profileImage) : null,
        ]);
    }



    // Update Password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => [
                'required',
                'confirmed',
                'different:current_password',
                Password::min(8),
            ],
        ]);

        $user = Auth::user();

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'status' => false,
                'errors' => [
                    'current_password' => [
                        'The current password is incorrect.'
                    ]
                ]
            ], 422);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Password updated successfully.'
        ]);
    }
}
