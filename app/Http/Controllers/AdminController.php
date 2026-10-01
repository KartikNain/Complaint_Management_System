<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    public function adminStoreUser(Request $request)
    {
        $validated = $request->validate([

            'name' => ['required', 'string','max:255'],

            'email' => ['required', 'email','unique:users,email'],

            'role' => ['required','in:employee,staff'],

            'password' => ['required', 'confirmed', Password::min(8)],

        ]);

        User::create([

            'name' => $validated['name'],

            'email' => $validated['email'],

            'password' => Hash::make($validated['password']),

            'role' => $validated['role'],

            'profileImage' => $validated['profileImage'] ?? null,

        ]);

        return response()->json([

            'status' => true,

            'message' => ucfirst($validated['role']) . ' user created successfully.',

        ]);
    }
}
