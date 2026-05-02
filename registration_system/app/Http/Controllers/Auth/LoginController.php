<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Allow admin/teacher accounts to login without an application record
        $existingUser = \App\Models\User::where('email', $credentials['email'])->first();
        if ($existingUser && in_array($existingUser->role, ['admin', 'teacher'])) {
            // Skip application check for admin and teacher accounts
        } else {
            // For students: verify email matches a submitted and approved application
            $application = \App\Models\Application::where('email', $credentials['email'])->first();

            if ($application && $application->status === 'Pending') {
                return back()->withErrors([
                    'email' => 'Your application is still pending review. Access will be granted once an administrator approves it.',
                ])->onlyInput('email');
            }

            if (!$application && !$existingUser) {
                return back()->withErrors([
                    'email' => 'No admission record found for this email. Please submit an application first.',
                ])->onlyInput('email');
            }
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();

            // Check if user must change password first
            if ($user->must_change_password) {
                return redirect()->route('password.force_change');
            }

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'teacher') {
                return redirect()->route('teacher.dashboard');
            }
            
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showForcePasswordChange()
    {
        if (!Auth::user()->must_change_password) {
            return redirect('/dashboard');
        }

        return view('auth.force_password_change');
    }

    public function forcePasswordChange(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        if (Hash::check($request->new_password, $user->password)) {
            return back()->withErrors(['new_password' => 'Your new password cannot be the same as the current one.']);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
            'must_change_password' => false,
        ]);

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Password changed successfully! Welcome.');
        } elseif ($user->role === 'teacher') {
            return redirect()->route('teacher.dashboard')->with('success', 'Password changed successfully! Welcome.');
        }

        return redirect()->route('dashboard')->with('success', 'Password changed successfully! Welcome.');
    }

    public function skipForcePasswordChange(Request $request)
    {
        $user = Auth::user();
        $user->update(['must_change_password' => false]);

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'teacher') {
            return redirect()->route('teacher.dashboard');
        }

        return redirect()->route('dashboard');
    }

    public function apiLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $existingUser = \App\Models\User::where('email', $credentials['email'])->first();

        if (!$existingUser || !in_array($existingUser->role, ['admin', 'teacher'])) {
            $application = \App\Models\Application::where('email', $credentials['email'])->first();

            if ($application && $application->status === 'Pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Your application is still pending review. Access will be granted once an administrator approves it.',
                ], 403);
            }

            if (!$application && !$existingUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'No account found for this email. Please submit an application or contact the administrator.',
                ], 403);
            }
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            return response()->json([
                'success' => true,
                'role' => $user->role,
                'name' => $user->name,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid email or password.',
        ], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(env('FRONTEND_URL', 'http://localhost:3000'));
    }
}