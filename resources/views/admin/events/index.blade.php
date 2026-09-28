@extends('layouts.app')

@section('title', 'Admin - Event Management')
@php 
$noNavbar = true;
$noFooter = true; 
@endphp

@section('content')
@include('admin.sidebar') 

<div class="ml-0 sm:ml-64 p-3 sm:p-6 min-h-screen">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-100 dark:border-gray-700">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-5">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-[#8d85ec]">Event Management</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Manage and view all events created by vendors</p>
            </div>
            <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">
                Total Events: <span class="font-bold text-[#8d85ec]">{{ $events->total() }}</span>
            </div>
        </div>

        <!-- Filter / Search Toolbar -->
        <form method="GET" action="{{ route('admin.events.index') }}" 
              class="mb-5 p-3.5 sm:p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
            
            <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 gap-3 items-end">
                <!-- 1. Search (Event Name or Vendor Name) -->
                <div class="sm:col-span-2 lg:col-span-2">
                    <label for="search" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Search Events</label>
                    <div class="relative">
                        <input type="text" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Search by event name or vendor..." 
                               class="block w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2 text-xs dark:bg-gray-800 dark:text-white pl-8 focus:ring-[#8d85ec] focus:border-[#8d85ec]">
                        <svg class="w-4 h-4 text-gray-400 absolute left-2.5 top-2.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- 2. Category Filter -->
                <div>
                    <label for="category" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Category</label>
                    <select id="category" 
                            name="category" 
                            class="block w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2 text-xs dark:bg-gray-800 dark:text-white focus:ring-[#8d85ec] focus:border-[#8d85ec]">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>{{ $category }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- 3. Status Filter -->
                <div>
                    <label for="status" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Status</label>
                    <select id="status" 
                            name="status" 
                            class="block w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2 text-xs dark:bg-gray-800 dark:text-white focus:ring-[#8d85ec] focus:border-[#8d85ec]">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active / Upcoming</option>
                        <option value="past" {{ request('status') === 'past' ? 'selected' : '' }}>Past / Completed</option>
                        <option value="sold_out" {{ request('status') === 'sold_out' ? 'selected' : '' }}>Sold Out</option>
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2 justify-end mt-3 pt-2 border-t border-gray-200 dark:border-gray-600">
                <button type="submit" 
                        class="bg-[#8D85EC] hover:bg-[#7b76e4] text-white px-4 py-1.5 rounded-lg text-xs font-semibold transition shadow-sm inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter
                </button>
                <a href="{{ route('admin.events.index') }}" 
                   class="px-3.5 py-1.5 bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 rounded-lg text-xs font-semibold transition">
                    Reset
                </a>
            </div>
        </form>

        <!-- Events Table -->
        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 mb-5">
            <table class="w-full text-xs sm:text-sm text-left border-collapse">
                <thead class="bg-[#8D85EC] text-white text-[11px] sm:text-xs uppercase tracking-wider">
                    <tr>
                        <th scope="col" class="px-3.5 py-3">Event</th>
                        <th scope="col" class="px-3.5 py-3">Vendor</th>
                        <th scope="col" class="px-3 py-3 text-center">Category</th>
                        <th scope="col" class="px-3.5 py-3">Date</th>
                        <th scope="col" class="px-3 py-3 text-center">Status</th>
                        <th scope="col" class="px-3.5 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-gray-800 dark:text-gray-200">
                    @forelse($events as $event)
                        @php
                            $isPast = \Carbon\Carbon::parse($event->event_date)->isPast();
                            $isSoldOut = (int)$event->available_seats <= 0;
                        @endphp
                        <tr class="hover:bg-purple-50/50 dark:hover:bg-gray-700/50 transition">
                            <!-- Event Column -->
                            <td class="px-3.5 py-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-10 h-10 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700 flex-shrink-0 border border-gray-200 dark:border-gray-600">
                                        @if($event->image && file_exists(public_path('uploads/' . $event->image)))
                                            <img src="{{ asset('uploads/' . $event->image) }}" alt="{{ $event->event_name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <a href="{{ route('admin.events.show', $event->id) }}" class="font-bold text-gray-900 dark:text-white hover:text-[#8d85ec] dark:hover:text-[#8d85ec] transition truncate block max-w-[180px] sm:max-w-[240px]">
                                            {{ $event->event_name }}
                                        </a>
                                        <div class="text-[11px] text-gray-500 dark:text-gray-400 flex items-center gap-1 mt-0.5 truncate max-w-[180px] sm:max-w-[240px]">
                                            <svg class="w-3 h-3 text-[#8d85ec] flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span class="truncate">{{ $event->venue }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Vendor Column -->
                            <td class="px-3.5 py-3 whitespace-nowrap">
                                <div class="font-semibold text-gray-900 dark:text-white">
                                    {{ $event->vendor->name ?? 'Organizer #' . $event->vendor_id }}
                                </div>
                                @if(!empty($event->vendor->email))
                                    <div class="text-[11px] text-gray-500 dark:text-gray-400">
                                        {{ $event->vendor->email }}
                                    </div>
                                @endif
                            </td>

                            <!-- Category Column -->
                            <td class="px-3 py-3 text-center whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300">
                                    {{ $event->category ?? 'General' }}
                                </span>
                            </td>

                            <!-- Date Column -->
                            <td class="px-3.5 py-3 whitespace-nowrap">
                                <div class="font-medium text-gray-900 dark:text-gray-200">
                                    {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                </div>
                                <div class="text-[11px] text-gray-400 dark:text-gray-500">
                                    {{ \Carbon\Carbon::parse($event->event_date)->format('h:i A') }}
                                </div>
                            </td>

                            <!-- Status Column -->
                            <td class="px-3 py-3 text-center whitespace-nowrap">
                                @if($isSoldOut)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">
                                        Sold Out
                                    </span>
                                @elseif($isPast)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                        Completed
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                                        Active
                                    </span>
                                @endif
                            </td>

                            <!-- Actions Column -->
                            <td class="px-3.5 py-3 text-center whitespace-nowrap">
                                <a href="{{ route('admin.events.show', $event->id) }}" 
                                   class="inline-flex items-center gap-1 bg-[#8D85EC] hover:bg-[#7b76e4] text-white px-2.5 py-1.5 rounded-lg text-xs font-semibold transition shadow-sm">
                                    <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400 font-medium">
                                No events found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($events->hasPages())
            <div class="mt-4">
                {{ $events->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
