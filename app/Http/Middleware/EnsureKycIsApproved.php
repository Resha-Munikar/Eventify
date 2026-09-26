<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureKycIsApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login.form');
        }

        $user = Auth::user();

        // Only vendor accounts require KYC
        if ($user->role === 'vendor' && !$user->isKycApproved()) {
            $kycStatus = $user->kycStatus();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Approved KYC verification is required to create events or venues.',
                    'kyc_status' => $kycStatus,
                    'redirect' => route('vendor.kyc.index'),
                ], 403);
            }

            $message = match ($kycStatus) {
                'pending' => 'Your KYC verification is currently under review by our team. You will be able to create events and venues once approved.',
                'rejected' => 'Your KYC submission was rejected. Please review the admin feedback and resubmit your documents.',
                default => 'Please complete and submit your KYC verification before creating events or venues.',
            };

            return redirect()
                ->route('vendor.kyc.index')
                ->with('warning', $message);
        }

        return $next($request);
    }
}
