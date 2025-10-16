@extends('layouts.app')

@section('title', 'Venues')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

  <!-- Search Bar -->
  <div class="flex justify-center mb-4 mt-1">
    <form class="flex items-center w-full max-w-xl" onsubmit="searchVenues(); return false;">
      <label for="simple-search" class="sr-only">Search</label>
      <div class="relative w-full">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
          <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
          </svg>
        </div>
        <input
          type="text"
          id="simple-search"
          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#8D85EC] focus:border-[#8D85EC] block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
          placeholder="Search venue..."
        />
      </div>
      <button type="submit"
        class="p-2.5 ml-2 text-sm font-medium rounded-lg text-white transition"
        style="background-color: #8D85EC;"
        onmouseover="this.style.backgroundColor='#7b76e4'"
        onmouseout="this.style.backgroundColor='#8D85EC'">
        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
        </svg>
      </button>
    </form>
  </div>

  <!-- Venues Section -->
  <h2 class="text-2xl font-bold mb-3 text-gray-900 dark:text-white">Venues</h2>

  @if($venues->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      @foreach($venues as $venue)
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl overflow-hidden hover:shadow-2xl transition transform hover:-translate-y-1 hover:scale-105 w-full h-120">
          
          <!-- Image Section (taller height) -->
          <div class="w-full h-60 overflow-hidden">
            <img src="{{ asset('uploads/' . $venue->image) }}" 
              alt="{{ $venue->venue_name }}" 
              class="w-full h-full object-cover transition-transform duration-300 hover:scale-105" />
          </div>
          
          <!-- Details Section -->
          <div class="p-6 flex flex-col justify-between min-h-[230px]">
            <div>
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">{{ $venue->venue_name }}</h3>
              <p class="text-gray-700 dark:text-gray-300 text-sm mb-2">{{ $venue->description }}</p>
              <p class="text-gray-600 dark:text-gray-400 text-sm mb-2">Location: {{ $venue->location }}</p>

              @if($venue->price_type === 'package')
                <p class="text-[#8D85EC] font-semibold text-sm">Price: Rs {{ number_format($venue->package_price ?? 0, 2) }} / package</p>
              @else
                <p class="text-[#8D85EC] font-semibold text-sm">Price: Rs {{ number_format($venue->base_price ?? 0, 2) }} / 
                  {{ $venue->price_type === 'per_person' ? 'person' : ($venue->price_type === 'per_day' ? 'day' : 'hour') }}
                </p>
              @endif
            </div>

            <!-- Book Now Button -->
            <div class="mt-4 flex justify-center">
              <a href="{{ route('login') }}" 
                class="bg-[#8D85EC] hover:bg-[#7b76e4] text-white text-sm font-medium py-2 px-5 rounded-lg transition">
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

<script>
function searchVenues() {
  const input = document.getElementById('simple-search');
  const venueName = input.value.trim();
  const params = new URLSearchParams(window.location.search);
  if (venueName) {
    params.set('query', venueName);
  } else {
    params.delete('query');
  }
  window.location.search = params.toString();
}
</script>
@endsection
