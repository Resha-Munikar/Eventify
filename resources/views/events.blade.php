@extends('layouts.app')

@section('title', 'Events')

@section('content')

<div class="max-w-7xl mx-auto flex gap-4 p-4 dark:bg-gray-800">
  
  <!-- Sidebar Filters -->
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
        @php $activeCategory = request('category'); @endphp

        <a href="{{ route('events', ['category' => 'Concert']) }}"
           class="block w-full text-sm mb-2 {{ $activeCategory == 'Concert' ? 'font-bold text-blue-600' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white' }}">Concert</a>
        <a href="{{ route('events', ['category' => 'Art']) }}"
           class="block w-full text-sm mb-2 {{ $activeCategory == 'Art' ? 'font-bold text-blue-600' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white' }}">Exhibition</a>
        <a href="{{ route('events', ['category' => 'Food and Drink']) }}"
           class="block w-full text-sm mb-2 {{ $activeCategory == 'Food and Drink' ? 'font-bold text-blue-600' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white' }}">Food and Drink</a>
        <a href="{{ route('events', ['category' => 'Technology']) }}"
           class="block w-full text-sm mb-2 {{ $activeCategory == 'Technology' ? 'font-bold text-blue-600' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white' }}">Technology</a>
        <a href="{{ route('events', ['category' => 'Sports']) }}"
           class="block w-full text-sm mb-2 {{ $activeCategory == 'Sports' ? 'font-bold text-blue-600' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white' }}">Sports</a>
        <a href="{{ route('events', ['category' => 'Wellness']) }}"
           class="block w-full text-sm mb-2 {{ $activeCategory == 'Wellness' ? 'font-bold text-blue-600' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white' }}">Workshop</a>
        <a href="{{ route('events') }}"
           class="block w-full text-sm mb-2 {{ !$activeCategory ? 'font-bold text-blue-600' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white' }}">All</a>
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
    
    <!-- Price / Browse -->
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Price</label>
      <button class="w-full bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-200 py-2 px-4 rounded mb-2">Browse by Venues</button>
    </div>
  </div>

  <!-- Events Listing with Booking Modal -->
  <div x-data="{ openBookingId: null, selectedEvent: null }" class="flex-1">
    <h2 class="text-3xl font-bold mb-4 mt-8 text-gray-900 dark:text-white">Upcoming Events</h2>

    @if($events->count() > 0)
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-8 mt-8">
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
                  @guest
                      <a href="{{ route('login') }}"
                        class="bg-[#8D85EC] hover:bg-[#7b76e4] text-white font-semibold text-sm py-2 px-4 rounded-lg transition">
                          Book Now
                      </a>
                  @endguest

                  @auth
                  <button 
                    @click="openBookingId = {{ $event->id }}; selectedEvent = {{ $event->toJson() }}; generateEsewaForm(selectedEvent)"
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
          <p class="text-gray-700 dark:text-gray-200 text-lg font-semibold">No events found.</p>
      </div>
    @endif

    <!-- Booking Modal -->
    <div x-show="openBookingId !== null" x-transition.opacity class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60 z-50">
      <div @click.away="openBookingId = null; selectedEvent = null" class="bg-gray-100 dark:bg-gray-900 rounded-2xl p-6 w-96 shadow-2xl transform transition-all duration-300 scale-100 border border-gray-200 dark:border-gray-700">
        <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white border-b border-gray-300 dark:border-gray-700 pb-2 text-center">
          Book Event: <span x-text="selectedEvent ? selectedEvent.event_name : ''"></span>
        </h2>

        <div x-data="{ tickets: 1 }">
          <label class="block text-gray-700 dark:text-gray-200 mb-1 font-semibold">Number of Tickets</label>
          <input type="number" x-model="tickets" min="1" :max="selectedEvent ? selectedEvent.available_seats : 1"
            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-[#8D85EC] focus:outline-none mb-4">

          <div class="bg-white dark:bg-gray-800 p-3 rounded-lg mb-4 text-center shadow-sm border border-gray-200 dark:border-gray-700">
            <p class="text-gray-800 dark:text-gray-200 text-sm">
              Price per ticket: $<span x-text="selectedEvent ? Number(selectedEvent.price).toFixed(2) : '0.00'"></span>
            </p>
            <p class="text-gray-800 dark:text-gray-200 text-sm mt-1">
              Total amount: 
              <span class="font-bold text-[#8D85EC] text-lg">$<span x-text="selectedEvent ? (tickets * Number(selectedEvent.price)).toFixed(2) : '0.00'"></span></span>
            </p>
          </div>

          <div class="flex justify-end gap-2">
            <button type="button" @click="openBookingId = null; selectedEvent = null" 
              class="px-4 py-2 rounded-lg bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-400 dark:hover:bg-gray-600 transition">
              Cancel
            </button>
            <!-- eSewa Payment Form -->
            <form id="esewaForm" action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST">
              <input type="hidden" name="amount" value="0">
              <input type="hidden" name="tax_amount" value="0">
              <input type="hidden" name="product_service_charge" value="0">
              <input type="hidden" name="product_delivery_charge" value="0">
              <input type="hidden" name="total_amount" id="esewa_total_amount" value="0">
              <input type="hidden" name="transaction_uuid" id="esewa_transaction_uuid" value="">
              <input type="hidden" name="product_code" id="esewa_product_code" value="">
              <input type="hidden" name="signed_field_names" value="total_amount,transaction_uuid,product_code">
              <input type="hidden" name="signature" id="esewa_signature" value="">
              <input type="hidden" name="success_url" value="{{ route('esewa.success') }}">
              <input type="hidden" name="failure_url" value="{{ route('esewa.failure') }}">
              <button type="submit" class="px-4 py-2 rounded-lg bg-[#8D85EC] hover:bg-[#7b76e4] text-white font-semibold transition">
                  Pay Now
              </button>
          </form>

          </div>
        </div>
      </div>
    </div>
  </div>

</div>

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

function updateEsewaForm(price, tickets) {
    const totalAmount = (price * tickets).toFixed(2);

    // Send AJAX request to generate signature
    fetch("{{ route('esewa.generate-signature') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ total_amount: totalAmount })
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('esewa_total_amount').value = data.total_amount;
        document.getElementById('esewa_transaction_uuid').value = data.transaction_uuid;
        document.getElementById('esewa_product_code').value = data.product_code;
        document.getElementById('esewa_signature').value = data.signature;
    });
}

// Example: call this function whenever tickets quantity changes
// Assume 'selectedEvent' is your Alpine.js variable
document.querySelector('input[x-model="tickets"]').addEventListener('input', function() {
    if (selectedEvent) {
        updateEsewaForm(selectedEvent.price, this.value);
    }
});

// Also call once when modal opens
function onModalOpen() {
    if (selectedEvent) {
        const ticketsInput = document.querySelector('input[x-model="tickets"]');
        updateEsewaForm(selectedEvent.price, ticketsInput.value);
    }
}

</script>

@endsection
