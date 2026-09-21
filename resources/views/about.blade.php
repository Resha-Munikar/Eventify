@extends('layouts.app')

@section('title', 'About Us')

@section('content')

<div class="bg-[#faf9ff] dark:bg-gray-950 text-gray-900 dark:text-gray-100 min-h-screen font-sans overflow-x-hidden">

    <!-- ====================================================== -->
    <!-- 1. HERO SECTION                                        -->
    <!-- ====================================================== -->

    <section class="relative w-full bg-[#d9d4f7] dark:bg-gray-900 py-12 sm:py-16 lg:py-24 overflow-hidden">

        <!-- Soft background decoration -->
        <div class="absolute top-10 left-1/4 w-48 sm:w-72 h-48 sm:h-72 bg-[#8D85EC]/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 right-0 w-56 sm:w-80 h-56 sm:h-80 bg-[#c4b5fd]/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-12 relative z-10">

            <div class="flex flex-col lg:flex-row items-center justify-between gap-10 lg:gap-16">

                <!-- Hero Content -->
                <div class="w-full lg:w-1/2 text-center lg:text-left">

                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm shadow-sm mb-5">
                        <span class="w-2 h-2 rounded-full bg-[#8D85EC] animate-pulse"></span>

                        <span class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-[#6C5CE7] dark:text-[#a78df0]">
                            About Eventify
                        </span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold text-black dark:text-white tracking-tight leading-[1.12]">
                        Discover. Connect.
                        <br>
                        <span class="text-[#6C5CE7] dark:text-[#a78df0]">
                            Experience.
                        </span>
                    </h1>

                    <p class="mt-5 text-sm sm:text-base lg:text-lg text-gray-700 dark:text-gray-300 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                        Eventify brings event organizers and attendees together through one simple platform for discovering and booking events.
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-3 mt-7">

                        <a
                            href="{{ route('events') }}"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-[#8D85EC] hover:bg-[#7a72db] text-white px-6 sm:px-8 py-3 rounded-xl text-sm sm:text-base font-semibold shadow-lg shadow-[#8D85EC]/20 transition-all duration-200"
                        >
                            <iconify-icon icon="solar:compass-bold" class="text-lg sm:text-xl"></iconify-icon>
                            <span>Explore Events</span>
                        </a>

                        <a
                            href="#how-it-works"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-white/90 dark:bg-gray-800 text-gray-800 dark:text-gray-200 hover:bg-white dark:hover:bg-gray-700 px-6 py-3 rounded-xl text-sm sm:text-base font-semibold shadow-sm transition-all duration-200"
                        >
                            <span>How It Works</span>
                            <iconify-icon icon="solar:arrow-right-linear" class="text-lg"></iconify-icon>
                        </a>

                    </div>
                </div>


                <!-- Hero Image -->
                <div class="w-full lg:w-1/2 flex justify-center">

                    <div class="relative w-full max-w-lg aspect-[4/3] rounded-2xl sm:rounded-3xl overflow-hidden shadow-2xl group">

                        <img
                            src="{{ asset('uploads/team.jpg') }}"
                            alt="Eventify Gathering"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                        />

                        <div class="absolute inset-0 bg-gradient-to-t from-black/35 via-transparent to-transparent"></div>

                        <div class="absolute bottom-3 sm:bottom-5 left-3 sm:left-5 right-3 sm:right-5 bg-white/90 dark:bg-gray-900/90 backdrop-blur-md rounded-xl sm:rounded-2xl p-3 sm:p-4 shadow-lg">

                            <div class="flex items-center justify-between gap-3">

                                <div class="flex items-center gap-2 sm:gap-3 min-w-0">

                                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-[#8D85EC]/20 text-[#6C5CE7] dark:text-[#a78df0] flex items-center justify-center text-lg sm:text-xl shrink-0">
                                        <iconify-icon icon="solar:ticket-sale-bold"></iconify-icon>
                                    </div>

                                    <div class="min-w-0">
                                        <p class="text-[10px] sm:text-xs font-semibold text-gray-500 dark:text-gray-400">
                                            Two-Sided Platform
                                        </p>

                                        <p class="text-xs sm:text-sm font-bold text-gray-900 dark:text-white truncate">
                                            Organizers • Attendees
                                        </p>
                                    </div>

                                </div>

                                <span class="hidden xs:inline-flex sm:inline-flex items-center text-[10px] sm:text-xs font-bold text-[#6C5CE7] dark:text-[#a78df0] bg-purple-100 dark:bg-purple-950/60 px-2 sm:px-3 py-1 rounded-full shrink-0">
                                    Live Sync
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- ====================================================== -->
    <!-- 2. OUR STORY / HISTORY                                 -->
    <!-- ====================================================== -->

    <section class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-12 py-14 sm:py-20 lg:py-24">

        <div class="grid lg:grid-cols-12 gap-10 lg:gap-16 items-center">

            <!-- Evolution -->
            <div class="lg:col-span-5 bg-white dark:bg-gray-900 rounded-2xl sm:rounded-3xl p-5 sm:p-7 lg:p-8 shadow-lg shadow-purple-900/5">

                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-50 dark:bg-purple-900/40 text-[#6C5CE7] dark:text-[#a78df0] text-[11px] sm:text-xs font-bold uppercase tracking-wider mb-6">
                    <iconify-icon icon="solar:history-bold"></iconify-icon>
                    <span>Evolution</span>
                </div>


                <div class="space-y-5 sm:space-y-6">

                    <!-- 01 -->
                    <div class="flex items-start gap-4">

                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-[#8D85EC] text-white flex items-center justify-center font-bold text-xs sm:text-sm shrink-0">
                            01
                        </div>

                        <div class="flex-1">
                            <span class="text-[10px] sm:text-xs font-bold text-[#6C5CE7] dark:text-[#a78df0] uppercase">
                                2024 • Conception
                            </span>

                            <h4 class="font-bold text-gray-900 dark:text-white text-base sm:text-lg mt-1">
                                The Spark
                            </h4>

                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 mt-1 leading-relaxed">
                                Identified the need for a unified platform to discover events and manage tickets without fragmented social media posts.
                            </p>
                        </div>

                    </div>


                    <!-- 02 -->
                    <div class="flex items-start gap-4">

                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-[#6C5CE7] text-white flex items-center justify-center font-bold text-xs sm:text-sm shrink-0">
                            02
                        </div>

                        <div class="flex-1">
                            <span class="text-[10px] sm:text-xs font-bold text-[#6C5CE7] dark:text-[#a78df0] uppercase">
                                2025 • Platform Launch
                            </span>

                            <h4 class="font-bold text-gray-900 dark:text-white text-base sm:text-lg mt-1">
                                Core Functionality
                            </h4>

                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 mt-1 leading-relaxed">
                                Developed multi-tier ticketing, organizer dashboards, saved favorites, and digital checkout functionality.
                            </p>
                        </div>

                    </div>


                    <!-- 03 -->
                    <div class="flex items-start gap-4">

                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-purple-600 text-white flex items-center justify-center font-bold text-xs sm:text-sm shrink-0">
                            03
                        </div>

                        <div class="flex-1">
                            <span class="text-[10px] sm:text-xs font-bold text-[#6C5CE7] dark:text-[#a78df0] uppercase">
                                2026 • Expanding Reach
                            </span>

                            <h4 class="font-bold text-gray-900 dark:text-white text-base sm:text-lg mt-1">
                                Connecting Communities
                            </h4>

                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 mt-1 leading-relaxed">
                                Empowering local organizers, venues, and attendees with seamless event discovery and booking.
                            </p>
                        </div>

                    </div>

                </div>

            </div>


            <!-- Story -->
            <div class="lg:col-span-7 text-left">

                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-100 dark:bg-purple-900/40 text-[#6C5CE7] dark:text-[#a78df0] text-[11px] sm:text-xs font-bold uppercase tracking-wider mb-5">
                    <iconify-icon icon="solar:book-bookmark-bold"></iconify-icon>
                    <span>Our Story</span>
                </div>

                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-black dark:text-white tracking-tight leading-tight">
                    Making event discovery and booking effortless for everyone.
                </h2>

                <p class="mt-5 text-sm sm:text-base lg:text-lg text-gray-700 dark:text-gray-300 leading-relaxed">
                    Eventify was created to solve a straightforward yet widespread challenge: discovering great events used to mean searching through disconnected posts, while organizers struggled with complex ticket tracking and manual guest lists.
                </p>

                <p class="mt-4 text-sm sm:text-base lg:text-lg text-gray-700 dark:text-gray-300 leading-relaxed">
                    We built Eventify as a complete two-sided platform. Whether you are an attendee looking for concerts, comedy nights, or sports tournaments — or an organizer ready to publish events and manage tickets — Eventify brings everything together under one cohesive experience.
                </p>


                <div class="grid sm:grid-cols-2 gap-4 mt-7">

                    <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-900 rounded-2xl shadow-sm">

                        <div class="w-9 h-9 rounded-xl bg-purple-100 dark:bg-purple-900/40 text-[#6C5CE7] flex items-center justify-center shrink-0 text-lg">
                            <iconify-icon icon="solar:user-hand-up-bold"></iconify-icon>
                        </div>

                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white text-sm">
                                Organizer Centric
                            </h4>

                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                Tools to create, price, and monitor bookings effortlessly.
                            </p>
                        </div>

                    </div>


                    <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-900 rounded-2xl shadow-sm">

                        <div class="w-9 h-9 rounded-xl bg-purple-100 dark:bg-purple-900/40 text-[#6C5CE7] flex items-center justify-center shrink-0 text-lg">
                            <iconify-icon icon="solar:ticket-bold"></iconify-icon>
                        </div>

                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white text-sm">
                                Easy Attendee Access
                            </h4>

                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                Explore events, save favorites, and book tickets easily.
                            </p>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- ====================================================== -->
    <!-- 3. WHY EVENTIFY                                        -->
    <!-- ====================================================== -->

    <section class="w-full bg-[#F5F2FF] dark:bg-gray-900/60 py-14 sm:py-20 px-5 sm:px-6 lg:px-12">

        <div class="max-w-7xl mx-auto">

            <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-14">

                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white dark:bg-gray-800 text-[#6C5CE7] dark:text-[#a78df0] text-[11px] sm:text-xs font-bold uppercase tracking-wider shadow-sm mb-4">
                    <iconify-icon icon="solar:star-fall-bold"></iconify-icon>
                    <span>Key Benefits</span>
                </div>

                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-black dark:text-white">
                    Why Eventify?
                </h2>

                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-300 mt-3">
                    Designed to serve both event enthusiasts and creators.
                </p>

            </div>


            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 lg:gap-7">

                @php
                    $benefits = [
                        [
                            'icon' => 'solar:magnifer-bold',
                            'title' => 'Discover Events',
                            'text' => 'Find events in one convenient place with search and category filters.'
                        ],
                        [
                            'icon' => 'solar:card-send-bold',
                            'title' => 'Easy Booking',
                            'text' => 'Explore event details, select tickets, and complete your booking easily.'
                        ],
                        [
                            'icon' => 'solar:crown-star-bold',
                            'title' => 'For Organizers',
                            'text' => 'Create, publish, and manage events and ticket types from one platform.'
                        ],
                        [
                            'icon' => 'solar:users-group-two-rounded-bold',
                            'title' => 'Connected Experiences',
                            'text' => 'Bring organizers and attendees together for memorable experiences.'
                        ]
                    ];
                @endphp

                @foreach($benefits as $benefit)

                    <div class="p-6 sm:p-7 bg-white dark:bg-gray-800 rounded-2xl sm:rounded-3xl shadow-md hover:shadow-lg transition-all duration-300 group">

                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-purple-100 dark:bg-purple-900/40 text-[#8D85EC] flex items-center justify-center text-2xl sm:text-3xl mb-5 group-hover:bg-[#8D85EC] group-hover:text-white transition-colors">
                            <iconify-icon icon="{{ $benefit['icon'] }}"></iconify-icon>
                        </div>

                        <h3 class="font-bold text-lg sm:text-xl mb-2 text-gray-900 dark:text-white">
                            {{ $benefit['title'] }}
                        </h3>

                        <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                            {{ $benefit['text'] }}
                        </p>

                    </div>

                @endforeach

            </div>

        </div>

    </section>


    <!-- ====================================================== -->
    <!-- 4. EVENTIFY BY NUMBERS                                -->
    <!-- ====================================================== -->

    @php
        $dbEvents = \App\Models\Event::count();
        $dbCategories = \App\Models\Event::distinct('category')->whereNotNull('category')->count('category');
        $dbVendors = \App\Models\User::where('role', 'vendor')->count();
        $dbBookings = \App\Models\Booking::count() + \App\Models\VenueBooking::count();
    @endphp


    <section class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-12 py-14 sm:py-20">

        <div class="bg-gradient-to-br from-white via-purple-50/50 to-white dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 rounded-2xl sm:rounded-3xl p-6 sm:p-10 lg:p-12 shadow-lg text-center">

            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-100 dark:bg-purple-900/50 text-[#6C5CE7] dark:text-[#a78df0] text-[11px] sm:text-xs font-bold uppercase tracking-wider mb-4">
                <iconify-icon icon="solar:chart-square-bold"></iconify-icon>
                <span>Platform Metrics</span>
            </div>

            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-black dark:text-white">
                Eventify by Numbers
            </h2>

            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-300 max-w-xl mx-auto mt-3 mb-8 sm:mb-10">
                Real-time activity across the Eventify platform.
            </p>


            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">

                <!-- Events -->
                <div class="p-5 sm:p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-sm">

                    <div class="w-10 h-10 mx-auto mb-3 rounded-xl bg-purple-100 dark:bg-purple-900/40 text-[#8D85EC] flex items-center justify-center text-xl">
                        <iconify-icon icon="solar:calendar-bold"></iconify-icon>
                    </div>

                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#6C5CE7] dark:text-[#a78df0]">
                        <span class="counter" data-target="{{ $dbEvents }}">0</span>+
                    </h3>

                    <p class="text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-300 mt-2">
                        Events Listed
                    </p>

                </div>


                <!-- Categories -->
                <div class="p-5 sm:p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-sm">

                    <div class="w-10 h-10 mx-auto mb-3 rounded-xl bg-purple-100 dark:bg-purple-900/40 text-[#8D85EC] flex items-center justify-center text-xl">
                        <iconify-icon icon="solar:widget-bold"></iconify-icon>
                    </div>

                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#6C5CE7] dark:text-[#a78df0]">
                        <span class="counter" data-target="{{ $dbCategories }}">0</span>
                    </h3>

                    <p class="text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-300 mt-2">
                        Categories
                    </p>

                </div>


                <!-- Vendors -->
                <div class="p-5 sm:p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-sm">

                    <div class="w-10 h-10 mx-auto mb-3 rounded-xl bg-purple-100 dark:bg-purple-900/40 text-[#8D85EC] flex items-center justify-center text-xl">
                        <iconify-icon icon="solar:user-bold"></iconify-icon>
                    </div>

                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#6C5CE7] dark:text-[#a78df0]">
                        <span class="counter" data-target="{{ $dbVendors }}">0</span>+
                    </h3>

                    <p class="text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-300 mt-2">
                        Event Organizers
                    </p>

                </div>


                <!-- Bookings -->
                <div class="p-5 sm:p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-sm">

                    <div class="w-10 h-10 mx-auto mb-3 rounded-xl bg-purple-100 dark:bg-purple-900/40 text-[#8D85EC] flex items-center justify-center text-xl">
                        <iconify-icon icon="solar:ticket-sale-bold"></iconify-icon>
                    </div>

                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#6C5CE7] dark:text-[#a78df0]">
                        <span class="counter" data-target="{{ $dbBookings }}">0</span>+
                    </h3>

                    <p class="text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-300 mt-2">
                        Bookings
                    </p>

                </div>

            </div>

        </div>

    </section>


    <!-- ====================================================== -->
    <!-- 5. HOW EVENTIFY WORKS                                  -->
    <!-- ====================================================== -->

    <section
        id="how-it-works"
        x-data="{ activeWorkflow: 'attendees' }"
        class="w-full bg-[#faf9ff] dark:bg-gray-950 py-14 sm:py-20 px-5 sm:px-6 lg:px-12 overflow-hidden"
    >

        <div class="max-w-7xl mx-auto">

            <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-12">

                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-100 dark:bg-purple-900/40 text-[#6C5CE7] dark:text-[#a78df0] text-[11px] sm:text-xs font-bold uppercase tracking-wider mb-4">
                    <iconify-icon icon="solar:route-bold"></iconify-icon>
                    <span>Simple Process</span>
                </div>

                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-black dark:text-white">
                    How Eventify Works
                </h2>

                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-300 mt-3">
                    Simple steps for attendees and event organizers.
                </p>


                <!-- Toggle -->
                <div class="inline-flex p-1 rounded-full bg-white dark:bg-gray-900 shadow-sm mt-6">

                    <button
                        type="button"
                        @click="activeWorkflow = 'attendees'"
                        :class="activeWorkflow === 'attendees'
                            ? 'bg-[#8D85EC] text-white shadow-md'
                            : 'text-gray-600 dark:text-gray-400'"
                        class="px-4 sm:px-6 py-2 rounded-full font-bold text-xs sm:text-sm transition-all duration-200"
                    >
                        For Attendees
                    </button>

                    <button
                        type="button"
                        @click="activeWorkflow = 'vendors'"
                        :class="activeWorkflow === 'vendors'
                            ? 'bg-[#8D85EC] text-white shadow-md'
                            : 'text-gray-600 dark:text-gray-400'"
                        class="px-4 sm:px-6 py-2 rounded-full font-bold text-xs sm:text-sm transition-all duration-200"
                    >
                        For Organizers
                    </button>

                </div>

            </div>


            <!-- ================================================= -->
            <!-- ATTENDEE WORKFLOW                                 -->
            <!-- ================================================= -->

            <div
                x-show="activeWorkflow === 'attendees'"
                x-transition
                class="relative"
            >

                <!-- Horizontal workflow -->
                <div class="flex overflow-x-auto snap-x snap-mandatory pb-4 md:overflow-visible scrollbar-hide">

                    <div class="min-w-[270px] sm:min-w-[300px] md:min-w-0 md:w-1/4 snap-start text-center px-3 sm:px-4 relative">

                        <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto rounded-full bg-white dark:bg-gray-900 shadow-lg flex items-center justify-center text-2xl sm:text-3xl text-[#6C5CE7]">
            <iconify-icon icon="solar:smartphone-2-linear"></iconify-icon>
        </div>

                        <div class="mt-5">
                            <span class="text-xs font-bold text-[#8D85EC]">01</span>

                            <h4 class="font-bold text-base sm:text-lg mt-1 text-gray-900 dark:text-white">
                                Discover Events
                            </h4>

                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-2 max-w-[220px] mx-auto">
                                Browse concerts, sports, comedy, and festivals in one simple place.
                            </p>
                        </div>

                    </div>


                    <div class="min-w-[270px] sm:min-w-[300px] md:min-w-0 md:w-1/4 snap-start text-center px-3 sm:px-4 relative">

                        <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto rounded-full bg-white dark:bg-gray-900 shadow-lg flex items-center justify-center text-2xl sm:text-3xl text-[#6C5CE7]">
                            <iconify-icon icon="solar:document-text-linear"></iconify-icon>
                        </div>

                        <div class="mt-5">
                            <span class="text-xs font-bold text-[#8D85EC]">02</span>

                            <h4 class="font-bold text-base sm:text-lg mt-1 text-gray-900 dark:text-white">
                                Explore Details
                            </h4>

                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-2 max-w-[220px] mx-auto">
                                Check dates, venue information, artists, ticket options, and event details.
                            </p>
                        </div>

                    </div>


                    <div class="min-w-[270px] sm:min-w-[300px] md:min-w-0 md:w-1/4 snap-start text-center px-3 sm:px-4 relative">

                        <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto rounded-full bg-white dark:bg-gray-900 shadow-lg flex items-center justify-center text-2xl sm:text-3xl text-[#6C5CE7]">
                            <iconify-icon icon="solar:ticket-sale-linear"></iconify-icon>
                        </div>

                        <div class="mt-5">
                            <span class="text-xs font-bold text-[#8D85EC]">03</span>

                            <h4 class="font-bold text-base sm:text-lg mt-1 text-gray-900 dark:text-white">
                                Choose & Book
                            </h4>

                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-2 max-w-[220px] mx-auto">
                                Select your preferred ticket and complete the booking process.
                            </p>
                        </div>

                    </div>


                    <div class="min-w-[270px] sm:min-w-[300px] md:min-w-0 md:w-1/4 snap-start text-center px-3 sm:px-4 relative">

                        <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto rounded-full bg-white dark:bg-gray-900 shadow-lg flex items-center justify-center text-2xl sm:text-3xl text-[#6C5CE7]">
                            <iconify-icon icon="solar:check-circle-linear"></iconify-icon>
                        </div>

                        <div class="mt-5">
                            <span class="text-xs font-bold text-[#8D85EC]">04</span>

                            <h4 class="font-bold text-base sm:text-lg mt-1 text-gray-900 dark:text-white">
                                Experience
                            </h4>

                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-2 max-w-[220px] mx-auto">
                                Receive your booking confirmation and enjoy your event.
                            </p>
                        </div>

                    </div>

                </div>

            </div>


            <!-- ================================================= -->
            <!-- ORGANIZER WORKFLOW                                -->
            <!-- ================================================= -->

            <div
                x-show="activeWorkflow === 'vendors'"
                x-cloak
                x-transition
                class="relative"
            >

                <div class="flex overflow-x-auto snap-x snap-mandatory pb-4 md:overflow-visible scrollbar-hide">

                    <!-- Step 1 -->
                    <div class="min-w-[270px] sm:min-w-[300px] md:min-w-0 md:w-1/4 snap-start text-center px-3 sm:px-4">

                        <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto rounded-full bg-white dark:bg-gray-900 shadow-lg flex items-center justify-center text-2xl sm:text-3xl text-[#6C5CE7]">
                            <iconify-icon icon="solar:pen-new-square-linear"></iconify-icon>
                        </div>

                        <div class="mt-5">
                            <span class="text-xs font-bold text-[#8D85EC]">01</span>

                            <h4 class="font-bold text-base sm:text-lg mt-1 text-gray-900 dark:text-white">
                                Create Event
                            </h4>

                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-2 max-w-[220px] mx-auto">
                                Add your event title, description, venue, and cover artwork.
                            </p>
                        </div>

                    </div>


                    <!-- Step 2 -->
                    <div class="min-w-[270px] sm:min-w-[300px] md:min-w-0 md:w-1/4 snap-start text-center px-3 sm:px-4">

                        <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto rounded-full bg-white dark:bg-gray-900 shadow-lg flex items-center justify-center text-2xl sm:text-3xl text-[#6C5CE7]">
                            <iconify-icon icon="solar:tag-price-linear"></iconify-icon>
                        </div>

                        <div class="mt-5">
                            <span class="text-xs font-bold text-[#8D85EC]">02</span>

                            <h4 class="font-bold text-base sm:text-lg mt-1 text-gray-900 dark:text-white">
                                Set Tickets
                            </h4>

                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-2 max-w-[220px] mx-auto">
                                Configure ticket types, pricing, and available quantities.
                            </p>
                        </div>

                    </div>


                    <!-- Step 3 -->
                    <div class="min-w-[270px] sm:min-w-[300px] md:min-w-0 md:w-1/4 snap-start text-center px-3 sm:px-4">

                        <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto rounded-full bg-white dark:bg-gray-900 shadow-lg flex items-center justify-center text-2xl sm:text-3xl text-[#6C5CE7]">
                            <iconify-icon icon="solar:rocket-2-linear"></iconify-icon>
                        </div>

                        <div class="mt-5">
                            <span class="text-xs font-bold text-[#8D85EC]">03</span>

                            <h4 class="font-bold text-base sm:text-lg mt-1 text-gray-900 dark:text-white">
                                Publish
                            </h4>

                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-2 max-w-[220px] mx-auto">
                                Publish your event and make it available to Eventify attendees.
                            </p>
                        </div>

                    </div>


                    <!-- Step 4 -->
                    <div class="min-w-[270px] sm:min-w-[300px] md:min-w-0 md:w-1/4 snap-start text-center px-3 sm:px-4">

                        <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto rounded-full bg-white dark:bg-gray-900 shadow-lg flex items-center justify-center text-2xl sm:text-3xl text-[#6C5CE7]">
                            <iconify-icon icon="solar:chart-2-linear"></iconify-icon>
                        </div>

                        <div class="mt-5">
                            <span class="text-xs font-bold text-[#8D85EC]">04</span>

                            <h4 class="font-bold text-base sm:text-lg mt-1 text-gray-900 dark:text-white">
                                Manage Bookings
                            </h4>

                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-2 max-w-[220px] mx-auto">
                                Track registrations, bookings, and attendee activity.
                            </p>
                        </div>

                    </div>

                </div>

            </div>


            <!-- Mobile scroll hint -->
            <p class="md:hidden text-center text-xs text-gray-400 dark:text-gray-500 mt-5">
                ← Swipe to view all steps →
            </p>

        </div>

    </section>


    <!-- ====================================================== -->
    <!-- 6. BUILT FOR EVERYONE                                  -->
    <!-- ====================================================== -->

    <section class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-12 py-14 sm:py-20">

        <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-14">

            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-100 dark:bg-purple-900/40 text-[#6C5CE7] dark:text-[#a78df0] text-[11px] sm:text-xs font-bold uppercase tracking-wider mb-4">
                <iconify-icon icon="solar:users-group-rounded-bold"></iconify-icon>
                <span>Platform Roles</span>
            </div>

            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-black dark:text-white">
                Built for Everyone
            </h2>

            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-300 mt-3">
                Simple tools for attendees and event organizers.
            </p>

        </div>


        <div class="grid md:grid-cols-2 gap-5 lg:gap-8">

            <!-- Attendees -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl sm:rounded-3xl p-6 sm:p-8 lg:p-10 shadow-lg flex flex-col justify-between">

                <div>

                    <div class="w-14 h-14 rounded-2xl bg-purple-100 dark:bg-purple-900/40 text-[#8D85EC] flex items-center justify-center text-3xl mb-5">
                        <iconify-icon icon="solar:ticket-bold"></iconify-icon>
                    </div>

                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-3">
                        For Event Attendees
                    </h3>

                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">
                        Discover events and book your place in just a few clicks.
                    </p>

                    <ul class="space-y-3 mb-8 text-sm text-gray-700 dark:text-gray-300">

                        <li class="flex items-center gap-3">
                            <iconify-icon icon="solar:check-circle-bold" class="text-[#8D85EC] text-lg shrink-0"></iconify-icon>
                            <span>Discover events</span>
                        </li>

                        <li class="flex items-center gap-3">
                            <iconify-icon icon="solar:check-circle-bold" class="text-[#8D85EC] text-lg shrink-0"></iconify-icon>
                            <span>Search and filter events</span>
                        </li>

                        <li class="flex items-center gap-3">
                            <iconify-icon icon="solar:check-circle-bold" class="text-[#8D85EC] text-lg shrink-0"></iconify-icon>
                            <span>Save events</span>
                        </li>

                        <li class="flex items-center gap-3">
                            <iconify-icon icon="solar:check-circle-bold" class="text-[#8D85EC] text-lg shrink-0"></iconify-icon>
                            <span>View event details</span>
                        </li>

                        <li class="flex items-center gap-3">
                            <iconify-icon icon="solar:check-circle-bold" class="text-[#8D85EC] text-lg shrink-0"></iconify-icon>
                            <span>Book tickets</span>
                        </li>

                    </ul>

                </div>


                <a
                    href="{{ route('events') }}"
                    class="w-full inline-flex items-center justify-center gap-2 bg-[#8D85EC] hover:bg-[#7a72db] text-white py-3 px-6 rounded-xl font-semibold shadow-md transition"
                >
                    <span>Explore Events</span>
                    <iconify-icon icon="solar:arrow-right-linear"></iconify-icon>
                </a>

            </div>


            <!-- Organizers -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl sm:rounded-3xl p-6 sm:p-8 lg:p-10 shadow-lg flex flex-col justify-between">

                <div>

                    <div class="w-14 h-14 rounded-2xl bg-purple-100 dark:bg-purple-900/40 text-[#8D85EC] flex items-center justify-center text-3xl mb-5">
                        <iconify-icon icon="solar:buildings-bold"></iconify-icon>
                    </div>

                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-3">
                        For Event Organizers
                    </h3>

                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">
                        Create, publish, and manage your events from one platform.
                    </p>

                    <ul class="space-y-3 mb-8 text-sm text-gray-700 dark:text-gray-300">

                        <li class="flex items-center gap-3">
                            <iconify-icon icon="solar:check-circle-bold" class="text-[#8D85EC] text-lg shrink-0"></iconify-icon>
                            <span>Create event listings</span>
                        </li>

                        <li class="flex items-center gap-3">
                            <iconify-icon icon="solar:check-circle-bold" class="text-[#8D85EC] text-lg shrink-0"></iconify-icon>
                            <span>Configure ticket types</span>
                        </li>

                        <li class="flex items-center gap-3">
                            <iconify-icon icon="solar:check-circle-bold" class="text-[#8D85EC] text-lg shrink-0"></iconify-icon>
                            <span>Publish events</span>
                        </li>

                        <li class="flex items-center gap-3">
                            <iconify-icon icon="solar:check-circle-bold" class="text-[#8D85EC] text-lg shrink-0"></iconify-icon>
                            <span>Manage bookings</span>
                        </li>

                        <li class="flex items-center gap-3">
                            <iconify-icon icon="solar:check-circle-bold" class="text-[#8D85EC] text-lg shrink-0"></iconify-icon>
                            <span>Reach attendees</span>
                        </li>

                    </ul>

                </div>


                @auth

                    @if(Auth::user()->role === 'vendor')

                        <a
                            href="{{ route('vendor.events.create') }}"
                            class="w-full inline-flex items-center justify-center gap-2 bg-[#6C5CE7] hover:bg-[#5b48db] text-white py-3 px-6 rounded-xl font-semibold shadow-md transition"
                        >
                            <span>List Your Event</span>
                            <iconify-icon icon="solar:arrow-right-linear"></iconify-icon>
                        </a>

                    @else

                        <a
                            href="{{ route('vendor.events.index') }}"
                            class="w-full inline-flex items-center justify-center gap-2 bg-[#6C5CE7] hover:bg-[#5b48db] text-white py-3 px-6 rounded-xl font-semibold shadow-md transition"
                        >
                            <span>Organizer Portal</span>
                            <iconify-icon icon="solar:arrow-right-linear"></iconify-icon>
                        </a>

                    @endif

                @else

                    <a
                        href="{{ route('register') }}"
                        class="w-full inline-flex items-center justify-center gap-2 bg-[#6C5CE7] hover:bg-[#5b48db] text-white py-3 px-6 rounded-xl font-semibold shadow-md transition"
                    >
                        <span>List Your Event</span>
                        <iconify-icon icon="solar:arrow-right-linear"></iconify-icon>
                    </a>

                @endauth

            </div>

        </div>

    </section>


    <!-- ====================================================== -->
    <!-- 7. MISSION / VISION / VALUES                           -->
    <!-- ====================================================== -->

    <section class="w-full bg-[#F5F2FF] dark:bg-gray-900/60 py-14 sm:py-20 px-5 sm:px-6 lg:px-12">

        <div class="max-w-7xl mx-auto">

            <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-14">

                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white dark:bg-gray-800 text-[#6C5CE7] dark:text-[#a78df0] text-[11px] sm:text-xs font-bold uppercase tracking-wider shadow-sm mb-4">
                    <iconify-icon icon="solar:shield-star-bold"></iconify-icon>
                    <span>Guiding Principles</span>
                </div>

                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-black dark:text-white">
                    Mission & Vision
                </h2>

            </div>


            <div class="grid md:grid-cols-3 gap-5 lg:gap-8">

                <!-- Mission -->
                <div class="p-6 sm:p-8 bg-white dark:bg-gray-800 rounded-2xl sm:rounded-3xl shadow-md text-center">

                    <div class="w-14 h-14 sm:w-16 sm:h-16 mx-auto mb-5 rounded-2xl bg-purple-100 dark:bg-purple-900/40 flex items-center justify-center text-[#8D85EC] text-2xl sm:text-3xl">
                        <iconify-icon icon="solar:target-bold"></iconify-icon>
                    </div>

                    <h3 class="font-bold text-xl sm:text-2xl mb-3 text-[#8D85EC] dark:text-[#a78df0]">
                        Mission
                    </h3>

                    <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed">
                        To make discovering, organizing, and experiencing events simpler and more accessible for everyone.
                    </p>

                </div>


                <!-- Vision -->
                <div class="p-6 sm:p-8 bg-white dark:bg-gray-800 rounded-2xl sm:rounded-3xl shadow-md text-center">

                    <div class="w-14 h-14 sm:w-16 sm:h-16 mx-auto mb-5 rounded-2xl bg-purple-100 dark:bg-purple-900/40 flex items-center justify-center text-[#8D85EC] text-2xl sm:text-3xl">
                        <iconify-icon icon="solar:eye-bold"></iconify-icon>
                    </div>

                    <h3 class="font-bold text-xl sm:text-2xl mb-3 text-[#8D85EC] dark:text-[#a78df0]">
                        Vision
                    </h3>

                    <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed">
                        To create a trusted platform that connects people with memorable experiences and helps event organizers reach their audience.
                    </p>

                </div>


                <!-- Values -->
                <div class="p-6 sm:p-8 bg-white dark:bg-gray-800 rounded-2xl sm:rounded-3xl shadow-md text-center">

                    <div class="w-14 h-14 sm:w-16 sm:h-16 mx-auto mb-5 rounded-2xl bg-purple-100 dark:bg-purple-900/40 flex items-center justify-center text-[#8D85EC] text-2xl sm:text-3xl">
                        <iconify-icon icon="solar:lightbulb-bold"></iconify-icon>
                    </div>

                    <h3 class="font-bold text-xl sm:text-2xl mb-3 text-[#8D85EC] dark:text-[#a78df0]">
                        Values
                    </h3>

                    <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed">
                        Simplicity, accessibility, community, and reliability are at the foundation of everything we build.
                    </p>

                </div>

            </div>

        </div>

    </section>


    <!-- ====================================================== -->
    <!-- 8. OUR TEAM                                             -->
    <!-- ====================================================== -->

    <section id="team" class="max-w-6xl mx-auto px-5 sm:px-6 lg:px-10 py-14 sm:py-20">

    <!-- Section heading -->
    <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-14">

        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-100 dark:bg-purple-900/40 text-[#6C5CE7] dark:text-[#a78df0] text-[11px] sm:text-xs font-bold uppercase tracking-wider mb-4">
            <iconify-icon icon="solar:users-group-two-rounded-bold"></iconify-icon>
            <span>Meet the Builders</span>
        </div>

        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-black dark:text-white">
            Our Team
        </h2>

        <p class="text-sm sm:text-base text-gray-600 dark:text-gray-300 mt-3">
            Meet the people behind Eventify.
        </p>

    </div>


    <!-- Team members -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 lg:gap-8 max-w-5xl mx-auto">

        <!-- Bristi -->
        <div class="bg-white dark:bg-gray-800 p-4 sm:p-5 rounded-2xl sm:rounded-3xl shadow-md text-center group">

            <div class="w-full aspect-[4/4.5] overflow-hidden rounded-2xl bg-purple-50">
                <img
                    src="{{ asset('uploads/jane1.jpg') }}"
                    alt="Bristi Maharjan"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                />
            </div>

            <h3 class="mt-4 font-bold text-base sm:text-lg text-gray-900 dark:text-white">
                Bristi Maharjan
            </h3>

            <span class="inline-block mt-1 px-3 py-0.5 rounded-full text-xs font-semibold bg-purple-100 dark:bg-purple-950 text-[#8D85EC] dark:text-[#a78df0]">
                CEO / Product Lead
            </span>

            <p class="text-gray-600 dark:text-gray-300 mt-3 text-xs sm:text-sm leading-relaxed">
                Driving Eventify's product direction, user experience, and platform strategy.
            </p>

        </div>


        <!-- Resha -->
        <div class="bg-white dark:bg-gray-800 p-4 sm:p-5 rounded-2xl sm:rounded-3xl shadow-md text-center group">

            <div class="w-full aspect-[4/4.5] overflow-hidden rounded-2xl bg-purple-50">
                <img
                    src="{{ asset('uploads/Sara.jpg') }}"
                    alt="Resha Munikar"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                />
            </div>

            <h3 class="mt-4 font-bold text-base sm:text-lg text-gray-900 dark:text-white">
                Resha Munikar
            </h3>

            <span class="inline-block mt-1 px-3 py-0.5 rounded-full text-xs font-semibold bg-purple-100 dark:bg-purple-950 text-[#8D85EC] dark:text-[#a78df0]">
                CTO / Lead Developer
            </span>

            <p class="text-gray-600 dark:text-gray-300 mt-3 text-xs sm:text-sm leading-relaxed">
                Architecting the core platform, ticket management, and backend functionality.
            </p>

        </div>


        <!-- Sony -->
        <div class="bg-white dark:bg-gray-800 p-4 sm:p-5 rounded-2xl sm:rounded-3xl shadow-md text-center group">

            <div class="w-full aspect-[4/4.5] overflow-hidden rounded-2xl bg-purple-50">
                <img
                    src="{{ asset('uploads/Sara.jpg') }}"
                    alt="Sony Tamang"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                />
            </div>

            <h3 class="mt-4 font-bold text-base sm:text-lg text-gray-900 dark:text-white">
                Sony Tamang
            </h3>

            <span class="inline-block mt-1 px-3 py-0.5 rounded-full text-xs font-semibold bg-purple-100 dark:bg-purple-950 text-[#8D85EC] dark:text-[#a78df0]">
                Marketing & Community
            </span>

            <p class="text-gray-600 dark:text-gray-300 mt-3 text-xs sm:text-sm leading-relaxed">
                Connecting Eventify with organizers, venues, and attendee communities.
            </p>

        </div>

    </div>

</section>

    <!-- ====================================================== -->
    <!-- 9. OUR JOURNEY                                          -->
    <!-- ====================================================== -->

    <section class="w-full bg-[#F5F2FF] dark:bg-gray-900/60 py-14 sm:py-20 px-5 sm:px-6 lg:px-12">

        <div class="max-w-4xl mx-auto">

            <div class="text-center mb-10 sm:mb-14">

                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white dark:bg-gray-800 text-[#6C5CE7] dark:text-[#a78df0] text-[11px] sm:text-xs font-bold uppercase tracking-wider shadow-sm mb-4">
                    <iconify-icon icon="solar:route-bold"></iconify-icon>
                    <span>Milestones</span>
                </div>

                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-black dark:text-white">
                    Our Journey
                </h2>

                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-300 mt-3">
                    How Eventify has evolved and where we are heading next.
                </p>

            </div>


            <div class="space-y-5 sm:space-y-6">

                <!-- 01 -->
                <div class="flex gap-4 sm:gap-5 items-start">

                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-[#8D85EC] text-white flex items-center justify-center font-bold text-xs sm:text-sm shrink-0">
                        01
                    </div>

                    <div class="flex-1 bg-white dark:bg-gray-800 p-5 sm:p-6 rounded-2xl shadow-sm">

                        <h3 class="text-base sm:text-xl font-bold text-gray-900 dark:text-white">
                            The Idea
                        </h3>

                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-2 leading-relaxed">
                            Eventify started with the idea of making event discovery and management easier, eliminating disjointed event posts and manual ticketing.
                        </p>

                    </div>

                </div>


                <!-- 02 -->
                <div class="flex gap-4 sm:gap-5 items-start">

                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-[#8D85EC] text-white flex items-center justify-center font-bold text-xs sm:text-sm shrink-0">
                        02
                    </div>

                    <div class="flex-1 bg-white dark:bg-gray-800 p-5 sm:p-6 rounded-2xl shadow-sm">

                        <h3 class="text-base sm:text-xl font-bold text-gray-900 dark:text-white">
                            First Steps
                        </h3>

                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-2 leading-relaxed">
                            Development of the platform and core event functionality, enabling organizers to add event listings and customize ticket categories.
                        </p>

                    </div>

                </div>


                <!-- 03 -->
                <div class="flex gap-4 sm:gap-5 items-start">

                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-[#8D85EC] text-white flex items-center justify-center font-bold text-xs sm:text-sm shrink-0">
                        03
                    </div>

                    <div class="flex-1 bg-white dark:bg-gray-800 p-5 sm:p-6 rounded-2xl shadow-sm">

                        <h3 class="text-base sm:text-xl font-bold text-gray-900 dark:text-white">
                            Growing Together
                        </h3>

                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-2 leading-relaxed">
                            Development of vendor and attendee workflows, including saved favorites, booking records, reports, and search filters.
                        </p>

                    </div>

                </div>


                <!-- 04 -->
                <div class="flex gap-4 sm:gap-5 items-start">

                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-[#6C5CE7] text-white flex items-center justify-center font-bold text-xs sm:text-sm shrink-0">
                        04
                    </div>

                    <div class="flex-1 bg-white dark:bg-gray-800 p-5 sm:p-6 rounded-2xl shadow-sm">

                        <h3 class="text-base sm:text-xl font-bold text-gray-900 dark:text-white">
                            Future Ahead
                        </h3>

                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-2 leading-relaxed">
                            Continuing to improve Eventify as an event discovery and booking platform.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- ====================================================== -->
    <!-- 10. FAQ                                                 -->
    <!-- ====================================================== -->

    


    <!-- ====================================================== -->
    <!-- 11. FINAL CTA                                           -->
    <!-- ====================================================== -->

    <section class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-12 pb-12 sm:pb-16">

        <div class="relative bg-gradient-to-r from-[#5a48e0] via-[#6C5CE7] to-[#8D85EC] rounded-2xl sm:rounded-3xl p-7 sm:p-10 lg:p-14 text-white shadow-xl overflow-hidden text-center">

            <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -left-20 -top-20 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>


            <div class="relative z-10 max-w-2xl mx-auto">

                <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold tracking-tight leading-tight">
                    Ready to Experience More?
                </h2>

                <p class="text-purple-100 text-sm sm:text-base lg:text-lg leading-relaxed mt-4">
                    Discover your next event or bring your event to life with Eventify.
                </p>


                <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4 mt-7">

                    <a
                        href="{{ route('events') }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-white text-[#6C5CE7] hover:bg-gray-100 px-6 sm:px-8 py-3 rounded-xl font-bold shadow-lg transition"
                    >
                        <iconify-icon icon="solar:ticket-sale-bold" class="text-xl"></iconify-icon>
                        <span>Explore Events</span>
                    </a>


                    @auth

                        @if(Auth::user()->role === 'vendor')

                            <a
                                href="{{ route('vendor.events.create') }}"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-[#111827] hover:bg-black text-white px-6 sm:px-8 py-3 rounded-xl font-bold shadow-lg transition"
                            >
                                <iconify-icon icon="solar:add-circle-bold" class="text-xl"></iconify-icon>
                                <span>List Your Event</span>
                            </a>

                        @else

                            <a
                                href="{{ route('contact') }}"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-[#111827] hover:bg-black text-white px-6 sm:px-8 py-3 rounded-xl font-bold shadow-lg transition"
                            >
                                <iconify-icon icon="solar:letter-bold" class="text-xl"></iconify-icon>
                                <span>Contact Us</span>
                            </a>

                        @endif

                    @else

                        <a
                            href="{{ route('register') }}"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-[#111827] hover:bg-black text-white px-6 sm:px-8 py-3 rounded-xl font-bold shadow-lg transition"
                        >
                            <iconify-icon icon="solar:user-plus-bold" class="text-xl"></iconify-icon>
                            <span>List Your Event</span>
                        </a>

                    @endauth

                </div>

            </div>

        </div>

    </section>

</div>


<!-- ====================================================== -->
<!-- INTERACTIVE SCRIPTS                                    -->
<!-- ====================================================== -->

<script>

document.addEventListener('DOMContentLoaded', () => {

    /* =====================================================
       COUNTERS
    ===================================================== */

    const counters = document.querySelectorAll('.counter');

    let animated = false;

    const animateCounters = () => {

        counters.forEach(counter => {

            const target = Number(counter.getAttribute('data-target'));

            if (target <= 0) {
                counter.innerText = '0';
                return;
            }

            const duration = 1000;
            const steps = 35;
            const increment = Math.max(1, Math.ceil(target / steps));

            let current = 0;

            const timer = setInterval(() => {

                current += increment;

                if (current >= target) {

                    counter.innerText = target;
                    clearInterval(timer);

                } else {

                    counter.innerText = current;

                }

            }, duration / steps);

        });

    };


    const statsSection = document.querySelector('.counter')?.closest('section');

    if (statsSection && 'IntersectionObserver' in window) {

        const observer = new IntersectionObserver((entries) => {

            entries.forEach(entry => {

                if (entry.isIntersecting && !animated) {

                    animated = true;
                    animateCounters();

                }

            });

        }, {
            threshold: 0.2
        });

        observer.observe(statsSection);

    } else {

        animateCounters();

    }


    /* =====================================================
       FAQ ACCORDION
    ===================================================== */

    const faqButtons = document.querySelectorAll('#faq-accordion button');

    faqButtons.forEach(button => {

        button.addEventListener('click', () => {

            const panel = button.nextElementSibling;

            const isExpanded =
                button.getAttribute('aria-expanded') === 'true';


            /* Close every other FAQ */

            faqButtons.forEach(btn => {

                btn.setAttribute('aria-expanded', 'false');

                if (btn.nextElementSibling) {
                    btn.nextElementSibling.style.maxHeight = null;
                }

                const icon = btn.querySelector('svg');

                if (icon) {
                    icon.classList.remove('rotate-180');
                }

            });


            /* Open selected FAQ */

            if (!isExpanded) {

                button.setAttribute('aria-expanded', 'true');

                if (panel) {
                    panel.style.maxHeight = panel.scrollHeight + 'px';
                }

                const icon = button.querySelector('svg');

                if (icon) {
                    icon.classList.add('rotate-180');
                }

            }

        });

    });

});

</script>

@endsection