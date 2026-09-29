<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Mail\VendorResetPasswordMail;


class VendorForgotPasswordController extends Controller
{
    // Show forgot password form
    public function showForgotForm()
    {
        return view('vendor.auth.forgot-password');
    }

    // Handle form submission
    public function sendResetLink(Request $request)
{
    // Validate email
    $request->validate(['email' => 'required|email|exists:users,email']);

    // Generate token
    $token = Str::random(64);

    // Store or update token in password_resets table
    DB::table('password_resets')->updateOrInsert(
        ['email' => $request->email],
        [
            'token' => $token,
            'created_at' => Carbon::now()
        ]
    );

    try {
        // Create reset link
        $resetLink = url('/vendor/reset-password/' . $token . '?email=' . urlencode($request->email));

        $user = User::where('email', $request->email)->first();
        $userName = $user ? $user->name : 'User';

        // Send email with styled template
        Mail::to($request->email)->send(new VendorResetPasswordMail($token, $request->email, $userName));

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'Password reset link sent to your email.'
            ]);
        }

        return back()->with('status', 'Password reset link sent to your email.');

    } catch (\Exception $e) {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'error' => 'Failed to send email. Please try again later.'
            ], 500);
        }

        return back()->withErrors(['email' => 'Failed to send email. Please try again later.']);
    }
}


    // Show reset password form
public function reset(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
        'password' => 'required|min:6|confirmed',
        'token' => 'required'
    ]);

    $reset = DB::table('password_resets')
        ->where('email', $request->email)
        ->where('token', $request->token)
        ->first();

    if (!$reset) {
        return back()->withErrors(['email' => 'Invalid token or email']);
    }

    $user = User::where('email', $request->email)->first();
    $user->password = bcrypt($request->password);
    $user->save();

    DB::table('password_resets')->where('email', $request->email)->delete();

    return redirect()->route('login')->with('status', 'Password reset successfully!');

}


    public function checkCurrentPassword(Request $request)
    {
        $user = auth()->user(); // or get vendor by email

        if (!$user) {
            return response()->json(['valid' => false]);
        }

        $isValid = Hash::check($request->current_password, $user->password);

        return response()->json(['valid' => $isValid]);
    }
    // Show reset password form
public function showResetForm(Request $request, $token)
{
    $email = $request->query('email'); // Get email from query string
    return view('vendor.profile.reset-password', compact('token', 'email'));
}

}
