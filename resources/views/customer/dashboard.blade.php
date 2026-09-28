@extends('layouts.app')

@section('title', 'My Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Top Welcome Header -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 sm:p-8 border border-gray-100 dark:border-gray-700 shadow-sm relative overflow-hidden flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#8D85EC]/15 text-[#8D85EC] dark:bg-[#8D85EC]/30">
                    Attendee Portal
                </span>
                <span class="text-xs text-gray-500 dark:text-gray-400">Personal Booking Center</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white">
                Hello, {{ $user->name }}!
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1 max-w-xl">
                Manage your event tickets, discover upcoming experiences in Kathmandu, and view your booking receipts.
            </p>
        </div>

        <!-- Quick Top Action Buttons -->
        <div class="flex items-center gap-3 relative z-10 shrink-0">
            <a href="{{ route('events') }}" class="px-5 py-2.5 rounded-xl bg-[#8D85EC] hover:bg-[#7b76e4] text-white text-xs sm:text-sm font-bold transition shadow-sm">
                Explore Events
            </a>
            <a href="{{ route('usereventbook') }}" class="px-4 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 text-xs sm:text-sm font-semibold transition">
                My Tickets
            </a>
        </div>
    </div>

    <!-- 1. Summary Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Total Bookings -->
        <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs hover:border-[#8D85EC]/50 transition">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Bookings</span>
                <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-900/30 text-[#8D85EC] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-black text-gray-900 dark:text-white">{{ number_format($totalBookings) }}</div>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">
                {{ number_format($totalTicketsPurchased) }} total tickets purchased
            </p>
        </div>

        <!-- Upcoming Attending Events -->
        <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs hover:border-[#8D85EC]/50 transition">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Upcoming Events</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400">{{ number_format($upcomingCount) }}</div>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">
                Events you are scheduled to attend
            </p>
        </div>

        <!-- Saved Events / Wishlist -->
        <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs hover:border-[#8D85EC]/50 transition">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Saved Events</span>
                <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-900/30 text-rose-500 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-black text-gray-900 dark:text-white">{{ number_format($savedEventsCount) }}</div>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">
                <a href="{{ route('events', ['tab' => 'saved']) }}" class="text-[#8D85EC] hover:underline">View saved wishlist &rarr;</a>
            </p>
        </div>

        <!-- Total Spent on Tickets -->
        <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs hover:border-[#8D85EC]/50 transition">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Spent</span>
                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                    <span class="font-bold text-xs">NPR</span>
                </div>
            </div>
            <div class="text-2xl font-black text-[#8D85EC] dark:text-[#a39df0]">
                Rs {{ number_format($totalSpent, 2) }}
            </div>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">
                Confirmed Khalti transactions
            </p>
        </div>

    </div>

    <!-- 2. Middle Grid: Upcoming Attending Events + Latest Booking Confirmation Card -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Upcoming Attending Events (2 Cols) -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Upcoming Events You're Attending</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Events with future dates you have confirmed tickets for</p>
                    </div>
                    <a href="{{ route('usereventbook') }}" class="text-xs font-semibold text-[#8D85EC] hover:underline">
                        View All Tickets &rarr;
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse($upcomingAttendingBookings as $ub)
                        @php
                            $event = $ub->event;
                            $eventDate = $event ? \Carbon\Carbon::parse($event->event_date) : null;
                        @endphp
                        <div class="p-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/30 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-3.5 min-w-0">
                                @if($event && $event->image)
                                    <img src="{{ asset('uploads/' . $event->image) }}" alt="{{ $event->event_name }}" class="w-14 h-14 rounded-2xl object-cover flex-shrink-0 border border-gray-200 dark:border-gray-700" onerror="this.onerror=null; this.src='{{ asset('images/eventify-logo.png') }}';">
                                @else
                                    <div class="w-14 h-14 rounded-2xl bg-purple-100 dark:bg-purple-900/40 text-[#8D85EC] flex items-center justify-center font-black text-base flex-shrink-0">
                                        {{ $event ? substr($event->event_name, 0, 2) : 'EV' }}
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <h4 class="font-bold text-sm text-gray-900 dark:text-white truncate">
                                        {{ $event->event_name ?? 'Event #' . $ub->event_id }}
                                    </h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 flex items-center gap-2">
                                        <span>📅 {{ $eventDate ? $eventDate->format('M d, Y &bull; h:i A') : 'Date TBA' }}</span>
                                        <span>📍 {{ $event->venue ?? 'Kathmandu' }}</span>
                                    </p>
                                    <div class="flex items-center gap-2 mt-1.5">
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300">
                                            {{ $ub->ticketType->name ?? 'General' }} &bull; {{ $ub->tickets }} ticket(s)
                                        </span>
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300">
                                            Confirmed
                                        </span>
                                    </div>
                                </div>
                            </div>

                            @if($event)
                                <a href="{{ route('events.show', $event->id) }}" class="px-4 py-2 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 hover:border-[#8D85EC] text-xs font-bold text-gray-800 dark:text-gray-200 text-center transition">
                                    Event Details
                                </a>
                            @endif
                        </div>
                    @empty
                        <div class="py-10 text-center text-gray-400">
                            <div class="w-12 h-12 mx-auto mb-2 rounded-2xl bg-purple-50 dark:bg-purple-900/30 text-[#8D85EC] flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <p class="font-semibold text-sm text-gray-700 dark:text-gray-300">No Upcoming Events Scheduled</p>
                            <p class="text-xs text-gray-400 mt-0.5">Discover concerts, exhibitions, and comedy shows to attend!</p>
                            <a href="{{ route('events') }}" class="mt-3 inline-block px-4 py-2 rounded-xl bg-[#8D85EC] text-white font-bold text-xs">
                                Browse Hot Events
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700/60 text-right">
                <a href="{{ route('events') }}" class="text-xs font-semibold text-[#8D85EC] hover:underline">
                    Browse all Kathmandu events &rarr;
                </a>
            </div>
        </div>

        <!-- Latest Booking Card (1 Col) -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Latest Booking</h2>
                    @if($latestBooking)
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300">
                            #{{ $latestBooking->id }}
                        </span>
                    @endif
                </div>

                @if($latestBooking)
                    @php
                        $lEvent = $latestBooking->event;
                        $lAmount = $latestBooking->total_amount ?? $latestBooking->amount;
                    @endphp
                    <div class="p-4 rounded-2xl bg-gradient-to-br from-[#8D85EC]/10 to-[#8D85EC]/5 border border-[#8D85EC]/20 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-[#8D85EC] uppercase tracking-wider">Confirmed Ticket</span>
                            <span class="text-[11px] text-gray-500 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($latestBooking->booking_date ?? $latestBooking->created_at)->format('M d, Y') }}
                            </span>
                        </div>

                        <h3 class="font-bold text-base text-gray-900 dark:text-white line-clamp-1">
                            {{ $lEvent->event_name ?? 'Event #' . $latestBooking->event_id }}
                        </h3>

                        <div class="text-xs space-y-1.5 text-gray-600 dark:text-gray-300">
                            <div class="flex items-center justify-between">
                                <span>Ticket Type:</span>
                                <span class="font-bold text-gray-900 dark:text-white">{{ $latestBooking->ticketType->name ?? 'General' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Quantity:</span>
                                <span class="font-bold text-gray-900 dark:text-white">{{ $latestBooking->tickets }} ticket(s)</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Payment Status:</span>
                                <span class="font-bold text-green-600 dark:text-green-400 uppercase text-[10px]">{{ $latestBooking->payment_status ?? 'Paid' }}</span>
                            </div>
                            <div class="flex items-center justify-between pt-2 border-t border-[#8D85EC]/20 text-sm">
                                <span class="font-bold text-gray-900 dark:text-white">Total Paid:</span>
                                <span class="font-black text-[#8D85EC]">Rs {{ number_format($lAmount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="py-10 text-center text-gray-400">
                        <div class="w-12 h-12 mx-auto mb-2 rounded-2xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                        </div>
                        <p class="font-semibold text-xs text-gray-700 dark:text-gray-300">No Booking History Yet</p>
                        <p class="text-[11px] text-gray-400 mt-0.5">Your most recent ticket receipt will be pinned here.</p>
                    </div>
                @endif
            </div>

            <!-- Quick Actions Grid -->
            <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700/60">
                <span class="text-xs font-bold text-gray-700 dark:text-gray-300 block mb-2">Quick Navigation</span>
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('usereventbook') }}" class="p-2.5 rounded-xl bg-gray-50 dark:bg-gray-700/50 hover:bg-purple-50 dark:hover:bg-gray-700 text-center text-xs font-semibold text-gray-800 dark:text-gray-200 transition">
                        🎟️ My Bookings
                    </a>
                    <a href="{{ route('events', ['tab' => 'saved']) }}" class="p-2.5 rounded-xl bg-gray-50 dark:bg-gray-700/50 hover:bg-purple-50 dark:hover:bg-gray-700 text-center text-xs font-semibold text-gray-800 dark:text-gray-200 transition">
                        ❤️ Saved Events
                    </a>
                    <a href="{{ route('profile') }}" class="p-2.5 rounded-xl bg-gray-50 dark:bg-gray-700/50 hover:bg-purple-50 dark:hover:bg-gray-700 text-center text-xs font-semibold text-gray-800 dark:text-gray-200 transition">
                        ⚙️ Account Profile
                    </a>
                    <a href="{{ route('contact') }}" class="p-2.5 rounded-xl bg-gray-50 dark:bg-gray-700/50 hover:bg-purple-50 dark:hover:bg-gray-700 text-center text-xs font-semibold text-gray-800 dark:text-gray-200 transition">
                        💬 Support
                    </a>
                </div>
            </div>
        </div>

    </div>

    <!-- 3. Saved / Wishlist Events Row -->
    @if($savedEvents->isNotEmpty())
        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Your Saved Events</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Events you've bookmarked for fast booking</p>
                </div>
                <a href="{{ route('events', ['tab' => 'saved']) }}" class="text-xs font-semibold text-[#8D85EC] hover:underline">
                    View All Saved ({{ $savedEventsCount }}) &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($savedEvents as $sEvent)
                    <div class="rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/30 overflow-hidden group hover:shadow-md transition">
                        <div class="relative h-36 w-full overflow-hidden bg-gray-200 dark:bg-gray-700">
                            @if($sEvent->image)
                                <img src="{{ asset('uploads/' . $sEvent->image) }}" alt="{{ $sEvent->event_name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" onerror="this.onerror=null; this.src='{{ asset('images/eventify-logo.png') }}';">
                            @else
                                <div class="w-full h-full flex items-center justify-center font-bold text-[#8D85EC]">Eventify</div>
                            @endif
                            <div class="absolute top-2.5 right-2.5">
                                <button type="button" onclick="toggleSaveEvent(event, {{ $sEvent->id }}, this)" data-save-event-id="{{ $sEvent->id }}" class="w-8 h-8 rounded-full bg-white/90 dark:bg-gray-800/90 text-rose-500 flex items-center justify-center shadow-sm">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="p-3.5 space-y-1.5">
                            <span class="text-[10px] font-bold text-[#8D85EC] uppercase">{{ $sEvent->category ?? 'Event' }}</span>
                            <h4 class="font-bold text-xs text-gray-900 dark:text-white truncate" title="{{ $sEvent->event_name }}">{{ $sEvent->event_name }}</h4>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 truncate">📍 {{ $sEvent->venue }}</p>
                            <div class="flex items-center justify-between pt-2 border-t border-gray-100 dark:border-gray-700">
                                <span class="font-bold text-xs text-gray-900 dark:text-white">Rs {{ number_format($sEvent->price, 2) }}</span>
                                <a href="{{ route('events.show', $sEvent->id) }}" class="px-2.5 py-1 rounded-lg bg-[#8D85EC] text-white text-[11px] font-bold hover:bg-[#7b76e4] transition">
                                    Book Now
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- 4. Recent Bookings Table -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Recent Ticket Orders</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">History of your bookings and confirmations</p>
            </div>
            <a href="{{ route('usereventbook') }}" class="text-xs font-semibold text-[#8D85EC] hover:underline">
                View Full Booking History &rarr;
            </a>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-gray-100 dark:border-gray-700">
            <table class="w-full text-xs text-left">
                <thead class="bg-gray-50 dark:bg-gray-700/60 text-gray-600 dark:text-gray-300 font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3.5">Booking ID</th>
                        <th class="px-4 py-3.5">Event</th>
                        <th class="px-4 py-3.5">Tier</th>
                        <th class="px-4 py-3.5 text-center">Tickets</th>
                        <th class="px-4 py-3.5">Total Amount</th>
                        <th class="px-4 py-3.5">Booking Status</th>
                        <th class="px-4 py-3.5">Payment</th>
                        <th class="px-4 py-3.5">Booking Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60 text-gray-800 dark:text-gray-200">
                    @forelse($recentBookings as $bk)
                        @php
                            $bkAmt = $bk->total_amount ?? $bk->amount;
                        @endphp
                        <tr class="hover:bg-purple-50/30 dark:hover:bg-gray-700/30 transition">
                            <td class="px-4 py-3.5 font-mono font-bold text-gray-500 dark:text-gray-400">#{{ $bk->id }}</td>
                            <td class="px-4 py-3.5 font-semibold text-gray-900 dark:text-white">
                                {{ $bk->event->event_name ?? 'Event #' . $bk->event_id }}
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300">
                                    {{ $bk->ticketType->name ?? 'General' }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-center font-bold">{{ $bk->tickets }}</td>
                            <td class="px-4 py-3.5 font-bold text-[#8D85EC]">Rs {{ number_format($bkAmt, 2) }}</td>
                            <td class="px-4 py-3.5">
                                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300">
                                    {{ ucfirst($bk->booking_status ?? 'Confirmed') }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">
                                    {{ ucfirst($bk->payment_status ?? 'Paid') }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($bk->booking_date ?? $bk->created_at)->format('M d, Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-10 text-center text-gray-400">
                                <p class="font-semibold text-sm text-gray-700 dark:text-gray-300">You haven't booked any event tickets yet.</p>
                                <p class="text-xs text-gray-400 mt-0.5">Explore exciting concerts, sports tournaments, and workshops in Nepal!</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
