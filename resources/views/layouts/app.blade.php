<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Chirper')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css"
      rel="stylesheet"
    />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

    <script>
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
    <style>
        .brand-color {
            background-color: #8d85ec;
        }
        .brand-text {
            color: #8d85ec;
        }
        nav a.active {
            color: #8d85ec !important;
            font-weight: 600;
        }
        .brand-logo {
            font-family: 'Pacifico', cursive;
        }
        /* Hide scrollbar for all modern browsers */
        .scrollbar-hide::-webkit-scrollbar {
        display: none; /* Chrome, Safari, Edge */
        }
        .scrollbar-hide {
        -ms-overflow-style: none; /* IE 10+ */
        scrollbar-width: none; /* Firefox */
        }
        body, h1, h2, h3, h4, h5, h6, p, a, span {
        color: #000 ;
        }

    </style>
</head>
<body class="font-sans bg-gray-300 dark:bg-gray-500">

    {{-- Navbar only shows if $noNavbar is not set or false --}}
    @if (!isset($noNavbar) || !$noNavbar)
        <!-- Navbar -->
        <header class="w-full bg-[#8D85EC] shadow-md">
        <div class="max-w-7xl mx-auto flex justify-between items-center px-8 py-4">
            <!-- Logo + Title -->
            <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center overflow-hidden">
                <img src="uploads/eventicon.png" alt="E Icon" class="w-8 h-8 object-contain" />
            </div>
            <span class="text-black text-4xl brand-logo">Eventify</span>
            </div>
            <nav class="hidden md:flex bg-white rounded-full px-8 py-3 shadow-md w-1/2 justify-center">
                <a href="{{ route('welcome') }}" 
                class="text-black font-semibold hover:underline mx-4 {{ request()->routeIs('welcome') ? 'active' : '' }}">
                Home
                </a>

                <a href="{{ route('about') }}" 
                class="text-black font-semibold hover:underline mx-4 {{ request()->routeIs('about') ? 'active' : '' }}">
                About Us
                </a>

                <a href="#" class="text-black font-semibold hover:underline mx-4">Events</a>
                <a href="#" class="text-black font-semibold hover:underline mx-4">Venues</a>
                <a href="#" class="text-black font-semibold hover:underline mx-4">Contact</a>
            </nav>



            <!-- Buttons -->
            <div class="hidden md:flex items-center space-x-4">
            <a href="{{ route('login') }}" class="bg-white text-[#8D85EC] font-semibold px-5 py-2 rounded-full hover:bg-gray-100 transition">Login</a>
            <a href="{{ route('register') }}" class="bg-[#7b76e4] text-white font-semibold px-5 py-2 rounded-full hover:bg-[#6f69d9] transition">Sign Up</a>
            </div>
        </div>
        </header>
    @endif

    @yield('content')

    <script>
        var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        // Change the icons inside button based on previous settings
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
        }

        var themeToggleBtn = document.getElementById('theme-toggle');

        themeToggleBtn.addEventListener('click', function() {

            // toggle icons inside button
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            // if set via local storage previously
            if (localStorage.getItem('color-theme')) {
                if (localStorage.getItem('color-theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                }

            // if NOT set via local storage previously
            } else {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            }

        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>
</html>
