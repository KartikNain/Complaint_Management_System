<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $validated = $request->validate([

            'name' => 'required|string|max:255',

            'email' => 'required|email|unique:users,email',

            'password' => ['required','confirmed',Password::min(8)
            ],

        ]);

        User::create([

            'name' => $validated['name'],

            'email' => $validated['email'],

            'password' => Hash::make($validated['password']),

            'role' => 'employee', // Set the role to 'employee' for registered employees

        ]);

        return response()->json([

            'status' => true,

            'message' => 'Employee registered successfully.',

        ]);

    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ])) {

            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role == 'admin') {

                $redirect = route('admin.dashboard');

            } elseif ($user->role == 'staff') {

                $redirect = route('staff.dashboard');

            } else {

                $redirect = route('employee.dashboard');

            }
            // dd($redirect);

            return response()->json([
                'status'   => true,
                'message'  => 'Login successful.',
                'redirect' => $redirect
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid email or password.'
        ], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('auth.login.page');
    }    


}
