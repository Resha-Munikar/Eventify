@extends('layouts.app')

@section('title', 'Book Live Events & Experiences - Eventify')

@section('content')
<div class="bg-[#faf9ff] dark:bg-gray-950 text-gray-900 dark:text-gray-100 min-h-screen font-sans selection:bg-[#6C5CE7] selection:text-white">

    <!-- ========================================== -->
    <!-- 1. HERO SECTION                            -->
    <!-- ========================================== -->
    <section class="relative pt-8 pb-16 md:pt-14 md:pb-24 overflow-hidden">
        <!-- Background subtle glow -->
        <div class="absolute top-10 left-1/4 w-96 h-96 bg-[#8D85EC]/15 rounded-full blur-3xl pointer-events-none -z-10"></div>
        <div class="absolute top-20 right-10 w-96 h-96 bg-[#c4b5fd]/20 rounded-full blur-3xl pointer-events-none -z-10"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-12 gap-10 lg:gap-8 items-center">
                
                <!-- Left Hero Content -->
                <div class="lg:col-span-6 space-y-6 text-left">
                    
                    <!-- Live events badge -->
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white dark:bg-gray-800 border border-gray-200/80 dark:border-gray-700 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                        <span class="text-xs font-semibold text-gray-700 dark:text-gray-300 tracking-wide">Live events near you</span>
                    </div>

                    <!-- Main Hero Title -->
                    <h1 class="text-4xl sm:text-5xl lg:text-[54px] font-extrabold tracking-tight text-gray-900 dark:text-white leading-[1.12]">
                        Book your seat for<br/>
                        <span class="text-[#6C5CE7] italic font-serif font-normal">every</span> kind of night<br/>
                        out
                    </h1>

                    <!-- Hero Subtitle -->
                    <p class="text-base sm:text-lg text-gray-600 dark:text-gray-300 max-w-lg leading-relaxed">
                        Discover concerts, theatre, stand-up comedy, sports, festivals & more. Book fast & safe, get instant e-tickets delivered to your phone.
                    </p>

                    <!-- Search Input Pill (No outlines, fully functional) -->
                    <form action="{{ route('events') }}" method="GET" class="relative max-w-lg">
                        <div class="flex items-center bg-white dark:bg-gray-800 rounded-full border border-gray-200 dark:border-gray-700 shadow-lg shadow-purple-500/5 p-1.5 transition-all">
                            <div class="pl-4 pr-2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input 
                                type="text" 
                                name="query"
                                placeholder="Search by artist, event or venue..." 
                                class="w-full bg-transparent text-sm sm:text-base text-gray-800 dark:text-gray-100 placeholder-gray-400 border-0 border-none outline-none focus:outline-none focus:ring-0 focus:border-none shadow-none ring-0 pr-2 py-2"
                                style="border: none !important; outline: none !important; box-shadow: none !important;"
                            />
                            <button 
                                type="submit" 
                                class="bg-[#6C5CE7] hover:bg-[#5b48db] text-white text-sm font-semibold px-6 py-2.5 rounded-full transition-all duration-200 shadow-md shadow-[#6C5CE7]/25 hover:shadow-lg hover:shadow-[#6C5CE7]/35 flex-shrink-0"
                            >
                                Search
                            </button>
                        </div>
                    </form>

                    <!-- Social Proof / Reviews -->
                    <div class="flex items-center gap-3 pt-2">
                        <div class="flex -space-x-2 overflow-hidden">
                            <img class="inline-block h-8 w-8 rounded-full ring-2 ring-white dark:ring-gray-900 object-cover" src="{{ asset('uploads/avatar.jpg') }}" alt="User 1" />
                            <img class="inline-block h-8 w-8 rounded-full ring-2 ring-white dark:ring-gray-900 object-cover" src="{{ asset('uploads/jane1.jpg') }}" alt="User 2" />
                            <img class="inline-block h-8 w-8 rounded-full ring-2 ring-white dark:ring-gray-900 object-cover" src="{{ asset('uploads/john.jpg') }}" alt="User 3" />
                            <img class="inline-block h-8 w-8 rounded-full ring-2 ring-white dark:ring-gray-900 object-cover" src="{{ asset('uploads/Sara.jpg') }}" alt="User 4" />
                        </div>
                        <div class="flex items-center text-xs sm:text-sm text-gray-600 dark:text-gray-300 font-medium">
                            <span class="text-amber-500 font-bold mr-1">4.9 ★</span>
                            <span>from 12,000+ happy night owls</span>
                        </div>
                    </div>

                </div>

                <!-- Right Hero Visuals: Polaroid Collage -->
                <div class="lg:col-span-6 relative flex justify-center items-center py-4">
                    <div class="relative w-full max-w-[480px] h-[380px] sm:h-[440px]">
                        
                        <!-- Polaroid 1: Top Left - Arena Concert -->
                        <div class="absolute top-0 left-2 sm:left-4 w-40 sm:w-48 bg-white dark:bg-gray-800 p-2 sm:p-2.5 pb-6 sm:pb-7 rounded-sm shadow-xl shadow-gray-900/10 dark:shadow-black/40 transform -rotate-6 hover:rotate-0 hover:scale-105 hover:z-30 transition-all duration-300 cursor-pointer">
                            <div class="w-full h-32 sm:h-40 overflow-hidden rounded-[2px] bg-gray-100">
                                <img src="{{ asset('uploads/a9e3088f2698f4b567d9a1c8e03939eaf4410e02.png') }}" alt="Concert Arena" class="w-full h-full object-cover" />
                            </div>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-mono text-center mt-2 tracking-tight">Arena Vibes '26</p>
                        </div>

                        <!-- Polaroid 2: Top Middle - Candlelit Club -->
                        <div class="absolute top-6 left-32 sm:left-40 w-36 sm:w-44 bg-white dark:bg-gray-800 p-2 sm:p-2.5 pb-6 sm:pb-7 rounded-sm shadow-xl shadow-gray-900/15 dark:shadow-black/50 transform rotate-3 z-10 hover:rotate-0 hover:scale-105 hover:z-30 transition-all duration-300 cursor-pointer">
                            <div class="w-full h-28 sm:h-36 overflow-hidden rounded-[2px] bg-gray-100">
                                <img src="{{ asset('uploads/f792d70df342043677282fd39c4e21b974490b9f.png') }}" alt="Acoustic Night" class="w-full h-full object-cover" />
                            </div>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-mono text-center mt-2 tracking-tight">Candlelight Jazz</p>
                        </div>

                        <!-- Polaroid 3: Top Right Vertical - Park Summer Fest -->
                        <div class="absolute top-2 right-0 sm:right-2 w-40 sm:w-48 bg-white dark:bg-gray-800 p-2 sm:p-2.5 pb-6 sm:pb-7 rounded-sm shadow-xl shadow-gray-900/10 dark:shadow-black/40 transform rotate-6 z-0 hover:rotate-0 hover:scale-105 hover:z-30 transition-all duration-300 cursor-pointer">
                            <div class="w-full h-40 sm:h-48 overflow-hidden rounded-[2px] bg-gray-100">
                                <img src="{{ asset('uploads/8e86b4ab3ce200dcba28708915e47705130c5b95.png') }}" alt="Summer Fest" class="w-full h-full object-cover" />
                            </div>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-mono text-center mt-2 tracking-tight">Summer Festival</p>
                        </div>

                        <!-- Polaroid 4: Bottom Left - Bookstore Gathering -->
                        <div class="absolute bottom-0 left-8 sm:left-14 w-40 sm:w-48 bg-white dark:bg-gray-800 p-2 sm:p-2.5 pb-6 sm:pb-7 rounded-sm shadow-2xl shadow-gray-900/20 dark:shadow-black/60 transform -rotate-3 z-20 hover:rotate-0 hover:scale-105 hover:z-30 transition-all duration-300 cursor-pointer">
                            <div class="w-full h-32 sm:h-40 overflow-hidden rounded-[2px] bg-gray-100">
                                <img src="{{ asset('uploads/37938ebd6daa31a9a624343ed5c5f9a1ab08b240.png') }}" alt="Book Club" class="w-full h-full object-cover" />
                            </div>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-mono text-center mt-2 tracking-tight">Storytellers Night</p>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- ========================================== -->
    <!-- 2. UPCOMING EVENTS SECTION (DYNAMIC DB)   -->
    <!-- ========================================== -->
    <section class="py-16 bg-white dark:bg-gray-900 border-t border-b border-gray-100 dark:border-gray-800" id="upcoming-events-section">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Section Header -->
            <div class="text-left mb-6">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Upcoming Events</h2>
                <p class="text-sm sm:text-base text-gray-500 dark:text-gray-400 mt-1">Hand-picked live experiences happening this week and beyond</p>
            </div>

            <!-- Category Filter Pills (Interactive JS Filtering) -->
            <div class="flex items-center gap-2 overflow-x-auto pb-4 scrollbar-hide text-sm font-medium" id="event-filters">
                <button type="button" onclick="filterEvents('all', this)" class="event-filter-btn px-5 py-2 rounded-full bg-[#6C5CE7] text-white shadow-sm transition whitespace-nowrap">
                    All events
                </button>
                <button type="button" onclick="filterEvents('Concert', this)" class="event-filter-btn px-5 py-2 rounded-full bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:border-gray-400 dark:hover:border-gray-500 transition whitespace-nowrap">
                    Concerts
                </button>
                <button type="button" onclick="filterEvents('Comedy', this)" class="event-filter-btn px-5 py-2 rounded-full bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:border-gray-400 dark:hover:border-gray-500 transition whitespace-nowrap">
                    Comedy
                </button>
                <button type="button" onclick="filterEvents('Sports', this)" class="event-filter-btn px-5 py-2 rounded-full bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:border-gray-400 dark:hover:border-gray-500 transition whitespace-nowrap">
                    Sports
                </button>
                <button type="button" onclick="filterEvents('Theatre', this)" class="event-filter-btn px-5 py-2 rounded-full bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:border-gray-400 dark:hover:border-gray-500 transition whitespace-nowrap">
                    Theatre
                </button>
                <button type="button" onclick="filterEvents('Festival', this)" class="event-filter-btn px-5 py-2 rounded-full bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:border-gray-400 dark:hover:border-gray-500 transition whitespace-nowrap">
                    Festivals
                </button>
            </div>

            <!-- Dynamic Event Cards Grid (Iterating Real Events from DB) -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 mt-6" id="events-grid">
                @forelse($upcomingEvents->take(6) as $event)
                    @php
                        $catColor = match($event->category) {
                            'Concert' => 'bg-purple-50 dark:bg-purple-900/40 text-[#6C5CE7] dark:text-purple-300',
                            'Comedy' => 'bg-amber-50 dark:bg-amber-900/40 text-amber-600 dark:text-amber-300',
                            'Sports' => 'bg-teal-50 dark:bg-teal-900/40 text-teal-600 dark:text-teal-300',
                            'Theatre' => 'bg-rose-50 dark:bg-rose-900/40 text-rose-600 dark:text-rose-300',
                            'Festival' => 'bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-300',
                            default => 'bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-300'
                        };
                        $eventDate = \Carbon\Carbon::parse($event->event_date);
                        $imagePath = $event->image ? (file_exists(public_path('uploads/' . $event->image)) ? asset('uploads/' . $event->image) : asset('uploads/concert.jpg')) : asset('uploads/concert.jpg');
                        $minPrice = $event->min_price ?? $event->price;
                    @endphp

                    <div onclick="window.location.href='{{ route('events.show', $event->slug ?: $event->id) }}'" class="cursor-pointer event-card bg-white dark:bg-gray-800 rounded-2xl border border-gray-200/80 dark:border-gray-700/80 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group" data-category="{{ $event->category }}">
                        
                        <!-- Event Image -->
                        <div class="relative h-52 sm:h-56 overflow-hidden bg-gray-100 dark:bg-gray-700">
                            <a href="{{ route('events.show', $event->slug ?: $event->id) }}">
                                <img src="{{ $imagePath }}" alt="{{ $event->event_name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                            </a>
                            
                            <!-- Date Badge -->
                            <div class="absolute top-3.5 left-3.5 bg-white/95 dark:bg-gray-900/95 backdrop-blur-sm rounded-xl px-3 py-1.5 text-center shadow-md border border-gray-100 dark:border-gray-700">
                                <span class="block text-xs font-bold text-gray-900 dark:text-white uppercase leading-none">{{ $eventDate->format('d') }}</span>
                                <span class="block text-[10px] font-semibold text-[#6C5CE7] uppercase leading-none mt-0.5">{{ $eventDate->format('M') }}</span>
                            </div>

                            <!-- Save / Favorite Button -->
                            @php
                                $isSaved = in_array($event->id, $savedEventIds ?? []);
                            @endphp
                            <button 
                                type="button"
                                onclick="toggleSaveEvent(event, {{ $event->id }}, this)"
                                data-save-event-id="{{ $event->id }}"
                                aria-label="{{ $isSaved ? 'Remove from saved events' : 'Save this event' }}"
                                title="{{ $isSaved ? 'Saved to favorites' : 'Save to favorites' }}"
                                class="save-event-btn absolute top-3.5 right-3.5 w-9 h-9 bg-white/95 dark:bg-gray-900/95 backdrop-blur-sm rounded-full flex items-center justify-center shadow-md transition-all duration-200 hover:scale-110 active:scale-90 z-20 group/btn {{ $isSaved ? 'text-rose-500' : 'text-gray-600 dark:text-gray-300 hover:text-rose-500' }}"
                            >
                                <svg class="w-4 h-4 transition-transform duration-200" 
                                     fill="{{ $isSaved ? 'currentColor' : 'none' }}" 
                                     stroke="currentColor" 
                                     stroke-width="{{ $isSaved ? '0' : '2' }}" 
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Event Body -->
                        <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                            <div class="space-y-2 text-left">
                                <span class="inline-flex items-center text-xs font-semibold px-2.5 py-0.5 rounded-full {{ $catColor }}">
                                    • {{ $event->category ?? 'General' }}
                                </span>
                                <a href="{{ route('events.show', $event->slug ?: $event->id) }}" class="block">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-[#6C5CE7] transition line-clamp-1">
                                        {{ $event->event_name }}
                                    </h3>
                                </a>
                                <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1.5 line-clamp-1">
                                    <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>{{ $event->venue }}</span>
                                </p>
                            </div>

                            <!-- Footer Price & Booking -->
                            <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700">
                                <div>
                                    <span class="text-[11px] text-gray-400 block leading-tight">From</span>
                                    <span class="text-base font-extrabold text-gray-900 dark:text-white">Rs. {{ number_format($minPrice) }}</span>
                                </div>
                                <a href="{{ route('events.show', $event->slug ?: $event->id) }}" class="px-5 py-2 rounded-full bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-xs font-bold hover:bg-black dark:hover:bg-gray-100 transition shadow-sm">
                                    Book now
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 py-12 text-center text-gray-500 dark:text-gray-400">
                        No events found in the database.
                    </div>
                @endforelse
            </div>

            <!-- View all events CTA -->
            <div class="mt-10 text-center">
                <a href="{{ route('events') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 text-sm font-semibold transition">
                    <span>Explore All Live Events</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>

        </div>
    </section>


    <!-- ========================================== -->
    <!-- 3. SELL OUT YOUR NEXT EVENT IN THREE STEPS -->
    <!-- ========================================== -->
    <section class="py-16 md:py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Row -->
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-12">
            <div class="space-y-4 max-w-xl text-left">
                <span class="inline-block text-xs font-bold uppercase tracking-wider text-[#6C5CE7] bg-[#EDE9FE] dark:bg-purple-900/50 px-3 py-1 rounded-full">
                    • FOR ORGANIZERS
                </span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-gray-900 dark:text-white leading-tight">
                    Sell out your next<br/>event in three steps
                </h2>
                <div>
                    <a href="{{ route('register') }}" class="inline-block bg-[#6C5CE7] hover:bg-[#5b48db] text-white font-semibold text-sm px-7 py-3 rounded-full shadow-md shadow-[#6C5CE7]/30 hover:shadow-lg transition">
                        Start selling now
                    </a>
                </div>
            </div>
            
            <div class="lg:max-w-md text-left">
                <p class="text-gray-600 dark:text-gray-400 text-sm sm:text-base leading-relaxed">
                    From local gigs to stadium tours, Eventify gives you the tools to create, manage, and scale your ticket sales with ease.
                </p>
            </div>
        </div>

        <!-- 3 Step Cards Grid (Responsive Grid) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
            
            <!-- Step 1 Card -->
            <div class="bg-gradient-to-b from-[#F5F3FF] via-white to-white dark:from-gray-800/80 dark:via-gray-800 dark:to-gray-800 rounded-3xl p-6 sm:p-7 border border-[#E9D5FF]/60 dark:border-gray-700 shadow-sm flex flex-col justify-between overflow-hidden relative group hover:shadow-xl hover:border-[#6C5CE7]/40 transition-all duration-300">
                <div class="space-y-4 text-left">
                    <div class="w-10 h-10 rounded-full bg-[#EDE9FE] dark:bg-purple-900/60 text-[#6C5CE7] dark:text-purple-300 font-bold flex items-center justify-center text-base">
                        1
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Create your vendor account</h3>
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                        Sign up in 2 minutes, verify your identity if you represent an organization or solo artist and you are ready to go.
                    </p>
                </div>
                
                <!-- Phone Mockup 1 -->
                <div class="mt-8 pt-4 flex justify-center -mb-10 group-hover:-translate-y-2 transition-transform duration-300">
                    <div class="w-56 sm:w-60 rounded-t-3xl overflow-hidden shadow-2xl border-4 border-b-0 border-gray-900 bg-white">
                        <img src="{{ asset('uploads/a0e119fe69f66a788f1ab7633889c4b94d308eaf.png') }}" alt="Step 1 Vendor Mockup" class="w-full object-cover object-top" />
                    </div>
                </div>
            </div>

            <!-- Step 2 Card -->
            <div class="bg-gradient-to-b from-[#F5F3FF] via-white to-white dark:from-gray-800/80 dark:via-gray-800 dark:to-gray-800 rounded-3xl p-6 sm:p-7 border border-[#E9D5FF]/60 dark:border-gray-700 shadow-sm flex flex-col justify-between overflow-hidden relative group hover:shadow-xl hover:border-[#6C5CE7]/40 transition-all duration-300">
                <div class="space-y-4 text-left">
                    <div class="w-10 h-10 rounded-full bg-[#EDE9FE] dark:bg-purple-900/60 text-[#6C5CE7] dark:text-purple-300 font-bold flex items-center justify-center text-base">
                        2
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Set up your event</h3>
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                        Add your event details, set ticket tiers and pricing, customize seat maps and upload promotional posters.
                    </p>
                </div>
                
                <!-- Phone Mockup 2: Interactive Styled Card -->
                <div class="mt-8 pt-4 flex justify-center -mb-10 group-hover:-translate-y-2 transition-transform duration-300">
                    <div class="w-56 sm:w-60 rounded-t-3xl overflow-hidden shadow-2xl border-4 border-b-0 border-gray-900 bg-white dark:bg-gray-900 p-3 space-y-3">
                        <div class="h-24 rounded-xl overflow-hidden relative">
                            <img src="{{ asset('uploads/a9e3088f2698f4b567d9a1c8e03939eaf4410e02.png') }}" alt="Event setup banner" class="w-full h-full object-cover" />
                            <div class="absolute bottom-1 left-2 bg-[#6C5CE7] text-white text-[9px] font-bold px-2 py-0.5 rounded-full">
                                🎵 Music Concert
                            </div>
                        </div>
                        <div class="space-y-1.5 text-left">
                            <div class="h-2 bg-gray-200 dark:bg-gray-700 rounded-full w-3/4"></div>
                            <div class="h-2 bg-gray-100 dark:bg-gray-800 rounded-full w-1/2"></div>
                        </div>
                        <div class="bg-[#F5F3FF] dark:bg-gray-800 p-2 rounded-xl flex items-center justify-between text-[10px]">
                            <span class="font-bold text-gray-800 dark:text-gray-200">VIP Ticket</span>
                            <span class="font-bold text-[#6C5CE7]">Rs. 2,500</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3 Card -->
            <div class="bg-gradient-to-b from-[#F5F3FF] via-white to-white dark:from-gray-800/80 dark:via-gray-800 dark:to-gray-800 rounded-3xl p-6 sm:p-7 border border-[#E9D5FF]/60 dark:border-gray-700 shadow-sm flex flex-col justify-between overflow-hidden relative group hover:shadow-xl hover:border-[#6C5CE7]/40 transition-all duration-300">
                <div class="space-y-4 text-left">
                    <div class="w-10 h-10 rounded-full bg-[#EDE9FE] dark:bg-purple-900/60 text-[#6C5CE7] dark:text-purple-300 font-bold flex items-center justify-center text-base">
                        3
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Publish and start selling</h3>
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                        Go live with one click. Share your event link, track real-time ticket sales and scan QR tickets at the door.
                    </p>
                </div>
                
                <!-- Phone Mockup 3 -->
                <div class="mt-8 pt-4 flex justify-center -mb-10 group-hover:-translate-y-2 transition-transform duration-300">
                    <div class="w-56 sm:w-60 rounded-t-3xl overflow-hidden shadow-2xl border-4 border-b-0 border-gray-900 bg-white">
                        <img src="{{ asset('uploads/40c6f467804771131403928c8429669b565a97a3.png') }}" alt="Step 3 Sales Dashboard" class="w-full object-cover object-top" />
                    </div>
                </div>
            </div>

        </div>
    </section>


    <!-- ========================================== -->
    <!-- 4. BROWSE BY CATEGORY SECTION (DYNAMIC)    -->
    <!-- ========================================== -->
    <section class="py-16 bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-left mb-8">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Browse by category</h2>
                <p class="text-sm sm:text-base text-gray-500 dark:text-gray-400 mt-1">Find experiences that match your vibe and mood</p>
            </div>

            <!-- 4 Column Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
                
                <!-- Category 1: Concerts -->
                <a href="{{ route('events', ['category' => 'Concert']) }}" class="group block text-left">
                    <div class="h-36 sm:h-48 md:h-52 rounded-2xl overflow-hidden relative bg-gray-100 dark:bg-gray-800 shadow-sm group-hover:shadow-lg transition-all duration-300">
                        <img src="{{ asset('uploads/concert.jpg') }}" alt="Concerts" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                    </div>
                    <div class="mt-3 space-y-1">
                        <span class="inline-block text-[10px] font-extrabold uppercase tracking-wider text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/60 px-2 py-0.5 rounded">
                            MUSIC
                        </span>
                        <h3 class="text-base font-bold text-gray-900 dark:text-white group-hover:text-[#6C5CE7] transition">Concerts</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $categoryCounts['Concert'] ?? 0 }}+ Events</p>
                    </div>
                </a>

                <!-- Category 2: Sports -->
                <a href="{{ route('events', ['category' => 'Sports']) }}" class="group block text-left">
                    <div class="h-36 sm:h-48 md:h-52 rounded-2xl overflow-hidden relative bg-gray-100 dark:bg-gray-800 shadow-sm group-hover:shadow-lg transition-all duration-300">
                        <img src="{{ asset('uploads/27d39cfb3009d08541a1b429a677df002ffec2cd.png') }}" alt="Sports" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                    </div>
                    <div class="mt-3 space-y-1">
                        <span class="inline-block text-[10px] font-extrabold uppercase tracking-wider text-teal-600 dark:text-teal-400 bg-teal-50 dark:bg-teal-950/60 px-2 py-0.5 rounded">
                            SPORT
                        </span>
                        <h3 class="text-base font-bold text-gray-900 dark:text-white group-hover:text-[#6C5CE7] transition">Sports</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $categoryCounts['Sports'] ?? 0 }}+ Events</p>
                    </div>
                </a>

                <!-- Category 3: Theatre & Shows -->
                <a href="{{ route('events', ['category' => 'Theatre']) }}" class="group block text-left">
                    <div class="h-36 sm:h-48 md:h-52 rounded-2xl overflow-hidden relative bg-gray-100 dark:bg-gray-800 shadow-sm group-hover:shadow-lg transition-all duration-300">
                        <img src="{{ asset('uploads/5d6d3c39ed125e58205d5ca13d839919649a3e0c.png') }}" alt="Theatre & Shows" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                    </div>
                    <div class="mt-3 space-y-1">
                        <span class="inline-block text-[10px] font-extrabold uppercase tracking-wider text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/60 px-2 py-0.5 rounded">
                            STAGE
                        </span>
                        <h3 class="text-base font-bold text-gray-900 dark:text-white group-hover:text-[#6C5CE7] transition">Theatre & Shows</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $categoryCounts['Theatre'] ?? 0 }}+ Events</p>
                    </div>
                </a>

                <!-- Category 4: Comedy -->
                <a href="{{ route('events', ['category' => 'Comedy']) }}" class="group block text-left">
                    <div class="h-36 sm:h-48 md:h-52 rounded-2xl overflow-hidden relative bg-gray-100 dark:bg-gray-800 shadow-sm group-hover:shadow-lg transition-all duration-300">
                        <img src="{{ asset('uploads/a25912490f77ee8f15b5eb3e5275ae6c1469a2d9.png') }}" alt="Comedy" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                    </div>
                    <div class="mt-3 space-y-1">
                        <span class="inline-block text-[10px] font-extrabold uppercase tracking-wider text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/60 px-2 py-0.5 rounded">
                            STAND-UP
                        </span>
                        <h3 class="text-base font-bold text-gray-900 dark:text-white group-hover:text-[#6C5CE7] transition">Comedy</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $categoryCounts['Comedy'] ?? 0 }}+ Events</p>
                    </div>
                </a>

            </div>
        </div>
    </section>


    <!-- ========================================== -->
    <!-- 5. TRENDING IN YOUR CITY (DYNAMIC DB)      -->
    <!-- ========================================== -->
    <section class="py-16 bg-[#faf9ff] dark:bg-gray-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header with "See all" link -->
            <div class="flex items-end justify-between mb-8">
                <div class="text-left">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Trending in your city</h2>
                    <p class="text-sm sm:text-base text-gray-500 dark:text-gray-400 mt-1">Popular events near you right now</p>
                </div>
                <a href="{{ route('events') }}" class="text-xs sm:text-sm font-bold text-[#6C5CE7] hover:text-[#5a48e0] flex items-center gap-1 group">
                    <span>See all ({{ $upcomingEvents->count() }})</span>
                    <span class="group-hover:translate-x-1 transition-transform">→</span>
                </a>
            </div>

            <!-- 4 Column Trending Cards from Database -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($trendingEvents as $tEvent)
                    @php
                        $tImage = $tEvent->image ? (file_exists(public_path('uploads/' . $tEvent->image)) ? asset('uploads/' . $tEvent->image) : asset('uploads/concert.jpg')) : asset('uploads/concert.jpg');
                        $tIsSaved = in_array($tEvent->id, $savedEventIds ?? []);
                    @endphp
                    <div onclick="window.location.href='{{ route('events.show', $tEvent->slug ?: $tEvent->id) }}'" class="cursor-pointer bg-white dark:bg-gray-800 rounded-2xl border border-gray-200/80 dark:border-gray-700/80 overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col justify-between group">
                        <div class="h-44 overflow-hidden bg-gray-100 dark:bg-gray-700 relative">
                            <a href="{{ route('events.show', $tEvent->slug ?: $tEvent->id) }}">
                                <img src="{{ $tImage }}" alt="{{ $tEvent->event_name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                            </a>
                            
                            <!-- Save / Favorite Button -->
                            <button 
                                type="button"
                                onclick="toggleSaveEvent(event, {{ $tEvent->id }}, this)"
                                data-save-event-id="{{ $tEvent->id }}"
                                aria-label="{{ $tIsSaved ? 'Remove from saved events' : 'Save this event' }}"
                                title="{{ $tIsSaved ? 'Saved to favorites' : 'Save to favorites' }}"
                                class="save-event-btn absolute top-3 right-3 w-8 h-8 bg-white/95 dark:bg-gray-900/95 backdrop-blur-sm rounded-full flex items-center justify-center shadow-md transition-all duration-200 hover:scale-110 active:scale-90 z-20 group/btn {{ $tIsSaved ? 'text-rose-500' : 'text-gray-600 dark:text-gray-300 hover:text-rose-500' }}"
                            >
                                <svg class="w-3.5 h-3.5 transition-transform duration-200" 
                                     fill="{{ $tIsSaved ? 'currentColor' : 'none' }}" 
                                     stroke="currentColor" 
                                     stroke-width="{{ $tIsSaved ? '0' : '2' }}" 
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </button>
                        </div>
                        <div class="p-4 flex-1 flex flex-col justify-between space-y-4 text-left">
                            <div>
                                <a href="{{ route('events.show', $tEvent->slug ?: $tEvent->id) }}" class="block">
                                    <h3 class="font-bold text-gray-900 dark:text-white text-sm group-hover:text-[#6C5CE7] transition line-clamp-1">
                                        {{ $tEvent->event_name }}
                                    </h3>
                                </a>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-1 line-clamp-1">
                                    <span>📍</span> {{ $tEvent->venue }}
                                </p>
                            </div>
                            <a href="{{ route('events.show', $tEvent->slug ?: $tEvent->id) }}" class="w-full py-2 px-4 rounded-xl bg-[#6C5CE7] hover:bg-[#5b48db] text-white text-xs font-bold text-center block transition shadow-sm">
                                Book Now
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-4 py-8 text-center text-gray-500">
                        No trending events available.
                    </div>
                @endforelse
            </div>
        </div>
    </section>


    <!-- ========================================== -->
    <!-- 6. CTA BANNER: STAY IN THE LOOP            -->
    <!-- ========================================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 mb-10">
        <div class="relative bg-gradient-to-r from-[#5a48e0] via-[#6C5CE7] to-[#8D85EC] rounded-3xl p-8 sm:p-12 lg:p-14 text-white shadow-2xl overflow-hidden">
            
            <!-- Glow circles background -->
            <div class="absolute -right-16 -bottom-16 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -left-16 -top-16 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 grid lg:grid-cols-12 gap-8 items-center">
                
                <!-- Left text -->
                <div class="lg:col-span-7 space-y-3 text-left">
                    <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight leading-tight">
                        Stay in the loop
                    </h2>
                    <p class="text-purple-100 text-sm sm:text-base max-w-lg leading-relaxed">
                        Get early access to tickets, exclusive promo codes and weekly curated event guides delivered straight to your inbox.
                    </p>
                </div>

                <!-- Right Newsletter Box -->
                <div class="lg:col-span-5 space-y-2">
                    <form onsubmit="event.preventDefault(); alert('Thank you for subscribing to Eventify updates!'); this.reset();" class="flex items-center bg-white rounded-full p-1.5 shadow-lg">
                        <div class="pl-3 pr-2 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <input 
                            type="email" 
                            required 
                            placeholder="Enter your email address" 
                            class="w-full bg-transparent text-sm text-gray-800 placeholder-gray-400 border-0 border-none outline-none focus:outline-none focus:ring-0 focus:border-none shadow-none ring-0 pr-2 py-2"
                            style="border: none !important; outline: none !important; box-shadow: none !important;"
                        />
                        <button 
                            type="submit" 
                            class="bg-[#111827] hover:bg-black text-white text-xs sm:text-sm font-bold px-6 py-3 rounded-full transition-all duration-200 flex-shrink-0"
                        >
                            Subscribe
                        </button>
                    </form>
                    <p class="text-[11px] text-purple-200 text-center lg:text-left pl-2">
                        🔒 No spam ever. Unsubscribe anytime with a single click.
                    </p>
                </div>

            </div>
        </div>
    </section>

</div>

<!-- Interactive Category Filter Script -->
<script>
function filterEvents(category, btnElement) {
    // Update active filter button styling
    const buttons = document.querySelectorAll('.event-filter-btn');
    buttons.forEach(btn => {
        btn.classList.remove('bg-[#6C5CE7]', 'text-white', 'shadow-sm');
        btn.classList.add('bg-white', 'dark:bg-gray-800', 'text-gray-700', 'dark:text-gray-300', 'border', 'border-gray-200', 'dark:border-gray-700');
    });

    if (btnElement) {
        btnElement.classList.remove('bg-white', 'dark:bg-gray-800', 'text-gray-700', 'dark:text-gray-300', 'border', 'border-gray-200', 'dark:border-gray-700');
        btnElement.classList.add('bg-[#6C5CE7]', 'text-white', 'shadow-sm');
    }

    // Filter cards
    const cards = document.querySelectorAll('.event-card');
    cards.forEach(card => {
        const cardCat = card.getAttribute('data-category');
        if (category === 'all' || cardCat === category) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>
@endsection