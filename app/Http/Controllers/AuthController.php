<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\EmailOtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected EmailOtpService $otpService;

    public function __construct(EmailOtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,admin,vendor',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Keep auth and session state for immediate OTP verification
        Auth::login($user);
        $request->session()->put('verify_user_id', $user->id);

        // Generate and dispatch 6-digit OTP to user email
        $otpResult = $this->otpService->generateAndSend($user);

        $flashMessage = $otpResult['success']
            ? 'Registration successful! A 6-digit verification code has been sent to ' . $user->email . '.'
            : 'Account created! ' . $otpResult['message'];

        return redirect()
            ->route('verification.notice')
            ->with('success', $flashMessage);
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credential = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credential)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Intercept unverified users and redirect to OTP verification
            if ($user->email_verified_at === null) {
                $request->session()->put('verify_user_id', $user->id);

                if ($this->otpService->canResend($user)) {
                    $this->otpService->generateAndSend($user);
                }

                return redirect()
                    ->route('verification.notice')
                    ->with('warning', 'Please verify your email address to complete sign in.');
            }

            if ($user->role === 'admin') {
                return redirect()->route('chirps.adminIndex');
            } elseif ($user->role === 'vendor') {
                return redirect()->route('vendor.dashboard');
            }

            return redirect()->route('welcome');
        } else {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login.form');
    }
}
