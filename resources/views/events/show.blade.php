@extends('layouts.app')

@section('title', $event->event_name . ' - Tickets & Details | Eventify')

@section('content')
@php
    $catColor = match($event->category) {
        'Concert' => 'bg-purple-100 dark:bg-purple-900/60 text-[#6C5CE7] dark:text-purple-300 border-purple-200 dark:border-purple-700',
        'Comedy' => 'bg-amber-100 dark:bg-amber-900/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-700',
        'Sports' => 'bg-teal-100 dark:bg-teal-900/60 text-teal-700 dark:text-teal-300 border-teal-200 dark:border-teal-700',
        'Theatre' => 'bg-rose-100 dark:bg-rose-900/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-700',
        'Festival' => 'bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-700',
        default => 'bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 border-indigo-200 dark:border-indigo-700'
    };
    $eventDate = \Carbon\Carbon::parse($event->event_date);
    $activeTickets = $event->ticketTypes->where('status', 'active');
    $minPrice = $activeTickets->isNotEmpty() ? $activeTickets->min('price') : $event->price;
    $maxPrice = $activeTickets->isNotEmpty() ? $activeTickets->max('price') : $event->price;
    $totalRemaining = $activeTickets->isNotEmpty() 
        ? $activeTickets->sum(fn($t) => max(0, $t->quantity - $t->sold_quantity)) 
        : $event->available_seats;
    $imagePath = $event->image 
        ? (file_exists(public_path('uploads/' . $event->image)) ? asset('uploads/' . $event->image) : asset('uploads/concert.jpg')) 
        : asset('uploads/concert.jpg');
@endphp

<div class="bg-[#faf9ff] dark:bg-gray-950 text-gray-900 dark:text-gray-100 min-h-screen selection:bg-[#8D85EC] selection:text-white pb-16"
     x-data="{
        selectedEvent: {{ $event->toJson() }},
        selectedTicketId: {{ $activeTickets->first() ? $activeTickets->first()->id : ($event->ticketTypes->first() ? $event->ticketTypes->first()->id : 'null') }},
        tickets: 1,
        showKhaltiPopup: false,
        phone: '',
        mpin: '',
        paymentError: '',

        getSelectedTicket() {
            if (!this.selectedEvent || !this.selectedEvent.ticket_types) return null;
            return this.selectedEvent.ticket_types.find(t => t.id === this.selectedTicketId) || null;
        },

        getTicketPrice() {
            let t = this.getSelectedTicket();
            return t ? Number(t.price) : (this.selectedEvent ? Number(this.selectedEvent.price) : 0);
        },

        getMaxTickets() {
            let t = this.getSelectedTicket();
            if (!t) return this.selectedEvent ? (this.selectedEvent.available_seats || 1) : 1;
            return Math.max(1, t.quantity - t.sold_quantity);
        },

        isSoldOut() {
            let t = this.getSelectedTicket();
            if (!t) return (this.selectedEvent && this.selectedEvent.available_seats <= 0);
            return (t.quantity - t.sold_quantity) <= 0;
        },

        getSubtotal() {
            return this.tickets * this.getTicketPrice();
        },

        getTotal() {
            return this.getSubtotal() + 5.65;
        }
     }">

    <!-- ========================================== -->
    <!-- 1. BREADCRUMBS & TOP NAV                  -->
    <!-- ========================================== -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-5 pb-3">
        <div class="flex flex-wrap items-center justify-between gap-3 text-xs sm:text-sm">
            <a href="{{ route('events') }}" class="inline-flex items-center gap-1.5 font-semibold text-[#8D85EC] hover:text-[#746cd4] transition group">
                <span class="group-hover:-translate-x-1 transition-transform">←</span>
                <span>Back to Events</span>
            </a>

            <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                <a href="{{ route('welcome') }}" class="hover:text-gray-900 dark:hover:text-white transition">Home</a>
                <span>/</span>
                <a href="{{ route('events') }}" class="hover:text-gray-900 dark:hover:text-white transition">Events</a>
                <span>/</span>
                <span class="text-gray-800 dark:text-gray-200 font-medium truncate max-w-[200px] sm:max-w-none">{{ $event->event_name }}</span>
            </div>
        </div>
    </div>


    <!-- ========================================== -->
    <!-- 2. HERO BANNER SECTION (BOOKMYSHOW STYLE) -->
    <!-- ========================================== -->
    <section class="relative bg-gray-900 text-white overflow-hidden py-10 lg:py-14 my-2">
        <!-- Blurred Backdrop Background -->
        <div class="absolute inset-0 bg-cover bg-center opacity-25 blur-2xl scale-110 pointer-events-none"
             style="background-image: url('{{ $imagePath }}');">
        </div>
        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-r from-gray-950 via-gray-950/90 to-gray-900/80"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-12 gap-8 lg:gap-10 items-center">
                
                <!-- Event Poster (Left) -->
                <div class="lg:col-span-4 flex justify-center lg:justify-start">
                    <div class="relative w-64 sm:w-72 md:w-80 lg:w-full rounded-2xl overflow-hidden shadow-2xl border-2 border-white/10 group bg-gray-800 aspect-[3/4]">
                        <img src="{{ $imagePath }}" 
                             alt="{{ $event->event_name }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                        
                        @if($event->category)
                            <span class="absolute top-3.5 left-3.5 bg-black/75 backdrop-blur-md text-white text-xs font-bold px-3 py-1 rounded-full border border-white/20">
                                {{ $event->category }}
                            </span>
                        @endif

                        <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent p-4 text-center">
                            <span class="text-xs font-semibold text-purple-200 uppercase tracking-wider">
                                Live In {{ $event->venue }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Event Key Details (Right) -->
                <div class="lg:col-span-8 space-y-5 text-left">
                    
                    <!-- Badges Row -->
                    <div class="flex flex-wrap items-center gap-2.5">
                        <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $catColor }}">
                            • {{ $event->category ?? 'Event' }}
                        </span>
                        
                        @if($totalRemaining > 0 && $totalRemaining <= 20)
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                🔥 Limited Tickets ({{ $totalRemaining }} left)
                            </span>
                        @elseif($totalRemaining <= 0)
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-500/20 text-red-300 border border-red-500/30">
                                ❌ Sold Out
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                🟢 Booking Open ({{ $totalRemaining }} seats available)
                            </span>
                        @endif
                    </div>

                    <!-- Title -->
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-white leading-tight">
                        {{ $event->event_name }}
                    </h1>

                    <!-- Key Metadata Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <!-- Date & Time -->
                        <div class="flex items-start gap-3 bg-white/5 backdrop-blur-sm p-3.5 rounded-xl border border-white/10">
                            <div class="w-10 h-10 rounded-lg bg-[#8D85EC]/20 flex items-center justify-center text-xl flex-shrink-0 text-[#8D85EC]">
                                📅
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Date & Time</p>
                                <p class="text-sm font-bold text-white">{{ $eventDate->format('D, d M Y') }}</p>
                                <p class="text-xs text-purple-300">{{ $eventDate->format('h:i A') }} onwards</p>
                            </div>
                        </div>

                        <!-- Venue -->
                        <div class="flex items-start gap-3 bg-white/5 backdrop-blur-sm p-3.5 rounded-xl border border-white/10">
                            <div class="w-10 h-10 rounded-lg bg-[#8D85EC]/20 flex items-center justify-center text-xl flex-shrink-0 text-[#8D85EC]">
                                📍
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Venue Location</p>
                                <p class="text-sm font-bold text-white line-clamp-1">{{ $event->venue }}</p>
                                <p class="text-xs text-purple-300">Kathmandu, Nepal</p>
                            </div>
                        </div>
                    </div>

                    <!-- Price & Quick CTA -->
                    <div class="flex flex-wrap items-center justify-between gap-4 pt-4 border-t border-white/10">
                        <div>
                            <span class="text-xs text-gray-400 block">Starting from</span>
                            <div class="flex items-baseline gap-2">
                                <span class="text-2xl sm:text-3xl font-extrabold text-[#8D85EC]">
                                    Rs {{ number_format($minPrice, 2) }}
                                </span>
                                @if($minPrice != $maxPrice)
                                    <span class="text-xs text-gray-400">up to Rs {{ number_format($maxPrice, 0) }}</span>
                                @endif
                            </div>
                        </div>

                        <a href="#ticket-selection-section" 
                           class="bg-[#8D85EC] hover:bg-[#7a72d6] text-white font-bold text-sm sm:text-base px-8 py-3.5 rounded-full shadow-lg shadow-[#8D85EC]/30 hover:shadow-xl transition transform hover:scale-105 active:scale-95 flex items-center gap-2">
                            <span>Select Tickets</span>
                            <span>↓</span>
                        </a>
                    </div>

                </div>

            </div>
        </div>
    </section>


    <!-- ========================================== -->
    <!-- 3. MAIN CONTENT & TICKET BOOKING LAYOUT    -->
    <!-- ========================================== -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10">
            
            <!-- LEFT COLUMN: EVENT DETAILS (7 Cols) -->
            <div class="lg:col-span-7 space-y-8 text-left">
                
                <!-- 1. About Event / Full Description -->
                <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2.5">
                        <span class="w-2.5 h-6 bg-[#8D85EC] rounded-full"></span>
                        <span>About The Event</span>
                    </h2>
                    
                    <div class="prose dark:prose-invert max-w-none text-sm sm:text-base text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line">
                        {{ $event->description }}
                    </div>

                    <!-- Highlight Features Pills -->
                    <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700 grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <div class="flex items-center gap-2 p-2.5 rounded-xl bg-purple-50 dark:bg-gray-700/50 text-xs font-semibold text-gray-800 dark:text-gray-200">
                            <span>🎟️</span>
                            <span>Digital E-Ticket</span>
                        </div>
                        <div class="flex items-center gap-2 p-2.5 rounded-xl bg-purple-50 dark:bg-gray-700/50 text-xs font-semibold text-gray-800 dark:text-gray-200">
                            <span>⚡</span>
                            <span>Instant Confirmation</span>
                        </div>
                        <div class="flex items-center gap-2 p-2.5 rounded-xl bg-purple-50 dark:bg-gray-700/50 text-xs font-semibold text-gray-800 dark:text-gray-200">
                            <span>🛡️</span>
                            <span>100% Buyer Guarantee</span>
                        </div>
                    </div>
                </div>

                <!-- 2. Event Guide / Info Specs -->
                <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2.5">
                        <span class="w-2.5 h-6 bg-[#8D85EC] rounded-full"></span>
                        <span>Event Guide & Facilities</span>
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs sm:text-sm">
                        <div class="p-3.5 rounded-xl bg-gray-50 dark:bg-gray-700/60 border border-gray-100 dark:border-gray-600 space-y-1">
                            <span class="text-gray-500 dark:text-gray-400 font-medium">Genre / Category</span>
                            <p class="font-bold text-gray-900 dark:text-white text-sm">{{ $event->category ?? 'General Entertainment' }}</p>
                        </div>
                        <div class="p-3.5 rounded-xl bg-gray-50 dark:bg-gray-700/60 border border-gray-100 dark:border-gray-600 space-y-1">
                            <span class="text-gray-500 dark:text-gray-400 font-medium">Estimated Duration</span>
                            <p class="font-bold text-gray-900 dark:text-white text-sm">2 - 3 Hours</p>
                        </div>
                        <div class="p-3.5 rounded-xl bg-gray-50 dark:bg-gray-700/60 border border-gray-100 dark:border-gray-600 space-y-1">
                            <span class="text-gray-500 dark:text-gray-400 font-medium">Age Restriction</span>
                            <p class="font-bold text-gray-900 dark:text-white text-sm">Open to all age groups</p>
                        </div>
                        <div class="p-3.5 rounded-xl bg-gray-50 dark:bg-gray-700/60 border border-gray-100 dark:border-gray-600 space-y-1">
                            <span class="text-gray-500 dark:text-gray-400 font-medium">Languages</span>
                            <p class="font-bold text-gray-900 dark:text-white text-sm">Nepali, English</p>
                        </div>
                    </div>
                </div>

                <!-- 3. Venue & Location Information -->
                <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2.5">
                        <span class="w-2.5 h-6 bg-[#8D85EC] rounded-full"></span>
                        <span>Venue & Location</span>
                    </h2>

                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-4 rounded-xl bg-purple-50/60 dark:bg-gray-700/50 border border-purple-100 dark:border-gray-600">
                        <div class="flex items-center gap-3.5">
                            <div class="w-12 h-12 rounded-xl bg-[#8D85EC] text-white flex items-center justify-center text-2xl flex-shrink-0 shadow-md">
                                📍
                            </div>
                            <div>
                                <h3 class="font-bold text-base text-gray-900 dark:text-white">{{ $event->venue }}</h3>
                                <p class="text-xs text-gray-600 dark:text-gray-300">Full address and entry gates open 30 mins prior to showtime</p>
                            </div>
                        </div>
                        
                        <a href="https://maps.google.com/?q={{ urlencode($event->venue) }}" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="px-4 py-2 rounded-lg bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-xs font-bold text-[#8D85EC] hover:bg-gray-50 dark:hover:bg-gray-700 transition shadow-sm whitespace-nowrap">
                            View on Google Maps ↗
                        </a>
                    </div>
                </div>

                <!-- 4. Important Terms & Guidelines -->
                <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white mb-3">
                        Terms & Entry Guidelines
                    </h2>
                    <ul class="space-y-2 text-xs sm:text-sm text-gray-600 dark:text-gray-300 list-disc list-inside">
                        <li>Please carry a valid digital copy or printout of the confirmed e-ticket sent to your email.</li>
                        <li>Gates open 45 minutes prior to the scheduled start time. Early arrival is recommended.</li>
                        <li>Outside food and beverages are strictly prohibited inside the main arena.</li>
                        <li>Tickets once booked are non-transferable and subject to organizer terms.</li>
                    </ul>
                </div>

                <!-- 5. Related Events You Might Like -->
                @if($relatedEvents->isNotEmpty())
                    <div class="pt-4">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">You Might Also Like</h2>
                            <a href="{{ route('events') }}" class="text-xs font-semibold text-[#8D85EC] hover:underline">Explore all &rarr;</a>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            @foreach($relatedEvents as $rEvent)
                                @php
                                    $rImage = $rEvent->image ? (file_exists(public_path('uploads/' . $rEvent->image)) ? asset('uploads/' . $rEvent->image) : asset('uploads/concert.jpg')) : asset('uploads/concert.jpg');
                                    $rMinPrice = $rEvent->min_price ?? $rEvent->price;
                                @endphp
                                <a href="{{ route('events.show', $rEvent->slug ?: $rEvent->id) }}" 
                                   class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition hover:-translate-y-1 flex flex-col justify-between group">
                                    <div class="h-32 overflow-hidden bg-gray-100 dark:bg-gray-700 relative">
                                        <img src="{{ $rImage }}" alt="{{ $rEvent->event_name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        @if($rEvent->category)
                                            <span class="absolute top-2 right-2 bg-black/70 backdrop-blur-sm text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                                                {{ $rEvent->category }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="p-3 space-y-1">
                                        <h3 class="font-bold text-xs text-gray-900 dark:text-white line-clamp-1 group-hover:text-[#8D85EC] transition">{{ $rEvent->event_name }}</h3>
                                        <p class="text-[11px] text-gray-500 dark:text-gray-400 line-clamp-1">📍 {{ $rEvent->venue }}</p>
                                        <p class="text-xs font-extrabold text-[#8D85EC] pt-1">Rs {{ number_format($rMinPrice, 0) }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>


            <!-- RIGHT COLUMN: STICKY TICKET SELECTION & BOOKING (5 Cols) -->
            <div class="lg:col-span-5" id="ticket-selection-section">
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl p-6 sm:p-8 border border-gray-200 dark:border-gray-700 lg:sticky top-8 space-y-6">
                    
                    <div class="border-b border-gray-100 dark:border-gray-700 pb-4">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-black text-gray-900 dark:text-white">
                                Select Tickets
                            </h2>
                            <span class="text-xs bg-purple-100 text-[#8d85ec] font-bold px-2.5 py-1 rounded-full dark:bg-purple-900/50">
                                {{ $totalRemaining }} left
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Choose ticket tier and quantity to proceed</p>
                    </div>

                    <!-- STEP 1: TICKET TIERS RADIO SELECTION -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2.5">
                            1. Select Ticket Tier
                        </label>

                        <div class="space-y-2.5">
                            @forelse($event->ticketTypes as $ticket)
                                @php
                                    $rem = max(0, $ticket->quantity - $ticket->sold_quantity);
                                    $isSoldOut = $rem <= 0 || $ticket->status !== 'active';
                                @endphp
                                <label 
                                    :class="{
                                        'border-[#8D85EC] bg-purple-50/70 dark:bg-purple-950/30 ring-2 ring-[#8D85EC]': selectedTicketId === {{ $ticket->id }},
                                        'border-gray-200 dark:border-gray-700 hover:border-purple-300 dark:hover:border-purple-600': selectedTicketId !== {{ $ticket->id }},
                                        'opacity-50 cursor-not-allowed bg-gray-50 dark:bg-gray-800/40': {{ $isSoldOut ? 'true' : 'false' }}
                                    }"
                                    class="flex items-start justify-between p-4 rounded-2xl border transition cursor-pointer"
                                >
                                    <div class="flex items-start gap-3">
                                        <input 
                                            type="radio" 
                                            name="ticket_tier_radio" 
                                            :value="{{ $ticket->id }}" 
                                            x-model.number="selectedTicketId"
                                            {{ $isSoldOut ? 'disabled' : '' }}
                                            class="mt-1 text-[#8D85EC] focus:ring-[#8D85EC]"
                                        >
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-gray-900 dark:text-white text-sm">{{ $ticket->name }}</span>
                                                @if($isSoldOut)
                                                    <span class="text-[10px] bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300 px-2 py-0.5 rounded-full font-bold">Sold Out</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $ticket->description ?: 'Standard access' }}</p>
                                            
                                            <div class="mt-1 text-[11px]">
                                                @if($rem > 5)
                                                    <span class="text-gray-500 dark:text-gray-400">{{ $rem }} available</span>
                                                @elseif($rem > 0)
                                                    <span class="text-amber-600 dark:text-amber-400 font-bold">Only {{ $rem }} left!</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-right flex-shrink-0">
                                        <span class="text-base font-extrabold text-[#8D85EC]">
                                            Rs {{ number_format($ticket->price, 2) }}
                                        </span>
                                    </div>
                                </label>
                            @empty
                                <!-- Fallback if event has no explicit ticket_types -->
                                <label class="flex items-start justify-between p-4 rounded-2xl border border-[#8D85EC] bg-purple-50/70 dark:bg-purple-950/30 ring-2 ring-[#8D85EC]">
                                    <div>
                                        <span class="font-bold text-gray-900 dark:text-white text-sm">General Admission</span>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Standard entry</p>
                                    </div>
                                    <span class="text-base font-extrabold text-[#8D85EC]">Rs {{ number_format($event->price, 2) }}</span>
                                </label>
                            @endforelse
                        </div>
                    </div>

                    <!-- STEP 2: QUANTITY SELECTOR -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">
                            2. Ticket Quantity
                        </label>
                        <div class="flex items-center gap-3">
                            <button 
                                type="button" 
                                @click="if(tickets > 1) tickets--" 
                                :disabled="tickets <= 1 || isSoldOut()"
                                class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-bold text-lg transition flex items-center justify-center disabled:opacity-40"
                            >-</button>
                            
                            <input 
                                type="number" 
                                x-model.number="tickets" 
                                min="1" 
                                :max="getMaxTickets()" 
                                :disabled="isSoldOut()"
                                class="w-20 text-center border border-gray-300 dark:border-gray-600 rounded-xl py-2 text-gray-900 dark:text-white font-bold focus:ring-2 focus:ring-[#8D85EC] focus:outline-none dark:bg-gray-700"
                            >

                            <button 
                                type="button" 
                                @click="if(tickets < getMaxTickets()) tickets++" 
                                :disabled="tickets >= getMaxTickets() || isSoldOut()"
                                class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-bold text-lg transition flex items-center justify-center disabled:opacity-40"
                            >+</button>

                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-1" x-text="'Max: ' + getMaxTickets() + ' ticket(s)'"></span>
                        </div>
                    </div>

                    <!-- STEP 3: ORDER SUMMARY & PRICE BREAKDOWN -->
                    <div class="bg-gray-50 dark:bg-gray-700/60 p-4 sm:p-5 rounded-2xl border border-gray-200 dark:border-gray-600 space-y-2 text-xs sm:text-sm">
                        <div class="flex justify-between text-gray-600 dark:text-gray-300">
                            <span>Selected Tier:</span>
                            <span class="font-bold text-gray-900 dark:text-white" x-text="getSelectedTicket() ? getSelectedTicket().name : 'Standard'"></span>
                        </div>
                        <div class="flex justify-between text-gray-600 dark:text-gray-300">
                            <span>Price per Ticket:</span>
                            <span class="font-semibold text-gray-900 dark:text-white" x-text="'Rs ' + getTicketPrice().toFixed(2)"></span>
                        </div>
                        <div class="flex justify-between text-gray-600 dark:text-gray-300">
                            <span>Subtotal (<span x-text="tickets"></span> &times; Rs <span x-text="getTicketPrice().toFixed(0)"></span>):</span>
                            <span class="font-semibold text-gray-900 dark:text-white" x-text="'Rs ' + getSubtotal().toFixed(2)"></span>
                        </div>
                        <div class="flex justify-between text-gray-600 dark:text-gray-300">
                            <span>Service & Handling Fee:</span>
                            <span class="font-semibold text-gray-900 dark:text-white">Rs 5.65</span>
                        </div>
                        <div class="border-t border-gray-200 dark:border-gray-600 pt-3 mt-2 flex justify-between items-center">
                            <span class="font-bold text-gray-900 dark:text-white text-sm sm:text-base">Total Amount:</span>
                            <span class="font-extrabold text-[#8D85EC] text-xl sm:text-2xl" x-text="'Rs ' + getTotal().toFixed(2)"></span>
                        </div>
                    </div>

                    <!-- PROMINENT CTA BUTTON -->
                    <div>
                        @guest
                            <a href="{{ route('login') }}" 
                               class="w-full block text-center py-4 rounded-2xl text-white font-bold text-sm sm:text-base transition transform hover:scale-[1.02] active:scale-95 shadow-lg"
                               style="background: linear-gradient(90deg, #8D85EC 0%, #7a72d6 100%); box-shadow: 0 4px 20px rgba(141, 133, 236, 0.4);">
                                Login to Book Tickets
                            </a>
                        @endguest

                        @auth
                            <button 
                                type="button"
                                @click="if(!isSoldOut() && selectedTicketId) { showKhaltiPopup = true; }"
                                :disabled="isSoldOut() || !selectedTicketId"
                                class="w-full py-4 rounded-2xl text-white font-bold text-sm sm:text-base transition transform hover:scale-[1.02] active:scale-95 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                                style="background: linear-gradient(90deg, #8D85EC 0%, #7a72d6 100%); box-shadow: 0 4px 20px rgba(141, 133, 236, 0.4);">
                                <span x-text="isSoldOut() ? 'Sold Out' : 'Continue to Booking (Khalti)'"></span>
                                <span>&rarr;</span>
                            </button>
                        @endauth
                    </div>

                    <div class="text-center pt-2">
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 flex items-center justify-center gap-1">
                            <span>🔒</span> 256-Bit SSL Encrypted & Instant QR E-Ticket delivery
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </div>


    <!-- ========================================== -->
    <!-- 4. KHALTI PAYMENT MODAL POPUP              -->
    <!-- ========================================== -->
    <div x-show="showKhaltiPopup" 
         x-transition.opacity
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/75 backdrop-blur-sm p-4">
        
        <div @click.away="showKhaltiPopup=false; paymentError='';" 
             class="bg-white dark:bg-gray-900 rounded-3xl shadow-2xl max-w-4xl w-full overflow-hidden flex flex-col md:flex-row border border-gray-200 dark:border-gray-700">

            <!-- Left: Order Details & Price breakdown -->
            <div class="w-full md:w-2/3 bg-[#FAF9FC] dark:bg-gray-800 p-6 sm:p-8 flex flex-col justify-between gap-6">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Confirm Booking & Pay</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">Official Khalti Payment Verification</p>

                    <!-- Event Card in Modal -->
                    <div class="mt-4 bg-white dark:bg-gray-700 p-4 rounded-2xl border border-gray-200 dark:border-gray-600 space-y-1">
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Booking For</p>
                        <p class="font-bold text-gray-900 dark:text-white text-base" x-text="selectedEvent.event_name"></p>
                        <p class="text-xs text-purple-600 dark:text-purple-300 font-semibold" 
                           x-text="'Ticket Tier: ' + (getSelectedTicket() ? getSelectedTicket().name : '') + ' (' + tickets + ' ticket(s))'"></p>
                        <p class="text-xs text-gray-600 dark:text-gray-300" x-text="'Billed to: {{ Auth::user()->name ?? 'User' }} ({{ Auth::user()->email ?? '' }})'"></p>
                    </div>

                    <!-- Amount Summary -->
                    <div class="mt-4 bg-white dark:bg-gray-700 p-4 rounded-2xl border border-gray-200 dark:border-gray-600 space-y-2 text-xs sm:text-sm">
                        <div class="flex justify-between text-gray-600 dark:text-gray-300">
                            <span>Ticket Price (<span x-text="tickets"></span> &times; Rs <span x-text="getTicketPrice().toFixed(0)"></span>)</span>
                            <span class="font-semibold text-gray-900 dark:text-white" x-text="'Rs ' + getSubtotal().toFixed(2)"></span>
                        </div>
                        <div class="flex justify-between text-gray-600 dark:text-gray-300">
                            <span>Service Charge</span>
                            <span class="font-semibold text-gray-900 dark:text-white">Rs 5.65</span>
                        </div>
                        <div class="border-t border-gray-200 dark:border-gray-600 my-2"></div>
                        <div class="flex justify-between font-extrabold text-gray-900 dark:text-white text-lg">
                            <span>Total Payable</span>
                            <span class="text-[#8D85EC]" x-text="'Rs ' + getTotal().toFixed(2)"></span>
                        </div>
                    </div>
                </div>

                <div class="bg-[#7B2CBF] text-white text-xs font-semibold py-2.5 px-4 text-center rounded-xl">
                    PAYMENT POWERED BY <span class="ml-1 font-bold">KHALTI WALLET</span>
                </div>
            </div>

            <!-- Right: Khalti Wallet Number & MPIN Input -->
            <div class="w-full md:w-1/3 bg-white dark:bg-gray-900 p-6 sm:p-8 flex flex-col justify-between gap-4 relative">
                <button @click="showKhaltiPopup=false; paymentError='';" 
                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 dark:hover:text-white text-xl font-bold">
                    ✕
                </button>

                <div>
                    <div class="flex items-center justify-center mb-3">
                        <img src="{{ asset('uploads/khalti.png') }}" alt="Khalti Logo" class="h-8 object-contain">
                    </div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white text-center">Pay with Khalti</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-xs text-center mt-1">Enter your Khalti Mobile Number and MPIN</p>

                    <div class="mt-4 space-y-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">Khalti Mobile Number</label>
                            <input type="text" x-model="phone" placeholder="e.g. 9800000000"
                                   class="w-full border border-gray-300 dark:border-gray-600 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-[#8D85EC] dark:bg-gray-800 dark:text-white outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">Khalti MPIN</label>
                            <input type="password" x-model="mpin" placeholder="MPIN (1111)"
                                   class="w-full border border-gray-300 dark:border-gray-600 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-[#8D85EC] dark:bg-gray-800 dark:text-white outline-none">
                        </div>
                        <p class="text-red-500 text-xs font-semibold" x-text="paymentError"></p>
                    </div>
                </div>

                <div>
                    <button @click="
                        paymentError='';
                        let allowedPhones = ['9800000000','9800000001','9800000002','9800000003','9800000004','9800000005'];
                        if (allowedPhones.includes(phone.trim()) && mpin === '1111') {
                            let eventToSave = {...selectedEvent};
                            let ticketTypeId = selectedTicketId;
                            let count = tickets;
                            showKhaltiPopup = false;
                            saveBookingDetails(eventToSave, ticketTypeId, count);
                        } else {
                            paymentError = '❌ Invalid Khalti ID or MPIN. (Use test phone 9800000000 and PIN 1111)';
                        }"
                        class="w-full py-3.5 rounded-xl text-white font-bold text-sm transition transform hover:scale-[1.02] active:scale-95 shadow-md"
                        style="background: linear-gradient(90deg, #8D85EC 0%, #6E29B0 100%);">
                        Confirm & Pay
                    </button>
                </div>
            </div>

        </div>
    </div>

</div>

<script>
function saveBookingDetails(event, ticketTypeId, tickets) {
    fetch("{{ route('khalti.saveBooking') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            event_id: event.id,
            ticket_type_id: ticketTypeId,
            tickets: tickets
        })
    })
    .then(async response => {
        const data = await response.json();
        if (response.ok && data.success) {
            alert("✅ Payment & Booking Successful! Your ticket has been emailed to you.");
            window.location.href = "{{ route('usereventbook') }}";
        } else {
            console.error("Booking error:", data);
            alert("❌ " + (data.message || "Failed to complete booking."));
        }
    })
    .catch(err => {
        console.error("Fetch error:", err);
        alert("⚠️ Network or server error. Please try again.");
    });
}
</script>
@endsection
