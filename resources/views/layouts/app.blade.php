<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Event Management')</title>
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Flowbite CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
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

    /* Default body text color (override if needed) */
    body, h1, h2, h3, h4, h5, h6, p, a, span { color: #000; }
  </style>
</head>
<body class="font-sans text-black bg-gray-50">
    @if (!isset($noNavbar) || !$noNavbar)

  <!-- Navbar -->
  <header class="w-full bg-[#8D85EC] shadow-md">
    <div class="max-w-7xl mx-auto flex justify-between items-center px-8 py-4">
      <!-- Logo + Title -->
      <div class="flex items-center space-x-4">
        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center overflow-hidden">
          <img src="{{ asset('uploads/eventicon.png') }}" alt="E Icon" class="w-8 h-8 object-contain" />
        </div>
        <span class="text-black text-4xl brand-logo">Eventify</span>
      </div>
      
      <!-- Navigation links -->
       
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

  <!-- Main content -->
  @yield('content')

  <!-- Scripts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>
</html>