@extends('layouts.app')

@section('title', 'Events')

@section('content')

<!-- Main Content: Filters + Events -->
<div class="max-w-7xl mx-auto flex gap-4 p-4 dark:bg-gray-800">

  <!-- Filter Sidebar -->
 <div class="w-60 bg-white p-3 rounded-lg shadow-lg sticky top-4 z-10 h-[80vh] overflow-y-auto dark:bg-gray-700">
    <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Filters</h2>
    
  <!-- Date Filter Toggle Button -->
  <div class="mb-4">
<button class="flex items-center justify-between w-full text-left" onclick="toggleSection('dateFilter')">
  <span class="font-semibold text-gray-700 dark:text-gray-200">Date</span>
  <svg id="icon-dateFilter" class="w-4 h-4 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
  </svg>
</button>

<!-- Date Filter Options (Initially Hidden) -->
<div id="dateFilter" class="mt-4 hidden">
  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">From - To</label>
  <div class="flex space-x-2">
    <input type="date" id="startDate" class="border border-gray-300 dark:border-gray-600 rounded px-2 py-1 w-1/2" />
    <input type="date" id="endDate" class="border border-gray-300 dark:border-gray-600 rounded px-2 py-1 w-1/2" />
  </div>
  <button onclick="applyPredefinedDate('today')" class="mt-2 w-full  bg-[#8D85EC] dark:bg-[#a78df0] text-white py-1 px-2 rounded text-sm">Today</button>
  <button onclick="applyPredefinedDate('tomorrow')" class="mt-2 w-full  bg-[#8D85EC] dark:bg-[#a78df0] text-white py-1 px-2 rounded text-sm">Tomorrow</button>
  <button onclick="applyPredefinedDate('weekend')" class="mt-2 w-full  bg-[#8D85EC] dark:bg-[#a78df0] text-white py-1 px-2 rounded text-sm">This Weekend</button>
  <button onclick="applyCustomRange()" class="mt-2 w-full bg-[#8D85EC] dark:bg-[#a78df0]  text-white py-1 px-2 rounded text-sm">Apply Range</button>
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
  <div id="categoriesFilter" class="mt-4 hidden">
    @php
      $activeCategory = request('category');
    @endphp

    <a href="{{ route('events', ['category' => 'Concert']) }}"
       class="block w-full text-sm mb-2 {{ $activeCategory == 'Concert' ? 'font-bold text-[#8D85EC]' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white' }}">
      Concert
    </a>
    <a href="{{ route('events', ['category' => 'Art']) }}"
       class="block w-full text-sm mb-2 {{ $activeCategory == 'Art' ? 'font-bold text-[#8D85EC]' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white' }}">
      Art
    </a>
    <a href="{{ route('events', ['category' => 'Food and Drink']) }}"
       class="block w-full text-sm mb-2 {{ $activeCategory == 'Food and Drink' ? 'font-bold text-[#8D85EC]' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white' }}">
      Food and Drink
    </a>
    <a href="{{ route('events', ['category' => 'Technology']) }}"
       class="block w-full text-sm mb-2 {{ $activeCategory == 'Technology' ? 'font-bold text-[#8D85EC]' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white' }}">
      Technology
    </a>
    <a href="{{ route('events', ['category' => 'Sports']) }}"
       class="block w-full text-sm mb-2 {{ $activeCategory == 'Sports' ? 'font-bold text-[#8D85EC]' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white' }}">
      Sports
    </a>
    <a href="{{ route('events', ['category' => 'Wellness']) }}"
       class="block w-full text-sm mb-2 {{ $activeCategory == 'Wellness' ? 'font-bold text-[#8D85EC]' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white' }}">
     Wellness
    </a>
    <a href="{{ route('events') }}"
       class="block w-full text-sm mb-2 {{ !$activeCategory ? 'font-bold text-[#8D85EC]' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white' }}">
      All
    </a>
  </div>
</div>
<!-- Price Filter -->
  <div class="mb-4">
    <button class="flex items-center justify-between w-full text-left" onclick="toggleSection('priceFilter')">
      <span class="font-semibold text-gray-700 dark:text-gray-200">Price</span>
      <svg id="icon-priceFilter" class="w-4 h-4 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
      </svg>
    </button>
   <div id="priceFilter" class="mt-4 hidden">
      <div class="flex space-x-2">
        <input type="number" id="minPrice" placeholder="Min" class="border border-gray-300 dark:border-gray-600 rounded px-2 py-1 w-1/2" />
        <input type="number" id="maxPrice" placeholder="Max" class="border border-gray-300 dark:border-gray-600 rounded px-2 py-1 w-1/2" />
      </div>
      <button onclick="applyPriceFilter()" class="mt-2 w-full  bg-[#8D85EC] dark:bg-[#a78df0]  text-white py-1 px-2 rounded text-sm">Apply Price</button>
    </div>
  </div>
  <!-- Browse by Venues with Search -->
 <div class="mb-4">
  <button class="flex items-center justify-between w-full text-left" onclick="toggleSection('venueFilter')">
    <span class="font-semibold text-gray-700 dark:text-gray-200">Venues</span>
    <svg id="icon-venueFilter" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
    </svg>
  </button>
  <div id="venueFilter" class="mt-4 hidden px-2">
    <!-- Search input and button -->
    <div class="flex mb-3">
      <input type="text" placeholder="Search Venue" class="w-full border border-gray-300 dark:border-gray-600 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" id="venueSearchInput" />
      <button onclick="searchVenues()" class="ml-2  bg-[#8D85EC] dark:bg-[#a78df0]  hover:bg-blue-700 text-white px-3 py-2 rounded">Search</button>
    </div>
    <!-- Venue list -->
    <div class="max-h-40 overflow-y-auto space-y-2" id="venueList">
      <button class="w-full text-left px-2 py-1 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 rounded" onclick="filterVenue('Kathmandu Arena')">Kathmandu Arena</button>
      <button class="w-full text-left px-2 py-1 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 rounded" onclick="filterVenue('National Art Gallery')">National Art Gallery</button>
      <button class="w-full text-left px-2 py-1 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 rounded" onclick="filterVenue('Hotel Everest')">Hotel Everest</button>
    </div>
  </div>
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

<script>
  // Toggle script
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

// Function to get URL query parameters
function getQueryParams() {
  return new URLSearchParams(window.location.search);
}

// Function to set URL query parameters
function setQueryParams(params) {
  window.location.search = params.toString();
}

// Apply predefined date range: today, tomorrow, weekend
function applyPredefinedDate(option) {
  const params = getQueryParams();
  const today = new Date();

  let startDate, endDate;

  if (option === 'today') {
    startDate = endDate = today.toISOString().slice(0, 10);
  } else if (option === 'tomorrow') {
    const tomorrow = new Date();
    tomorrow.setDate(today.getDate() + 1);
    startDate = endDate = tomorrow.toISOString().slice(0, 10);
  } else if (option === 'weekend') {
    const day = today.getDay(); // Sunday=0, Monday=1, ..., Saturday=6
    const daysUntilSaturday = (6 - day + 7) % 7;
    const daysUntilSunday = (7 - day + 7) % 7;

    const saturday = new Date();
    saturday.setDate(today.getDate() + daysUntilSaturday);
    const sunday = new Date();
    sunday.setDate(today.getDate() + daysUntilSunday);

    startDate = saturday.toISOString().slice(0, 10);
    endDate = sunday.toISOString().slice(0, 10);
  }

  if (startDate && endDate) {
    params.set('start_date', startDate);
    params.set('end_date', endDate);
    setQueryParams(params);
  }
}

// Apply custom range based on user input
function applyCustomRange() {
  const start = document.getElementById('startDate').value;
  const end = document.getElementById('endDate').value;
  const params = getQueryParams();

  if (start) {
    params.set('start_date', start);
  }
  if (end) {
    params.set('end_date', end);
  }

  setQueryParams(params);
}
  function applyPriceFilter() {
    const minPrice = document.getElementById('minPrice').value;
    const maxPrice = document.getElementById('maxPrice').value;
    const params = getQueryParams();

    if (minPrice) {
      params.set('min_price', minPrice);
    }
    if (maxPrice) {
      params.set('max_price', maxPrice);
    }

    window.location.search = params.toString();
  }
 function searchVenues() {
  const input = document.getElementById('venueSearchInput');
  const venueName = input.value.trim();

  // Optional: validate if the venue exists in your list
  // For now, assume any input is valid and directly apply filter

  const params = new URLSearchParams(window.location.search);
  if (venueName) {
    params.set('venue', venueName);
  } else {
    params.delete('venue');
  }
  window.location.search = params.toString(); // Reload page with venue filter
}

  // Filter event cards by selected venue
let selectedVenue = ''; // Keep track of selected venue

function filterVenue(venueName) {
  const params = new URLSearchParams(window.location.search);
  if (venueName) {
    params.set('venue', venueName);
  } else {
    params.delete('venue'); // optional, to clear filter
  }
  window.location.search = params.toString(); // reload page with new URL
}
</script>
@endsection