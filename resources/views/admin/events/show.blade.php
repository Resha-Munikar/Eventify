@extends('layouts.app')

@section('title', 'Admin - Event Details: ' . $event->event_name)
@php 
$noNavbar = true;
$noFooter = true; 
$isPast = \Carbon\Carbon::parse($event->event_date)->isPast();
$isSoldOut = (int)$event->available_seats <= 0;
@endphp

@section('content')
@include('admin.sidebar') 

<div class="max-w-7xl mx-auto mt-10 ml-0 sm:ml-72 mr-4 sm:mr-10 p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 sm:p-8 border border-gray-100 dark:border-gray-700">
        <!-- Header & Breadcrumb -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 pb-6 border-b border-gray-100 dark:border-gray-700">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <a href="{{ route('admin.events.index') }}" class="text-xs font-semibold text-[#8d85ec] hover:underline flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Events
                    </a>
                </div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">
                    {{ $event->event_name }}
                </h2>
                <div class="flex flex-wrap items-center gap-2 mt-2">
                    @if($event->category)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300">
                            {{ $event->category }}
                        </span>
                    @endif

                    @if($isSoldOut)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">
                            Sold Out
                        </span>
                    @elseif($isPast)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                            Completed / Past
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                            Active / Upcoming
                        </span>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('events.show', $event->id) }}" target="_blank"
                   class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-xl text-xs font-semibold transition inline-flex items-center gap-1.5 shadow-sm">
                    <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    Live Public Page
                </a>
            </div>
        </div>

        <!-- Event Overview Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Left: Event Poster -->
            <div class="lg:col-span-1">
                <div class="rounded-2xl overflow-hidden shadow-md border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 aspect-[4/3] sm:aspect-[4/5] relative">
                    @if($event->image && file_exists(public_path('uploads/' . $event->image)))
                        <img src="{{ asset('uploads/' . $event->image) }}" alt="{{ $event->event_name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 p-6 text-center">
                            <svg class="w-16 h-16 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-xs">No cover image uploaded</span>
                        </div>
                    @endif
                </div>

                <!-- Organizer Card -->
                <div class="mt-6 p-4 rounded-xl bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600">
                    <div class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Event Organizer / Vendor</div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#8d85ec]/20 text-[#8d85ec] font-bold flex items-center justify-center text-sm">
                            {{ strtoupper(substr($event->vendor->name ?? 'V', 0, 1)) }}
                        </div>
                        <div>
                            <div class="font-bold text-sm text-gray-900 dark:text-white">
                                {{ $event->vendor->name ?? 'Vendor #' . $event->vendor_id }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $event->vendor->email ?? 'No email provided' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Event Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Key Details Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Date & Time -->
                    <div class="p-4 rounded-xl bg-purple-50/50 dark:bg-gray-700/30 border border-purple-100 dark:border-gray-700">
                        <div class="text-xs text-[#8d85ec] font-bold uppercase tracking-wider mb-1 flex items-center gap-1.5">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Event Date & Time
                        </div>
                        <div class="text-sm font-semibold text-gray-900 dark:text-white">
                            {{ \Carbon\Carbon::parse($event->event_date)->format('l, M d, Y') }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                            {{ \Carbon\Carbon::parse($event->event_date)->format('h:i A') }}
                        </div>
                    </div>

                    <!-- Venue -->
                    <div class="p-4 rounded-xl bg-purple-50/50 dark:bg-gray-700/30 border border-purple-100 dark:border-gray-700">
                        <div class="text-xs text-[#8d85ec] font-bold uppercase tracking-wider mb-1 flex items-center gap-1.5">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Location / Venue
                        </div>
                        <div class="text-sm font-semibold text-gray-900 dark:text-white">
                            {{ $event->venue }}
                        </div>
                    </div>

                    <!-- Capacity / Seats -->
                    <div class="p-4 rounded-xl bg-purple-50/50 dark:bg-gray-700/30 border border-purple-100 dark:border-gray-700">
                        <div class="text-xs text-[#8d85ec] font-bold uppercase tracking-wider mb-1 flex items-center gap-1.5">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M12 12a5 5 0 100-10 5 5 0 000 10z" />
                            </svg>
                            Available Capacity
                        </div>
                        <div class="text-sm font-semibold text-gray-900 dark:text-white">
                            {{ $event->available_seats }} seat(s) available
                        </div>
                    </div>

                    <!-- Base Starting Price -->
                    <div class="p-4 rounded-xl bg-purple-50/50 dark:bg-gray-700/30 border border-purple-100 dark:border-gray-700">
                        <div class="text-xs text-[#8d85ec] font-bold uppercase tracking-wider mb-1 flex items-center gap-1.5">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Price Starting At
                        </div>
                        <div class="text-base font-bold text-green-600 dark:text-green-400">
                            Rs {{ number_format($event->min_price, 2) }}
                        </div>
                    </div>
                </div>

                <!-- Event Description -->
                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-2">Event Description</h3>
                    <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-700/30 border border-gray-200 dark:border-gray-700 text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line">
                        {{ $event->description }}
                    </div>
                </div>

                <!-- Ticket Types Tiers -->
                @if($event->ticketTypes && $event->ticketTypes->count() > 0)
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-3">Ticket Types & Inventory</h3>
                        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                            <table class="w-full text-xs text-left">
                                <thead class="bg-gray-100 dark:bg-gray-700/60 font-semibold text-gray-700 dark:text-gray-300">
                                    <tr>
                                        <th class="px-4 py-2.5">Tier Name</th>
                                        <th class="px-4 py-2.5">Price</th>
                                        <th class="px-4 py-2.5 text-center">Allocated</th>
                                        <th class="px-4 py-2.5 text-center">Sold</th>
                                        <th class="px-4 py-2.5 text-center">Remaining</th>
                                        <th class="px-4 py-2.5">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-gray-800 dark:text-gray-200">
                                    @foreach($event->ticketTypes as $tier)
                                        @php
                                            $remaining = max(0, $tier->quantity - $tier->sold_quantity);
                                        @endphp
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                            <td class="px-4 py-2.5 font-bold">{{ $tier->name }}</td>
                                            <td class="px-4 py-2.5 font-semibold text-green-600 dark:text-green-400">Rs {{ number_format($tier->price, 2) }}</td>
                                            <td class="px-4 py-2.5 text-center">{{ $tier->quantity }}</td>
                                            <td class="px-4 py-2.5 text-center font-semibold">{{ $tier->sold_quantity }}</td>
                                            <td class="px-4 py-2.5 text-center font-bold {{ $remaining <= 0 ? 'text-red-500' : 'text-[#8d85ec]' }}">{{ $remaining }}</td>
                                            <td class="px-4 py-2.5">
                                                <span class="inline-flex px-2 py-0.5 text-[10px] font-semibold rounded-full {{ $tier->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                                    {{ ucfirst($tier->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
