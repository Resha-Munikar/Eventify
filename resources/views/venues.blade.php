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
              @guest
                  <a href="{{ route('login') }}"
                    class="bg-[#8D85EC] hover:bg-[#7b76e4] text-white font-semibold text-sm py-2 px-4 rounded-lg transition">
                      Book Now
                  </a>
              @endguest

              @auth
                  <button 
                      onclick="openBookingForm({{ $venue->id }}, '{{ $venue->venue_name }}', {{ $venue->price_type === 'package' ? $venue->package_price ?? 0 : $venue->base_price ?? 0 }}, '{{ $venue->price_type }}')"
                      class="bg-[#8D85EC] hover:bg-[#7b76e4] text-white font-semibold text-sm py-2 px-4 rounded-lg transition">
                      Book Now
                  </button>
              @endauth
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
    @else
      <div class="text-center mt-20">
        <p class="text-gray-700 dark:text-gray-200 text-lg font-semibold">No venues found. Start by adding a new venue!</p>
      </div>
    @endif
  </div>

  <!-- Booking Modal -->
<div id="bookingModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-96 p-6 relative">
    <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white" id="venueNameTitle">Book Venue</h2>

    <form id="bookingForm" action="{{ route('venues.book') }}" method="POST">
      @csrf
      <input type="hidden" name="venue_id" id="venueIdInput">
      <input type="hidden" name="total_price" id="totalPriceInput" value="0">

      <div class="mb-3">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Event Date</label>
        <input type="text" name="event_date" id="eventDate"
               class="w-full border border-gray-300 rounded px-3 py-2 dark:bg-gray-700 dark:text-white" required>
      </div>

      <div id="dynamicInputWrapper" class="mb-3">
        <label id="dynamicInputLabel" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Guests</label>
        <input type="number" name="guests" id="dynamicInput" min="1"
               class="w-full border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-2 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-purple-400 focus:outline-none transition"
               value="1" required>
      </div>

      <div class="mb-3">
        <p class="text-gray-800 dark:text-gray-200 text-sm">
          Total Price: <span id="totalPriceDisplay">Rs 0</span>
        </p>
      </div>

      <div class="flex justify-end gap-3 mt-4">
        <button type="button" onclick="closeBookingForm()" class="px-4 py-2 bg-[#8D85EC] text-white rounded hover:bg-[#7b76e4]">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-[#8D85EC] text-white rounded hover:bg-[#7b76e4]">Confirm</button>
      </div>
    </form>
  </div>
</div>


</div>

<script>
let currentVenuePrice = 0;
let currentPriceType = 'per_person';
let flatpickrInstance = null;

function openBookingForm(venueId, venueName, price, priceType) {
    const venueInput = document.getElementById('venueIdInput');
    const venueTitle = document.getElementById('venueNameTitle');
    const dynamicInput = document.getElementById('dynamicInput');
    const dynamicLabel = document.getElementById('dynamicInputLabel');

    venueInput.value = venueId;
    venueTitle.innerText = "Book Venue: " + venueName;
    document.getElementById('bookingModal').classList.remove('hidden');

    currentVenuePrice = price;
    currentPriceType = priceType;

    // Reset input
    dynamicInput.value = 1;
    dynamicInput.disabled = false;
    dynamicInput.required = true;
    dynamicInput.type = 'number';

    // Configure label and input per price type
    switch(priceType){
        case 'per_person':
            dynamicLabel.innerText = "Number of Guests";
            dynamicInput.placeholder = "Enter number of guests";
            break;
        case 'per_hour':
            dynamicLabel.innerText = "Number of Hours";
            dynamicInput.placeholder = "Enter number of hours";
            break;
        case 'per_day':
            dynamicLabel.innerText = "Number of Days";
            dynamicInput.placeholder = "Enter number of days";
            break;
        case 'package':
            dynamicLabel.innerText = "Package Booking";
            dynamicInput.type = 'hidden';
            dynamicInput.value = 1;
            dynamicInput.required = false;
            break;
        default:
            dynamicLabel.innerText = "Number of Guests";
            dynamicInput.placeholder = "Enter number of guests";
    }

    // Always update total price when modal opens
    updateTotalPrice();

    // Listen for changes
    dynamicInput.removeEventListener('input', updateTotalPrice);
    dynamicInput.addEventListener('input', updateTotalPrice);

    // Initialize Flatpickr
    fetch(`/venues/${venueId}/booked-dates`)
        .then(res => res.json())
        .then(data => {
            const bookedDates = data.bookedDates || [];
            if(flatpickrInstance) flatpickrInstance.destroy();
            flatpickrInstance = flatpickr("#eventDate", {
                minDate: "today",
                dateFormat: "Y-m-d",
                disable: bookedDates,
                defaultDate: "today",
                onDayCreate: function(dObj, dStr, fp, dayElem) {
                    const dateStr = fp.formatDate(dayElem.dateObj, "Y-m-d");
                    if(bookedDates.includes(dateStr)) {
                        dayElem.style.backgroundColor = "#f87171";
                        dayElem.style.color = "#fff";
                        dayElem.style.cursor = "not-allowed";
                    }
                }
            });
        })
        .catch(err => console.error("Failed to fetch booked dates:", err));
}

function updateTotalPrice(){
    const dynamicInput = document.getElementById('dynamicInput');
    const count = parseInt(dynamicInput.value) || 1;
    let total = 0;

    switch(currentPriceType){
        case 'per_person':
        case 'per_hour':
        case 'per_day':
            total = currentVenuePrice * count;
            break;
        case 'package':
            total = currentVenuePrice;
            break;
        default:
            total = currentVenuePrice;
    }

    document.getElementById('totalPriceDisplay').innerText = "Rs " + total.toLocaleString();
    // Use numeric value for hidden input
    document.getElementById('totalPriceInput').value = total;
}

function closeBookingForm() {
    document.getElementById('bookingModal').classList.add('hidden');
}

// Ensure total price is correct before submitting
document.getElementById('bookingForm').addEventListener('submit', function(e){
    updateTotalPrice();
});

// Toggle filter sections
function toggleSection(id) {
    const section = document.getElementById(id);
    const icon = document.getElementById('icon-' + id);
    section.classList.toggle('hidden');
    if(icon) icon.classList.toggle('rotate-180');
}

// URL query helpers
function getQueryParams() {
    return new URLSearchParams(window.location.search);
}
function setQueryParams(params) {
    window.location.search = params.toString();
}

// Filter functions
function applyCustomRange() {
    const start = document.getElementById('startDate').value;
    const end = document.getElementById('endDate').value;
    const params = getQueryParams();
    if (start) params.set('start_date', start);
    if (end) params.set('end_date', end);
    setQueryParams(params);
}

function applyPriceFilter() {
    const minPrice = document.getElementById('minPrice').value;
    const maxPrice = document.getElementById('maxPrice').value;
    const params = getQueryParams();
    if (minPrice) params.set('min_price', minPrice);
    if (maxPrice) params.set('max_price', maxPrice);
    setQueryParams(params);
}

function searchVenues() {
    const input = document.getElementById('venueSearchInput').value.trim();
    const params = getQueryParams();
    if(input) params.set('venue', input);
    else params.delete('venue');
    setQueryParams(params);
}

function filterVenue(venueName) {
    const params = getQueryParams();
    if (venueName) params.set('venue', venueName);
    else params.delete('venue');
    setQueryParams(params);
}


</script>


@endsection
