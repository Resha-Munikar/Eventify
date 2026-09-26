@extends('layouts.app')

@section('title', 'Vendor Dashboard')
@php 
    $noNavbar = true; 
    $noFooter = true; 
@endphp

@section('content')
@include('vendor.sidebar')

<div class="ml-0 sm:ml-64 p-4 sm:p-8 min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <div class="max-w-6xl mx-auto space-y-6">

        <!-- KYC Status Banner -->
        @php
            $kyc = $vendor->kyc;
        @endphp

        @if(!$kyc || $kyc->isNotSubmitted())
            <div class="p-4 sm:p-5 rounded-2xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 text-amber-900 dark:text-amber-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
                <div class="flex items-start sm:items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/60 text-amber-700 dark:text-amber-300 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm sm:text-base">KYC Verification Required</h3>
                        <p class="text-xs sm:text-sm text-amber-700 dark:text-amber-300/90 mt-0.5">
                            Please complete and submit your KYC verification to unlock event creation and venue publishing on Eventify.
                        </p>
                    </div>
                </div>
                <a href="{{ route('vendor.kyc.index') }}" class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-semibold text-xs sm:text-sm whitespace-nowrap shadow-sm transition">
                    Verify KYC Now &rarr;
                </a>
            </div>
        @elseif($kyc->isPending())
            <div class="p-4 sm:p-5 rounded-2xl bg-blue-50 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-800 text-blue-900 dark:text-blue-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
                <div class="flex items-start sm:items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-[#8d85ec] text-white flex items-center justify-center flex-shrink-0 shadow-sm">
                        <svg class="w-5 h-5 animate-spin-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-sm sm:text-base">KYC Under Review</h3>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-200 text-blue-800 dark:bg-blue-800 dark:text-blue-200 uppercase">Pending</span>
                        </div>
                        <p class="text-xs sm:text-sm text-blue-700 dark:text-blue-300/90 mt-0.5">
                            Your KYC verification is currently under review by our team. You will be notified once reviewed.
                        </p>
                    </div>
                </div>
                <a href="{{ route('vendor.kyc.index') }}" class="px-4 py-2 rounded-xl bg-white dark:bg-gray-800 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-700 font-semibold text-xs whitespace-nowrap hover:bg-blue-50 transition">
                    View Submission &rarr;
                </a>
            </div>
        @elseif($kyc->isRejected())
            <div class="p-4 sm:p-5 rounded-2xl bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800 text-rose-900 dark:text-rose-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
                <div class="flex items-start sm:items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-rose-500 text-white flex items-center justify-center flex-shrink-0 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-sm sm:text-base">KYC Verification Rejected</h3>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-200 text-rose-800 dark:bg-rose-800 dark:text-rose-200 uppercase">Action Needed</span>
                        </div>
                        <p class="text-xs sm:text-sm text-rose-700 dark:text-rose-300/90 mt-0.5 line-clamp-1">
                            Feedback: {{ $kyc->rejection_reason }}
                        </p>
                    </div>
                </div>
                <a href="{{ route('vendor.kyc.resubmit') }}" class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs sm:text-sm whitespace-nowrap shadow-sm transition">
                    Resubmit KYC &rarr;
                </a>
            </div>
        @elseif($kyc->isApproved())
            <div class="p-4 rounded-2xl bg-green-50/70 dark:bg-green-950/20 border border-green-200/60 dark:border-green-800/40 text-green-900 dark:text-green-200 flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-green-600 text-white flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-xs sm:text-sm font-bold text-green-800 dark:text-green-300">Verified Organizer Account</span>
                        <span class="text-xs text-green-600 dark:text-green-400 block sm:inline sm:ml-2">({{ $kyc->business_name }})</span>
                    </div>
                </div>
                <span class="text-[11px] font-semibold text-green-700 dark:text-green-400 bg-green-100 dark:bg-green-900/40 px-2.5 py-1 rounded-full">
                    KYC Verified
                </span>
            </div>
        @endif

        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-[#8D85EC] to-[#766ee6] rounded-3xl p-6 sm:p-8 text-white shadow-lg relative overflow-hidden">
            <div class="relative z-10">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/20 backdrop-blur-xs text-white mb-3">
                    Organizer Portal
                </span>
                <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight">
                    Welcome back, {{ $vendor->name }}!
                </h1>
                <p class="mt-2 text-white/90 text-sm sm:text-base max-w-xl">
                    Manage your event listings, venue bookings, inquiries, and track your ticket revenue in real time.
                </p>
            </div>
            <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        </div>

        <!-- Quick Actions & Links Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- 1. Events -->
            <a href="{{ route('vendor.events.index') }}" class="p-5 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200/80 dark:border-gray-700 shadow-sm hover:shadow-md hover:border-[#8d85ec] dark:hover:border-[#8d85ec] transition group">
                <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-900/40 text-[#8d85ec] flex items-center justify-center mb-4 group-hover:scale-105 transition">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M7 10h5v5H7zM3 5h18v2H3zm0 4h18v13H3z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 dark:text-white text-base">My Events</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Manage ticket tiers, listings & details.</p>
            </a>

            <!-- 2. Venues -->
            <a href="{{ route('vendor.venues.index') }}" class="p-5 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200/80 dark:border-gray-700 shadow-sm hover:shadow-md hover:border-[#8d85ec] dark:hover:border-[#8d85ec] transition group">
                <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center mb-4 group-hover:scale-105 transition">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M3 21V3h18v18H3zm2-16v14h14V5H5zm2 2h2v2H7V7zm0 4h2v2H7v-2zm0 4h2v2H7v-2zm4-8h2v2h-2V7zm0 4h2v2h-2v-2zm0 4h2v2h-2v-2zm4-8h2v2h-2V7zm0 4h2v2h-2v-2zm0 4h2v2h-2v-2z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 dark:text-white text-base">My Venues</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Manage venue spaces, pricing & amenities.</p>
            </a>

            <!-- 3. Inquiries -->
            <a href="{{ route('vendor.inquiries.index') }}" class="p-5 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200/80 dark:border-gray-700 shadow-sm hover:shadow-md hover:border-[#8d85ec] dark:hover:border-[#8d85ec] transition group">
                <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 flex items-center justify-center mb-4 group-hover:scale-105 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 dark:text-white text-base">Customer Inquiries</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Respond to booking requests & messages.</p>
            </a>

            <!-- 4. KYC Status -->
            <a href="{{ route('vendor.kyc.index') }}" class="p-5 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200/80 dark:border-gray-700 shadow-sm hover:shadow-md hover:border-[#8d85ec] dark:hover:border-[#8d85ec] transition group">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-4 group-hover:scale-105 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 dark:text-white text-base">KYC Verification</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    @if(!$kyc || $kyc->isNotSubmitted())
                        <span class="text-amber-600 font-semibold">Action Required</span>
                    @elseif($kyc->isPending())
                        <span class="text-blue-600 font-semibold">Under Review</span>
                    @elseif($kyc->isApproved())
                        <span class="text-green-600 font-semibold">Verified</span>
                    @elseif($kyc->isRejected())
                        <span class="text-rose-600 font-semibold">Needs Attention</span>
                    @endif
                </p>
            </a>

        </div>

    </div>
</div>
@endsection
