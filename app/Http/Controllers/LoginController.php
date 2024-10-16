<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Account;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        // Query the user by 'Username' column
        $user = Account::where('Username', $credentials['username'])->first();

        // Check if the user exists and use Laravel's Hash::check for password verification
        if ($user && Hash::check($credentials['password'], $user->Password)) {
            // Store necessary user info in session
            Session::put('user_id', $user->User_ID);
            Session::put('username', $user->Username);
            Session::put('name', $user->Name);
            Session::put('position', $user->Position);
            Session::put('is_supervisor', $user->Position === 'Supervisor');

            // Redirect based on user role
            if ($user->Position === 'Supervisor') {
                return redirect()->route('dashboard')->with('success', 'Welcome, ' . $user->Name . '! You have logged in successfully.');
            } elseif ($user->Position === 'Office Staff' || $user->Position === 'Technician') {
                return redirect()->route('staffdashboard')->with('success', 'Welcome, ' . $user->Name . '! You have logged in successfully.');
            }

        }

        // If login fails, redirect with an error message
        return redirect()->route('Login')->with('error', 'Invalid credentials, Please try again!');
    }

    public function logout()
    {
        // Clear all session data related to the user
        Session::flush();
        return redirect()->route('Login')->with('success', 'You have logged out successfully!');
    }
}
