@extends('layouts.app')

@section('title', 'Venues')

@section('content')

<!-- Main Content: Filters + Venues -->
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
      <!-- Date Filter Options -->
      <div id="dateFilter" class="mt-4 hidden">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">From - To</label>
        <div class="flex space-x-2">
          <input type="date" id="startDate" class="border border-gray-300 dark:border-gray-600 rounded px-2 py-1 w-1/2" />
          <input type="date" id="endDate" class="border border-gray-300 dark:border-gray-600 rounded px-2 py-1 w-1/2" />
        </div>
        <button onclick="applyCustomRange()" class="mt-2 w-full bg-[#8D85EC] dark:bg-[#a78df0]  text-white py-1 px-2 rounded text-sm">Apply Range</button>
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

    <!-- Search Venues with Input -->
    <div class="mb-4">
      <button class="flex items-center justify-between w-full text-left" onclick="toggleSection('venueFilter')">
        <span class="font-semibold text-gray-700 dark:text-gray-200">Venues</span>
        <svg id="icon-venueFilter" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      <div id="venueFilter" class="mt-4 hidden px-2">
        <div class="flex mb-3">
          <input type="text" placeholder="Search Venue" class="w-full border border-gray-300 dark:border-gray-600 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" id="venueSearchInput" />
          <button onclick="searchVenues()" class="ml-2  bg-[#8D85EC] dark:bg-[#a78df0]  hover:bg-blue-700 text-white px-3 py-2 rounded">Search</button>
        </div>
        <div class="max-h-40 overflow-y-auto space-y-2" id="venueList">
               @php
      $activeCategory = request('venue_name');
    @endphp
         <a href="{{ route('venues', ['venue_name' => 'Kathmandu Arena']) }}"
       class="block w-full text-sm mb-2 {{ $activeCategory == 'Kathmandu Arena' ? 'font-bold text-[#8D85EC]' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white' }}">
    Kathmandu Arena
    </a>
           <a href="{{ route('venues', ['venue_name' => 'National Art Gallery']) }}"
       class="block w-full text-sm mb-2 {{ $activeCategory == 'National Art Gallery' ? 'font-bold text-[#8D85EC]' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white' }}">
    National Art Gallery
    </a>
    
           <a href="{{ route('venues', ['venue_name' => 'Hotel Everest']) }}"
       class="block w-full text-sm mb-2 {{ $activeCategory == 'Hotel Everest' ? 'font-bold text-[#8D85EC]' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white' }}">
    Hotel Everest
    </a>
         
        
          <a href="{{ route('venues') }}"
       class="block w-full text-sm mb-2 {{ !$activeCategory ? 'font-bold text-[#8D85EC]' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white' }}">
      All
    </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Venues Listing -->
  <div class="flex-1">
    <h2 class="text-3xl font-bold mb-4 mt-8 text-gray-900 dark:text-white">Venues</h2>
    @if($venues->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-5">
      @foreach($venues as $venue)
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl overflow-hidden hover:shadow-2xl transition transform hover:-translate-y-1 hover:scale-105 w-full">
          <div class="w-full h-64 overflow-hidden">
            <img src="{{ asset('uploads/' . $venue->image) }}" 
                 alt="{{ $venue->venue_name }}" 
                 class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
          </div>
          <div class="p-5 flex flex-col gap-2">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white truncate">{{ $venue->venue_name }}</h3>
            <p class="text-gray-900 font-medium dark:text-gray-200 text-sm line-clamp-2">{{ $venue->description }}</p>
            <p class="text-gray-600 dark:text-gray-300 text-sm truncate">Location: {{ $venue->location }}</p>
     
            @if($venue->price_type === 'package')
              <div class="mt-2 p-3 bg-gray-100 dark:bg-gray-700 rounded-lg">
                <p class="text-sm text-gray-700 dark:text-gray-200">
                  🎁 <span class="font-semibold text-[#8d85ec]">Package Price:</span> Rs {{ number_format($venue->package_price ?? 0, 2) }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Package Includes: {{ $venue->package_details ?? '-' }}</p>
              </div>
            @else
              <p class="text-[#8d85ec] font-semibold text-sm mt-1">
                Price: Rs {{ number_format($venue->base_price ?? 0, 2) }} / 
                {{ $venue->price_type === 'per_person' ? 'person' : ($venue->price_type === 'per_day' ? 'day' : 'hour') }}
              </p>
            @endif
            @if($venue->has_catering)
              <div class="mt-2 p-3 bg-gray-100 dark:bg-gray-700 rounded-lg">
                <p class="text-sm text-gray-700 dark:text-gray-200">
                  🍽️ <span class="font-semibold text-[#8d85ec]">Catering Available</span><br>
                  Rs {{ number_format($venue->catering_price_per_person ?? 0, 2) }} per person
                </p>
                @if($venue->catering_menu)
                  <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Menu: {{ $venue->catering_menu }}</p>
                @endif
              </div>
            @endif
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
        <p class="text-gray-700 dark:text-gray-200 text-lg font-semibold">No venues found. Start by adding a new venue!</p>
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

// Get and set URL query parameters
function getQueryParams() {
  return new URLSearchParams(window.location.search);
}

function setQueryParams(params) {
  window.location.search = params.toString();
}

// Apply custom date range
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

// Price filtering
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

// Venue search and filter
function searchVenues() {
  const input = document.getElementById('venueSearchInput');
  const venueName = input.value.trim();
  const params = new URLSearchParams(window.location.search);
  if (venueName) {
    params.set('venue', venueName);
  } else {
    params.delete('venue');
  }
  window.location.search = params.toString();
}

let selectedVenue = '';

function filterVenue(venueName) {
  const params = new URLSearchParams(window.location.search);
  if (venueName) {
    params.set('venue', venueName);
  } else {
    params.delete('venue');
  }
  window.location.search = params.toString();
}
</script>
@endsection