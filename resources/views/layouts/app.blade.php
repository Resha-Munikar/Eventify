<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite('resources/css/app.css') {{-- or your CSS build setup --}}
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet" />
    <style>
      
       
        <style>
        /* Your custom styles */
        .brand-color { background-color: #8d85ec; }
        .brand-text { color: #8d85ec; }
        .brand-logo { font-family: 'Pacifico', cursive; }
        nav a.active { color: #8d85ec !important; font-weight: 600; }

        /* Hide scrollbar for all modern browsers */
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    </style>
</head>
<body class="flex flex-col min-h-screen bg-gray-50 dark:bg-gray-900 text-black dark:text-white">

    {{-- Navbar only shows if $noNavbar is not set or false --}}
    @if (!isset($noNavbar) || !$noNavbar)
        <!-- Navbar -->
        <header class="w-full bg-[#8D85EC]  dark:bg-gray-900 shadow-md">
        <div class="max-w-7xl mx-auto flex justify-between items-center px-8 py-4">
            <!-- Logo + Title -->
            <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-white  dark:bg-gray-700 rounded-full flex items-center justify-center overflow-hidden">
                <img src="uploads/eventicon.png" alt="E Icon" class="w-8 h-8 object-contain" />
            </div>
            <span class="text-black dark:text-white text-4xl brand-logo">Eventify</span>
            </div>
            <nav class="hidden md:flex bg-white dark:bg-gray-700 rounded-full px-8 py-3 shadow-md w-1/2 justify-center">
                <a href="{{ route('welcome') }}" 
                class="text-black dark:text-white font-semibold hover:underline mx-4 {{ request()->routeIs('welcome') ? 'active' : '' }}">
                Home
                </a>

                <a href="{{ route('about') }}" 
                class="text-black dark:text-white font-semibold hover:underline mx-4 {{ request()->routeIs('about') ? 'active' : '' }}">
                About Us
                </a>

                <a href="#" class="text-black  dark:text-white font-semibold hover:underline mx-4">Events</a>
                <a href="#" class="text-black  dark:text-white font-semibold hover:underline mx-4">Venues</a>
                <a href="#" class="text-black dark:text-white font-semibold hover:underline mx-4">Contact</a>
            </nav>



            <!-- Buttons -->
            <div class="hidden md:flex items-center space-x-4">
            <a href="{{ route('login') }}" class="bg-white dark:bg-gray-700 text-[#8D85EC] dark:text-white font-semibold px-4 py-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600 transition">
      Login
    </a>
            <a href="{{ route('register') }}" class="bg-[#7b76e4] text-white dark:bg-gray-700  font-semibold px-5 py-2 rounded-full hover:bg-[#6f69d9] transition">Sign Up</a>
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
        </header>
    @endif
    <!-- Main Content -->
    <main class="flex-1 p-0">
        @yield('content')
    </main>

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
    </script>
</body>
</html>