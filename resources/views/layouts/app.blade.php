<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@hasSection('title')@yield('title') - {{ config('app.name', 'Eventify') }}@else{{ config('app.name', 'Eventify') }}@endif</title>
    <link rel="icon" type="image/png" href="{{ asset('images/eventify-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/eventify-logo.png') }}">
    @vite('resources/css/app.css') {{-- or your CSS build setup --}}
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet" />
    <style>
              /* Your custom styles */
        .brand-color { background-color: #8d85ec; }
        .brand-text { color: #8d85ec; }
        .brand-logo { font-family: 'Pacifico', cursive; }
        nav a.active { color: #8d85ec !important; font-weight: 600; }

        /* Hide scrollbar for all modern browsers */
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        [x-cloak] { display: none !important; }
    </style>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>

</head>
<body class="flex flex-col min-h-screen bg-gray-50 dark:bg-gray-900 text-black dark:text-white">

    {{-- Navbar only shows if $noNavbar is not set or false --}}
    @if (!isset($noNavbar) || !$noNavbar)
        <!-- Navbar -->
        <header x-data="{ mobileMenuOpen: false }" class="w-full bg-[#8D85EC] dark:bg-gray-900 shadow-md">
        <div class="max-w-7xl mx-auto flex justify-between items-center gap-4 px-4 sm:px-6 lg:px-8 py-3 sm:py-4">
            <!-- Logo + Title -->
            <a href="{{ route('home') }}" class="flex items-center space-x-2 sm:space-x-4 hover:opacity-90 transition focus:outline-none" title="Eventify Home">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white dark:bg-gray-700 rounded-full flex items-center justify-center overflow-hidden shadow-sm">
                    <img src="{{ asset('images/eventify-logo.png') }}" alt="Eventify Logo" class="w-7 h-7 sm:w-8 sm:h-8 object-contain" />
                </div>
                <span class="text-black dark:text-white text-3xl sm:text-4xl brand-logo">Eventify</span>
            </a>
            <nav class="hidden lg:flex bg-white dark:bg-gray-700 rounded-full px-6 xl:px-8 py-3 shadow-md justify-center">
               <a href="{{ route('home') }}" 
                    class="text-black dark:text-white font-semibold hover:underline mx-4 
                    {{ request()->routeIs('home') || request()->routeIs('welcome') || request()->is('/') ? 'active' : '' }}">
                    Home
                </a>

                <a href="{{ route('about') }}" 
                class="text-black dark:text-white font-semibold hover:underline mx-4 {{ request()->routeIs('about') ? 'active' : '' }}">
                About Us
                </a>

                <a href="{{ route('events') }}" 
                class="text-black dark:text-white font-semibold hover:underline mx-4 {{ request()->routeIs('events') ? 'active' : '' }}">
                Events
                </a>
                <a href="{{ route('contact') }}" 
                class="text-black dark:text-white font-semibold hover:underline mx-4 {{ request()->routeIs('contact') ? 'active' : '' }}">
                Contact
                </a>
            </nav>

            <!-- Navbar Right Section -->
            <div class="flex items-center space-x-2 sm:space-x-4 relative">
                <button
                    type="button"
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    :aria-expanded="mobileMenuOpen.toString()"
                    aria-label="Toggle navigation menu"
                    class="lg:hidden p-2 rounded-lg bg-white/90 text-gray-800 hover:bg-white focus:outline-none focus:ring-2 focus:ring-white"
                >
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                @guest
                    <!-- When user is NOT logged in -->
                    <a href="{{ route('login') }}" class="bg-white dark:bg-gray-700 text-[#8D85EC] dark:text-white font-semibold px-5 py-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600 transition"> Login </a> 
                    <a href="{{ route('register') }}" class="bg-[#7b76e4] text-white dark:bg-gray-700 font-semibold px-5 py-2 rounded-full hover:bg-[#6f69d9] transition"> Sign Up </a>
                @endguest

                @auth
                <div x-data="{ open: false }" class="relative">
                    <!-- Profile button -->
                    <button @click="open = !open" class="flex items-center focus:outline-none">
                        <img src="{{ Auth::user()->profile_image ?? asset('uploads/avatar.jpg') }}"
                            alt="Profile"
                            class="w-8 h-8 rounded-full border-2 border-purple-500 hover:border-purple-700 transition">
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open" @click.away="open = false" 
                        class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-xl py-2 z-50 transition duration-200"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-95">

                        <!-- Profile link -->
                        <a href="{{ route('profile') }}"
                          class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-purple-100 dark:hover:bg-purple-700 rounded-lg transition">
                            <svg class="w-5 h-5 mr-2 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 10a4 4 0 100-8 4 4 0 000 8zm-6 8a6 6 0 1112 0H4z"/>
                            </svg>
                            Profile
                        </a>

                        <!-- Logout button -->
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>

                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="flex items-center w-full px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-red-100 dark:hover:bg-red-700 rounded-lg transition">
                            <svg class="w-5 h-5 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 10a1 1 0 011-1h8a1 1 0 110 2H4a1 1 0 01-1-1zm9-4a1 1 0 00-1-1H4a1 1 0 100 2h7a1 1 0 001-1zm0 8a1 1 0 00-1-1H4a1 1 0 100 2h7a1 1 0 001-1z"/>
                            </svg>
                            Logout
                        </a>

                    </div>
                </div>
                @endauth

                <!-- Theme toggle button -->
                <button id="theme-toggle" class="p-2 rounded-full bg-white dark:bg-gray-700 focus:outline-none" aria-label="Toggle theme">
                    <!-- Moon icon -->
                    <svg id="icon-moon" class="w-6 h-6 text-gray-800 dark:text-gray-200" fill="currentColor" viewBox="0 0 20 20" style="display: none;">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                    </svg>
                    <!-- Sun icon -->
                    <svg id="icon-sun" class="w-6 h-6 text-gray-800 dark:text-gray-200" fill="currentColor" viewBox="0 0 20 20" style="display: none;">
                        <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1z"/>
                    </svg>
                </button>
            </div>
        </div>
        <nav x-show="mobileMenuOpen" x-cloak @click.away="mobileMenuOpen = false" class="lg:hidden border-t border-white/20 px-4 pb-4 pt-3">
            <div class="flex flex-col gap-1 rounded-xl bg-white dark:bg-gray-800 p-2 shadow-lg">
                <a href="{{ route('home') }}" class="rounded-lg px-4 py-3 font-semibold text-gray-800 dark:text-white hover:bg-purple-100 dark:hover:bg-gray-700">Home</a>
                <a href="{{ route('about') }}" class="rounded-lg px-4 py-3 font-semibold text-gray-800 dark:text-white hover:bg-purple-100 dark:hover:bg-gray-700">About Us</a>
                <a href="{{ route('events') }}" class="rounded-lg px-4 py-3 font-semibold text-gray-800 dark:text-white hover:bg-purple-100 dark:hover:bg-gray-700">Events</a>
                <a href="{{ route('contact') }}" class="rounded-lg px-4 py-3 font-semibold text-gray-800 dark:text-white hover:bg-purple-100 dark:hover:bg-gray-700">Contact</a>
            </div>
        </nav>
        </header>
    @endif
        <!-- Main Content -->
        <main class="flex-1 p-0">
      @if(session('success'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 5000)"
        x-transition
        class="fixed top-5 right-5 z-[9999]
               w-[380px]
               bg-green-100
               text-green-800
               border border-green-300
               px-5 py-4
               rounded-xl
               shadow-lg"
    >
        <div class="flex items-center justify-between gap-4">

            <span class="text-sm font-medium">
                {{ session('success') }}
            </span>

            <button
                @click="show = false"
                class="text-green-800 font-bold text-lg leading-none
                       hover:text-green-900">
                &times;
            </button>

        </div>
    </div>
@endif

     @if(session('error'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 5000)"
        x-transition
        class="fixed top-5 right-5 z-[9999]
               w-[380px]
               bg-red-100
               text-red-800
               border border-red-300
               px-5 py-4
               rounded-xl
               shadow-lg"
    >
        <div class="flex items-center justify-between gap-4">

            <span class="text-sm font-medium">
                {{ session('error') }}
            </span>

            <button
                @click="show = false"
                class="text-red-800 font-bold text-lg leading-none
                       hover:text-red-900">
                &times;
            </button>

        </div>
    </div>
@endif

        @yield('content')
    </main>
    @if (!isset($noFooter) || !$noFooter)
    
      <!-- Footer -->
    <footer class="bg-gray-800 dark:bg-gray-950 text-white py-12 sm:py-16 px-4">
      <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12 text-left">
        <!-- Logo & About -->
        <div>
          <h2 class="text-2xl font-bold mb-4">Eventify</h2>
          <p class="text-gray-400 mb-4 leading-relaxed">
            Discover upcoming events, compare ticket options, and book memorable experiences through Eventify.
          </p>
        </div>
        <!-- Quick Links -->
        <div>
          <h3 class="text-xl font-semibold mb-4">Quick Links</h3>
          <ul class="space-y-2">
            <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-[#8D85EC]">Home</a></li>
            <li><a href="{{ route('about') }}" class="text-gray-300 hover:text-[#8D85EC]">About Us</a></li>
            <li><a href="{{ route('events') }}" class="text-gray-300 hover:text-[#8D85EC]">Browse Events</a></li>
            <li><a href="{{ route('contact') }}" class="text-gray-300 hover:text-[#8D85EC]">Contact</a></li>
          </ul>
        </div>
        <!-- For users and organizers -->
        <div>
          <h3 class="text-xl font-semibold mb-4">Eventify</h3>
          <ul class="space-y-2">
            <li><a href="{{ route('register') }}" class="text-gray-300 hover:text-[#8D85EC]">Create an account</a></li>
            <li><a href="{{ route('login') }}" class="text-gray-300 hover:text-[#8D85EC]">Sign in</a></li>
            <li><a href="{{ route('events') }}" class="text-gray-300 hover:text-[#8D85EC]">Find tickets</a></li>
          </ul>
        </div>
        <!-- Contact -->
        <div>
          <h3 class="text-xl font-semibold mb-4">Contact Us</h3>
          <p class="text-gray-400 mb-4 leading-relaxed">
            Have a question about an event or booking? Our contact page is the best way to reach the Eventify team.
          </p>
          <a href="{{ route('contact') }}" class="inline-flex items-center rounded-full bg-[#8D85EC] px-4 py-2 text-sm font-semibold hover:bg-[#7b76e4] transition">
            Get in touch
          </a>
        </div>
      </div>
      <!-- Bottom Footer -->
      <div class="mt-10 border-t border-gray-700 pt-6 text-center text-gray-400 text-sm">
        <span>&copy; {{ date('Y') }} Eventify. All rights reserved.</span>
      </div>
    </footer>
  
     @endif
   
    <!-- Scripts for theme toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggleBtn = document.getElementById('theme-toggle');
            const moonIcon = document.getElementById('icon-moon');
            const sunIcon = document.getElementById('icon-sun');

            function updateIcons() {
                if (document.documentElement.classList.contains('dark')) {
                    moonIcon.style.display = 'none';
                    sunIcon.style.display = 'block';
                } else {
                    moonIcon.style.display = 'block';
                    sunIcon.style.display = 'none';
                }
            }

            // Initialize theme based on localStorage or prefers-color-scheme
            if (
                localStorage.getItem('color-theme') === 'dark' ||
                (!localStorage.getItem('color-theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)
            ) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            updateIcons();

            // Toggle theme on button click
            toggleBtn.addEventListener('click', () => {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
                updateIcons();
            });
        });

        // Global Save / Favorite Event Handler
        function toggleSaveEvent(e, eventId, btn) {
            if (e) {
                e.stopPropagation();
                e.preventDefault();
            }

            @guest
                window.location.href = "{{ route('login') }}";
                return;
            @endguest

            const allButtons = document.querySelectorAll(`[data-save-event-id="${eventId}"]`);
            allButtons.forEach(b => b.classList.add('scale-90', 'opacity-70'));

            fetch(`/events/${eventId}/toggle-save`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(async response => {
                allButtons.forEach(b => b.classList.remove('scale-90', 'opacity-70'));
                if (response.status === 401) {
                    window.location.href = "{{ route('login') }}";
                    return;
                }
                const data = await response.json();
                if (response.ok && data.success) {
                    allButtons.forEach(b => {
                        updateSaveButtonVisual(b, data.saved);
                    });

                    const countBadges = document.querySelectorAll('.saved-count-badge');
                    countBadges.forEach(el => {
                        if (data.saved_count !== undefined) {
                            el.textContent = data.saved_count;
                        }
                    });

                    // If on Saved Events tab and item unsaved, animate removal
                    if (window.location.search.includes('tab=saved') || window.location.search.includes('saved=1')) {
                        const card = btn.closest('.event-card, .event-listing-card');
                        if (card && !data.saved) {
                            card.style.transition = 'all 0.3s ease';
                            card.style.opacity = '0';
                            card.style.transform = 'scale(0.95)';
                            setTimeout(() => {
                                card.remove();
                                const remaining = document.querySelectorAll('.event-listing-card');
                                if (remaining.length === 0) {
                                    const emptyBox = document.getElementById('saved-empty-state');
                                    if (emptyBox) emptyBox.classList.remove('hidden');
                                }
                            }, 300);
                        }
                    }
                } else {
                    alert(data.message || 'Failed to update saved event.');
                }
            })
            .catch(err => {
                allButtons.forEach(b => b.classList.remove('scale-90', 'opacity-70'));
                console.error('Save event error:', err);
            });
        }

        function updateSaveButtonVisual(btn, isSaved) {
            if (!btn) return;
            const svg = btn.querySelector('svg');
            if (isSaved) {
                btn.classList.remove('text-gray-600', 'dark:text-gray-300');
                btn.classList.add('text-rose-500');
                btn.setAttribute('title', 'Saved to favorites');
                btn.setAttribute('aria-label', 'Remove from saved events');
                if (svg) {
                    svg.setAttribute('fill', 'currentColor');
                    svg.setAttribute('stroke-width', '0');
                }
            } else {
                btn.classList.remove('text-rose-500');
                btn.classList.add('text-gray-600', 'dark:text-gray-300');
                btn.setAttribute('title', 'Save to favorites');
                btn.setAttribute('aria-label', 'Save this event');
                if (svg) {
                    svg.setAttribute('fill', 'none');
                    svg.setAttribute('stroke-width', '2');
                }
            }
        }
</script>
@include('partials.chatbot')

</body>
</html>
