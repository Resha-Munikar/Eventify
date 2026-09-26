@extends('layouts.app')

@section('title', 'KYC Submitted Successfully - Eventify')
@php 
    $noNavbar = true; 
    $noFooter = true; 
@endphp

@section('content')
@include('vendor.sidebar')

<div class="ml-0 sm:ml-64 p-4 sm:p-8 min-h-screen bg-gray-50 dark:bg-gray-900 flex items-center justify-center transition-colors duration-200">
    <div class="max-w-xl w-full mx-auto bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 p-8 sm:p-10 text-center space-y-6">
        
        <!-- Animated / Success Icon -->
        <div class="w-20 h-20 rounded-3xl bg-gradient-to-tr from-green-500 to-emerald-400 text-white flex items-center justify-center mx-auto shadow-lg shadow-green-500/30">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
        </div>

        <!-- Headings & Text -->
        <div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300 mb-3">
                <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                Status: Pending Verification
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white">
                KYC Documents Submitted Successfully
            </h1>
            <p class="mt-3 text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                Your updated documents have been submitted for verification. Our verification team will review your submission and update your KYC status.
            </p>
        </div>

        <!-- Application Summary Card -->
        @if($kyc)
            <div class="bg-gray-50 dark:bg-gray-700/40 p-4 rounded-2xl border border-gray-200 dark:border-gray-700 text-left text-xs space-y-2">
                <div class="flex justify-between items-center py-1 border-b border-gray-100 dark:border-gray-700">
                    <span class="text-gray-500 dark:text-gray-400 font-medium">Business / Organization:</span>
                    <span class="font-bold text-gray-800 dark:text-white">{{ $kyc->business_name }}</span>
                </div>
                <div class="flex justify-between items-center py-1 border-b border-gray-100 dark:border-gray-700">
                    <span class="text-gray-500 dark:text-gray-400 font-medium">Document Type:</span>
                    <span class="font-bold text-gray-800 dark:text-white">{{ $kyc->document_type }}</span>
                </div>
                <div class="flex justify-between items-center py-1">
                    <span class="text-gray-500 dark:text-gray-400 font-medium">Resubmitted At:</span>
                    <span class="font-bold text-gray-800 dark:text-white">{{ optional($kyc->updated_at)->format('M d, Y h:i A') ?? date('M d, Y') }}</span>
                </div>
            </div>
        @endif

        <!-- Action CTA Buttons -->
        <div class="flex flex-col sm:flex-row gap-3 justify-center pt-2">
            <a href="{{ route('vendor.dashboard') }}" 
               class="px-6 py-3 rounded-xl bg-[#8d85ec] hover:bg-[#7b76e4] text-white font-bold text-sm shadow-md hover:shadow-lg transition transform active:scale-98">
                Go to Vendor Dashboard
            </a>
            <a href="{{ route('vendor.kyc.index') }}" 
               class="px-5 py-3 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-semibold text-sm transition">
                View Submission Status
            </a>
        </div>

    </div>
</div>
@endsection
