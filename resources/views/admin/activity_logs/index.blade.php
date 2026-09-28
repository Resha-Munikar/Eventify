@extends('layouts.app')

@section('title', 'Admin - Activity Log')
@php 
$noNavbar = true;
$noFooter = true; 
@endphp

@section('content')
@include('admin.sidebar') 

<div class="ml-0 sm:ml-64 p-3 sm:p-6 min-h-screen">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-100 dark:border-gray-700">
        <!-- Page Title & Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-5">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-[#8d85ec]">Activity Log</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Platform-wide activity log of Users, Vendors, and Admins across Eventify.</p>
            </div>
            <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">
                Total Logs: <span class="font-bold text-[#8d85ec]">{{ $activityLogs->total() }}</span>
            </div>
        </div>

        <!-- Filter / Search Toolbar -->
        <form method="GET" action="{{ route('admin.activityLogs.index') }}" 
              x-data="{ dateOption: '{{ request('date_filter', (request('from_date') || request('to_date')) ? 'custom' : 'all') }}' }"
              class="mb-5 p-3.5 sm:p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl space-y-3">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <!-- 1. Search (User name or description) -->
                <div>
                    <label for="search" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Search</label>
                    <div class="relative">
                        <input type="text" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Search user or activity..." 
                               class="block w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2 text-xs dark:bg-gray-800 dark:text-white pl-8 focus:ring-[#8d85ec] focus:border-[#8d85ec]">
                        <svg class="w-4 h-4 text-gray-400 absolute left-2.5 top-2.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- 2. Role Filter -->
                <div>
                    <label for="role" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Role</label>
                    <select id="role" 
                            name="role" 
                            class="block w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2 text-xs dark:bg-gray-800 dark:text-white focus:ring-[#8d85ec] focus:border-[#8d85ec]">
                        <option value="">All Roles</option>
                        <option value="user" {{ strtolower(request('role')) === 'user' ? 'selected' : '' }}>User</option>
                        <option value="vendor" {{ strtolower(request('role')) === 'vendor' ? 'selected' : '' }}>Vendor</option>
                        <option value="admin" {{ strtolower(request('role')) === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>

                <!-- 3. Activity Filter -->
                <div>
                    <label for="action" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Activity</label>
                    <select id="action" 
                            name="action" 
                            class="block w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2 text-xs dark:bg-gray-800 dark:text-white focus:ring-[#8d85ec] focus:border-[#8d85ec]">
                        <option value="">All Activities</option>
                        @foreach($availableActions as $key => $label)
                            <option value="{{ $key }}" {{ request('action') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- 4. Date Filter Preset -->
                <div>
                    <label for="date_filter" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Date</label>
                    <select id="date_filter" 
                            name="date_filter" 
                            x-model="dateOption"
                            class="block w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2 text-xs dark:bg-gray-800 dark:text-white focus:ring-[#8d85ec] focus:border-[#8d85ec]">
                        <option value="all">All Time</option>
                        <option value="today">Today</option>
                        <option value="yesterday">Yesterday</option>
                        <option value="last_7_days">Last 7 Days</option>
                        <option value="last_30_days">Last 30 Days</option>
                        <option value="custom">Custom Date Range</option>
                    </select>
                </div>
            </div>

            <!-- Custom Date Range Row (Conditional with Alpine.js) -->
            <div x-show="dateOption === 'custom'" x-cloak class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 border-t border-gray-200 dark:border-gray-600">
                <div>
                    <label for="from_date" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">From Date</label>
                    <input type="date" 
                           id="from_date" 
                           name="from_date" 
                           value="{{ request('from_date') }}" 
                           class="block w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2 text-xs dark:bg-gray-800 dark:text-white">
                </div>
                <div>
                    <label for="to_date" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">To Date</label>
                    <input type="date" 
                           id="to_date" 
                           name="to_date" 
                           value="{{ request('to_date') }}" 
                           class="block w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2 text-xs dark:bg-gray-800 dark:text-white">
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2 justify-end pt-1">
                <button type="submit" 
                        class="bg-[#8D85EC] hover:bg-[#7b76e4] text-white px-4 py-1.5 rounded-lg text-xs font-semibold transition shadow-sm inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter
                </button>
                <a href="{{ route('admin.activityLogs.index') }}" 
                   class="px-3.5 py-1.5 bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 rounded-lg text-xs font-semibold transition">
                    Reset
                </a>
            </div>
        </form>

        <!-- Activity Table -->
        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 mb-5">
            <table class="w-full text-xs sm:text-sm text-left border-collapse">
                <thead class="bg-[#8D85EC] text-white text-[11px] sm:text-xs uppercase tracking-wider">
                    <tr>
                        <th scope="col" class="px-3.5 py-3">User</th>
                        <th scope="col" class="px-3.5 py-3 text-center">Role</th>
                        <th scope="col" class="px-3.5 py-3">Activity</th>
                        <th scope="col" class="px-3.5 py-3">Date / Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-gray-800 dark:text-gray-200">
                    @forelse($activityLogs as $log)
                        @php
                            $user = $log->user;
                            $userRole = strtolower($user->role ?? 'user');
                        @endphp
                        <tr class="hover:bg-purple-50/50 dark:hover:bg-gray-700/50 transition">
                            <!-- User Column -->
                            <td class="px-3.5 py-3 whitespace-nowrap">
                                <div class="font-semibold text-gray-900 dark:text-white">
                                    {{ $user->name ?? 'User #' . $log->user_id }}
                                </div>
                                @if(!empty($user->email))
                                    <div class="text-[11px] text-gray-500 dark:text-gray-400">
                                        {{ $user->email }}
                                    </div>
                                @endif
                            </td>

                            <!-- Role Column -->
                            <td class="px-3.5 py-3 text-center whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                    @if($userRole === 'admin') bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300
                                    @elseif($userRole === 'vendor') bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300
                                    @else bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 @endif">
                                    {{ ucfirst($user->role ?? 'User') }}
                                </span>
                            </td>

                            <!-- Activity Column -->
                            <td class="px-3.5 py-3">
                                <div class="font-medium text-gray-900 dark:text-gray-100">
                                    {{ $log->description }}
                                </div>
                                <div class="text-[10px] text-gray-400 dark:text-gray-500 font-mono">
                                    action: {{ $log->action }}
                                </div>
                            </td>

                            <!-- Date/Time Column -->
                            <td class="px-3.5 py-3 whitespace-nowrap">
                                <div class="font-medium text-gray-900 dark:text-gray-200">
                                    {{ \Carbon\Carbon::parse($log->created_at)->format('M d, g:i A') }}
                                </div>
                                <div class="text-[11px] text-gray-400 dark:text-gray-500">
                                    {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400 font-medium">
                                No activities found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($activityLogs->hasPages())
            <div class="mt-4">
                {{ $activityLogs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
