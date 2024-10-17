<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        $user = User::where('username', $credentials['username'])->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            $this->showAlert('error', 'Error!', 'Username or password is incorrect. Please try again.');
            return back();
        }
        Session::put('user_id', $user->user_id);
        Session::put('username', $user->username);
        Session::put('fullname', $user->fullname);
        Session::put('job_title', $user->job_title);

        if ($user->job_title === 'supervisor') {
            return redirect()->route('dashboard')->with('success', 'Welcome, ' . $user->fullname . '! You have logged in successfully.');
        } elseif ($user->job_title === 'officeStaff' || $user->job_title === 'technician') {
            return redirect()->route('staffdashboard')->with('success', 'Welcome, ' . $user->fullname . '! You have logged in successfully.');
        }
        return redirect()->route('Login')->with('error', 'Invalid credentials, Please try again!');
    }


    public function logout()
    {
        Session::flush();
        return redirect()->route('Login')->with('success', 'You have logged out successfully!');
    }
    public function register()
    {
        return view('auth/register');
    }

    public function registerSave(Request $request)
    {
        $request->validate([
            'fullname' => ['required', function ($attribute, $value, $fail) {
                if (User::where('fullname', $value)->exists()) {
                    $fail('The fullname has already been registered.');
                }
            }],
            'username' => ['required', 'unique:users,username'],
            'job_title' => ['required', 'string'],
            'phone_number' => ['required', 'digits:10'],
            'password' => ['required', 'min:8'],
        ]);

        try {
            User::create([
                'fullname' => $request->fullname,
                'username' => $request->username,
                'job_title' => $request->job_title,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
            ]);
            
            $this->showAlert('success', 'Registration successful', 'Please log in.');
            return redirect()->back();
        } catch (\Exception $e) {
            $this->showAlert('error', 'Registration failed', 'Please try again.');
            return redirect()->back();
        }
    }
    public static function showAlert($icon, $title, $text) {
        Session::flash('alertShow', true);
        Session::flash('icon', $icon);
        Session::flash('title', $title);
        Session::flash('text', $text);
    }
}
