@extends('layouts.app')

@section('title', 'Events')

@section('content')

<!-- Main Content: Filters + Events -->
<div class="max-w-7xl mx-auto flex gap-4 p-4 dark:bg-gray-800">

  <!-- Filter Sidebar -->
 <div class="w-60 bg-white p-3 rounded-lg shadow-lg sticky top-4 z-10 h-[80vh] overflow-y-auto dark:bg-gray-700">
    <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Filters</h2>
    
    <!-- Date Filter -->
    <div class="mb-4">
      <button class="flex items-center justify-between w-full text-left" onclick="toggleSection('dateFilter')">
        <span class="font-semibold text-gray-700 dark:text-gray-200">Date</span>
        <svg id="icon-dateFilter" class="w-4 h-4 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      <div id="dateFilter" class="mt-2 hidden">
        <button class="block w-full text-sm text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white mb-2">Today</button>
        <button class="block w-full text-sm text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white mb-2">Tomorrow</button>
        <button class="block w-full text-sm text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white mb-2">This Weekend</button>
        <div class="mt-2">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">From - To</label>
          <div class="flex space-x-2">
            <input type="date" class="border border-gray-300 dark:border-gray-600 rounded px-2 py-1 w-1/2" />
            <input type="date" class="border border-gray-300 dark:border-gray-600 rounded px-2 py-1 w-1/2" />
          </div>
        </div>
      </div>
    </div>
    
   <!-- Categories Filter -->
<div class="mb-4">
  <button class="flex items-center justify-between w-full text-left" onclick="toggleSection('categoriesFilter')">
    <span class="font-semibold text-gray-700 dark:text-gray-200">Categories</span>
    <svg id="icon-categoriesFilter" class="w-4 h-4 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
    </svg>
  </button>
  <div id="categoriesFilter" class="mt-2 hidden">
    @php
      $activeCategory = request('category');
    @endphp

    <a href="{{ route('events', ['category' => 'Concert']) }}"
       class="block w-full text-sm mb-2 {{ $activeCategory == 'Concert' ? 'font-bold text-blue-600' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white' }}">
      Concert
    </a>
    <a href="{{ route('events', ['category' => 'Art']) }}"
       class="block w-full text-sm mb-2 {{ $activeCategory == 'Art' ? 'font-bold text-blue-600' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white' }}">
      Art
    </a>
    <a href="{{ route('events', ['category' => 'Food and Drink']) }}"
       class="block w-full text-sm mb-2 {{ $activeCategory == 'Food and Drink' ? 'font-bold text-blue-600' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white' }}">
      Food and Drink
    </a>
    <a href="{{ route('events', ['category' => 'Technology']) }}"
       class="block w-full text-sm mb-2 {{ $activeCategory == 'Technology' ? 'font-bold text-blue-600' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white' }}">
      Technology
    </a>
    <a href="{{ route('events', ['category' => 'Sports']) }}"
       class="block w-full text-sm mb-2 {{ $activeCategory == 'Sports' ? 'font-bold text-blue-600' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white' }}">
      Sports
    </a>
    <a href="{{ route('events', ['category' => 'Wellness']) }}"
       class="block w-full text-sm mb-2 {{ $activeCategory == 'Wellness' ? 'font-bold text-blue-600' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white' }}">
     Wellness
    </a>
    <a href="{{ route('events') }}"
       class="block w-full text-sm mb-2 {{ !$activeCategory ? 'font-bold text-blue-600' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white' }}">
      All
    </a>
  </div>
</div>
    
    <!-- More Filters -->
    <div class="mb-4">
      <button class="flex items-center justify-between w-full text-left" onclick="toggleSection('moreFilters')">
        <span class="font-semibold text-gray-700 dark:text-gray-200">More Filters</span>
        <svg id="icon-moreFilters" class="w-4 h-4 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      <div id="moreFilters" class="mt-2 hidden">
        <button class="block w-full text-sm text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white mb-2">Filter 1</button>
        <button class="block w-full text-sm text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white mb-2">Filter 2</button>
      </div>
    </div>
    
    <!-- Price Filter -->
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Price</label>
      <button class="w-full bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-200 py-2 px-4 rounded mb-2">Browse by Venues</button>
    </div>
  </div>

  <!-- Events Listing -->
  <div class="flex-1">
    <!-- Your existing Events Listing code -->
    <h2 class="text-3xl font-bold mb-4 mt-8 text-gray-900 dark:text-white">Upcoming Events</h2>
    @if($events->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-6 mt-5">
        @foreach($events as $event)
            <div class="rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition transform hover:-translate-y-1 hover:scale-105 w-full bg-white dark:bg-gray-700">
                <img src="{{ asset('uploads/' . $event->image) }}" alt="{{ $event->event_name }}" class="h-60 w-full object-cover" />

                <div class="p-5 flex flex-col gap-2 text-gray-900 dark:text-gray-200">
                    <h3 class="text-lg font-bold truncate">{{ $event->event_name }}</h3>
                    <p class="text-sm truncate">{{ $event->venue }}</p>
                    <p class="text-sm line-clamp-2">{{ $event->description }}</p>
                    <p class="text-[#8d85ec] font-semibold text-sm mt-1">Price: ${{ number_format($event->price, 2) }}</p>
                    <p class="text-sm">Seats: {{ $event->available_seats }}</p>
                    <p class="text-sm">Date: {{ \Carbon\Carbon::parse($event->event_date)->format('d M, Y') }}</p>

                    <div class="flex justify-center mt-4">
                        <a href="{{ route('login') }}"
                           class="bg-[#8D85EC] hover:bg-[#7b76e4] text-white font-semibold text-sm py-2 px-4 rounded-lg transition">
                            Book Now
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @else
    <div class="text-center mt-20">
        <p class="text-gray-700 dark:text-gray-200 text-lg font-semibold">No events found. Start by adding a new event!</p>
    </div>
    @endif
  </div>
</div>

<!-- Toggle script -->
<script>
  function toggleSection(id) {
    const section = document.getElementById(id);
    const icon = document.getElementById('icon-' + id);
    if (section.classList.contains('hidden')) {
      section.classList.remove('hidden');
      if (icon) icon.classList.add('rotate-180');
    } else {
      section.classList.add('hidden');
      if (icon) icon.classList.remove('rotate-180');
    }
  }
</script>
@endsection