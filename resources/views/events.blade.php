@extends('layouts.app')

@section('title', 'Events')

@section('content')

<div class="max-w-7xl mx-auto flex flex-col md:flex-row gap-6 p-4 dark:bg-gray-800">
  <!-- Filter Sidebar -->
  <div class="w-full md:w-64 bg-white p-4 rounded-xl shadow-lg md:sticky top-4 z-10 h-auto md:h-[80vh] overflow-y-auto dark:bg-gray-700">
    <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white flex items-center justify-between">
      <span>Filters</span>
      <button onclick="resetFilters()" class="text-xs text-[#8d85ec] hover:underline font-normal">Reset All</button>
    </h2>

    <!-- Date Filter Toggle Button -->
    <div class="mb-4 border-b border-gray-100 dark:border-gray-600 pb-3">
      <button class="flex items-center justify-between w-full text-left font-semibold text-gray-700 dark:text-gray-200 text-sm" onclick="toggleSection('dateFilter')">
        <span>Date Range</span>
        <svg id="icon-dateFilter" class="w-4 h-4 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      <div id="dateFilter" class="mt-3 hidden space-y-2">
        <div class="flex gap-2">
          <input type="date" id="startDate" value="{{ request('start_date') }}" class="border border-gray-300 dark:border-gray-600 rounded-lg px-2 py-1 text-xs w-1/2 dark:bg-gray-800 dark:text-white" />
          <input type="date" id="endDate" value="{{ request('end_date') }}" class="border border-gray-300 dark:border-gray-600 rounded-lg px-2 py-1 text-xs w-1/2 dark:bg-gray-800 dark:text-white" />
        </div>
        <button onclick="applyCustomRange()" class="w-full bg-[#8D85EC] hover:bg-[#7b76e4] text-white py-1.5 px-2 rounded-lg text-xs font-semibold transition">Apply Range</button>
      </div>
    </div>

    <!-- Categories Filter -->
    <div class="mb-4 border-b border-gray-100 dark:border-gray-600 pb-3">
      <button class="flex items-center justify-between w-full text-left font-semibold text-gray-700 dark:text-gray-200 text-sm" onclick="toggleSection('categoriesFilter')">
        <span>Categories</span>
        <svg id="icon-categoriesFilter" class="w-4 h-4 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      <div id="categoriesFilter" class="mt-3 hidden space-y-1.5">
        @php $activeCategory = request('category'); @endphp
        <a href="{{ route('events', array_merge(request()->except('category'), [])) }}"
           class="block text-xs py-1 px-2 rounded {{ !$activeCategory ? 'bg-purple-100 text-[#8d85ec] font-bold dark:bg-purple-900/50' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600' }}">All Categories</a>
        <a href="{{ route('events', array_merge(request()->except('category'), ['category' => 'Concert'])) }}"
           class="block text-xs py-1 px-2 rounded {{ $activeCategory == 'Concert' ? 'bg-purple-100 text-[#8d85ec] font-bold dark:bg-purple-900/50' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600' }}">Concert</a>
        <a href="{{ route('events', array_merge(request()->except('category'), ['category' => 'Art'])) }}"
           class="block text-xs py-1 px-2 rounded {{ $activeCategory == 'Art' ? 'bg-purple-100 text-[#8d85ec] font-bold dark:bg-purple-900/50' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600' }}">Exhibition / Art</a>
        <a href="{{ route('events', array_merge(request()->except('category'), ['category' => 'Food & Drink'])) }}"
           class="block text-xs py-1 px-2 rounded {{ $activeCategory == 'Food & Drink' || $activeCategory == 'Food and Drink' ? 'bg-purple-100 text-[#8d85ec] font-bold dark:bg-purple-900/50' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600' }}">Food & Drink</a>
        <a href="{{ route('events', array_merge(request()->except('category'), ['category' => 'Technology'])) }}"
           class="block text-xs py-1 px-2 rounded {{ $activeCategory == 'Technology' ? 'bg-purple-100 text-[#8d85ec] font-bold dark:bg-purple-900/50' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600' }}">Technology</a>
        <a href="{{ route('events', array_merge(request()->except('category'), ['category' => 'Sports'])) }}"
           class="block text-xs py-1 px-2 rounded {{ $activeCategory == 'Sports' ? 'bg-purple-100 text-[#8d85ec] font-bold dark:bg-purple-900/50' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600' }}">Sports</a>
        <a href="{{ route('events', array_merge(request()->except('category'), ['category' => 'Wellness'])) }}"
           class="block text-xs py-1 px-2 rounded {{ $activeCategory == 'Wellness' ? 'bg-purple-100 text-[#8d85ec] font-bold dark:bg-purple-900/50' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600' }}">Workshop / Wellness</a>
      </div>
    </div>

    <!-- Price Filter -->
    <div class="mb-4 border-b border-gray-100 dark:border-gray-600 pb-3">
      <button class="flex items-center justify-between w-full text-left font-semibold text-gray-700 dark:text-gray-200 text-sm" onclick="toggleSection('priceFilter')">
        <span>Max Budget</span>
        <svg id="icon-priceFilter" class="w-4 h-4 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      <div id="priceFilter" class="mt-3 hidden space-y-2">
        <div class="flex gap-2">
          <input type="number" id="minPrice" placeholder="Min Rs" value="{{ request('min_price') }}" class="border border-gray-300 dark:border-gray-600 rounded-lg px-2 py-1 text-xs w-1/2 dark:bg-gray-800 dark:text-white" />
          <input type="number" id="maxPrice" placeholder="Max Rs" value="{{ request('max_price') }}" class="border border-gray-300 dark:border-gray-600 rounded-lg px-2 py-1 text-xs w-1/2 dark:bg-gray-800 dark:text-white" />
        </div>
        <button onclick="applyPriceFilter()" class="w-full bg-[#8D85EC] hover:bg-[#7b76e4] text-white py-1.5 px-2 rounded-lg text-xs font-semibold transition">Filter Price</button>
      </div>
    </div>

    <!-- Venue Search -->
    <div class="mb-4">
      <button class="flex items-center justify-between w-full text-left font-semibold text-gray-700 dark:text-gray-200 text-sm" onclick="toggleSection('venueFilter')">
        <span>Search Venue</span>
        <svg id="icon-venueFilter" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      <div id="venueFilter" class="mt-3 hidden space-y-2">
        <input type="text" placeholder="e.g. Hotel, Stadium" value="{{ request('venue') }}" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-1.5 text-xs focus:ring-2 focus:ring-[#8d85ec] dark:bg-gray-800 dark:text-white" id="venueSearchInput" />
        <button onclick="searchVenues()" class="w-full bg-[#8D85EC] hover:bg-[#7b76e4] text-white py-1.5 px-2 rounded-lg text-xs font-semibold transition">Search</button>
      </div>
    </div>
  </div>

  <!-- Events Listing with Multi-Ticket-Type Booking Modal -->
  <div x-data="{
      openBookingId: null,
      selectedEvent: null,
      selectedTicketId: null,
      tickets: 1,
      showKhaltiPopup: false,
      phone: '',
      mpin: '',
      paymentError: '',
      
      initBooking(event) {
          this.selectedEvent = event;
          this.openBookingId = event.id;
          this.tickets = 1;
          this.phone = '';
          this.mpin = '';
          this.paymentError = '';
          this.showKhaltiPopup = false;
          
          // Auto-select first active available ticket type
          if (event.ticket_types && event.ticket_types.length > 0) {
              let firstAvail = event.ticket_types.find(t => t.status === 'active' && ((t.quantity - t.sold_quantity) > 0));
              this.selectedTicketId = firstAvail ? firstAvail.id : event.ticket_types[0].id;
          } else {
              this.selectedTicketId = null;
          }
      },

      getSelectedTicket() {
          if (!this.selectedEvent || !this.selectedEvent.ticket_types) return null;
          return this.selectedEvent.ticket_types.find(t => t.id === this.selectedTicketId) || null;
      },

      getTicketPrice() {
          let t = this.getSelectedTicket();
          return t ? Number(t.price) : (this.selectedEvent ? Number(this.selectedEvent.price) : 0);
      },

      getMaxTickets() {
          let t = this.getSelectedTicket();
          if (!t) return this.selectedEvent ? (this.selectedEvent.available_seats || 1) : 1;
          return Math.max(1, t.quantity - t.sold_quantity);
      },

      isSoldOut() {
          let t = this.getSelectedTicket();
          if (!t) return (this.selectedEvent && this.selectedEvent.available_seats <= 0);
          return (t.quantity - t.sold_quantity) <= 0;
      },

      getSubtotal() {
          return this.tickets * this.getTicketPrice();
      },

      getTotal() {
          return this.getSubtotal() + 5.65;
      }
  }" class="flex-1">
    
    <div class="flex justify-between items-center mb-6">
      <div>
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Upcoming Events</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Discover and book tickets for top events in Nepal.</p>
      </div>
      <span class="text-xs bg-purple-100 text-[#8d85ec] font-bold px-3 py-1 rounded-full dark:bg-purple-900/50">
        {{ $events->count() }} Event(s)
      </span>
    </div>

    @if(session('success'))
      <div class="bg-green-100 border border-green-300 text-green-800 p-4 rounded-xl mb-6 shadow-sm flex items-center justify-between">
        <div class="flex items-center gap-2">
          <span>✅</span>
          <span>{{ session('success') }}</span>
        </div>
        <a href="{{ route('usereventbook') }}" class="text-sm font-bold text-green-900 underline hover:no-underline">View My Tickets &rarr;</a>
      </div>
    @endif

    @if($events->count() > 0)
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($events as $event)
          @php
            $activeTickets = $event->ticketTypes->where('status', 'active');
            $minPrice = $activeTickets->isNotEmpty() ? $activeTickets->min('price') : $event->price;
            $maxPrice = $activeTickets->isNotEmpty() ? $activeTickets->max('price') : $event->price;
            $totalRemaining = $activeTickets->isNotEmpty() ? $activeTickets->sum(fn($t) => max(0, $t->quantity - $t->sold_quantity)) : $event->available_seats;
          @endphp
          <div class="rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition transform hover:-translate-y-1 hover:scale-[1.02] w-full bg-white dark:bg-gray-700 flex flex-col justify-between border border-gray-100 dark:border-gray-600">
              <div>
                  <div class="relative h-52 w-full overflow-hidden">
                      <img src="{{ asset('uploads/' . $event->image) }}" alt="{{ $event->event_name }}" class="h-full w-full object-cover" />
                      @if($event->category)
                          <span class="absolute top-3 right-3 bg-black/60 backdrop-blur-sm text-white text-xs font-bold px-3 py-1 rounded-full">
                              {{ $event->category }}
                          </span>
                      @endif
                  </div>

                  <div class="p-5 flex flex-col gap-2 text-gray-900 dark:text-gray-200">
                      <h3 class="text-lg font-bold truncate text-gray-900 dark:text-white">{{ $event->event_name }}</h3>
                      <p class="text-xs text-gray-500 dark:text-gray-400 truncate flex items-center gap-1">
                          <span>📍</span> {{ $event->venue }}
                      </p>
                      <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                          <span>📅</span> {{ \Carbon\Carbon::parse($event->event_date)->format('d M, Y - h:i A') }}
                      </p>
                      <p class="text-xs text-gray-600 dark:text-gray-300 line-clamp-2 mt-1">{{ $event->description }}</p>
                      
                      <!-- Ticket Types Pill Badges -->
                      <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-600">
                          <div class="flex justify-between items-center mb-1.5">
                              <span class="text-xs font-bold text-[#8d85ec]">
                                  @if($minPrice == $maxPrice)
                                      Rs {{ number_format($minPrice, 2) }}
                                  @else
                                      From Rs {{ number_format($minPrice, 0) }}
                                  @endif
                              </span>
                              <span class="text-[11px] text-gray-500 dark:text-gray-400">
                                  {{ $totalRemaining }} seats left
                              </span>
                          </div>

                          <div class="flex flex-wrap gap-1">
                              @forelse($activeTickets as $ticket)
                                  <span class="text-[10px] bg-purple-50 dark:bg-gray-800 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800 px-2 py-0.5 rounded-full font-medium">
                                      {{ $ticket->name }}: Rs {{ number_format($ticket->price, 0) }}
                                  </span>
                              @empty
                                  <span class="text-[10px] text-gray-400">Standard</span>
                              @endforelse
                          </div>
                      </div>
                  </div>
              </div>

              <div class="p-5 pt-0">
                  @guest
                      <a href="{{ route('login') }}"
                        class="block text-center w-full bg-[#8D85EC] hover:bg-[#7b76e4] text-white font-semibold text-sm py-2.5 px-4 rounded-xl transition shadow-md">
                          Book Now
                      </a>
                  @endguest

                  @auth
                      <button 
                        @click="initBooking({{ $event->toJson() }})"
                        class="w-full bg-[#8D85EC] hover:bg-[#7b76e4] text-white font-semibold text-sm py-2.5 px-4 rounded-xl transition shadow-md transform active:scale-95">
                          Book Tickets
                      </button>
                  @endauth
              </div>
          </div>
        @endforeach
      </div>
    @else
      <div class="text-center mt-20 bg-white dark:bg-gray-700 p-12 rounded-2xl shadow-sm">
          <span class="text-5xl">🎪</span>
          <p class="text-gray-700 dark:text-gray-200 text-lg font-semibold mt-4">No events found matching your criteria.</p>
          <button onclick="resetFilters()" class="mt-4 bg-[#8d85ec] text-white px-5 py-2 rounded-lg text-sm font-semibold hover:opacity-90">Reset Filters</button>
      </div>
    @endif

    <!-- Interactive Multi-Ticket-Type Booking Modal -->
    <div 
        x-show="openBookingId !== null" 
        x-transition.opacity
        x-cloak
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4"
    >
        <!-- Modal Content Box -->
        <div 
            @click.away="openBookingId = null; selectedEvent = null" 
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-6 sm:p-8 w-full max-w-lg border border-gray-200 dark:border-gray-700 transform transition-all max-h-[90vh] overflow-y-auto"
        >
            <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white truncate">
                    Book: <span class="text-[#8d85ec]" x-text="selectedEvent ? selectedEvent.event_name : ''"></span>
                </h2>
                <button @click="openBookingId = null; selectedEvent = null" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-xl font-bold">
                    &times;
                </button>
            </div>

            <template x-if="selectedEvent">
                <div class="space-y-5">
                    
                    <!-- 1. CHOOSE TICKET TYPE -->
                    <div>
                        <label class="block text-sm font-bold text-gray-800 dark:text-gray-200 mb-2">
                            1. Choose Ticket Type:
                        </label>

                        <div class="space-y-2.5">
                            <template x-for="ticket in (selectedEvent.ticket_types || [])" :key="ticket.id">
                                <label 
                                    :class="{
                                        'border-[#8d85ec] bg-purple-50/70 dark:bg-purple-950/30 ring-2 ring-[#8d85ec]': selectedTicketId === ticket.id,
                                        'border-gray-200 dark:border-gray-700 hover:border-purple-300 dark:hover:border-purple-600': selectedTicketId !== ticket.id,
                                        'opacity-50 cursor-not-allowed bg-gray-50 dark:bg-gray-800': (ticket.quantity - ticket.sold_quantity) <= 0
                                    }"
                                    class="flex items-start justify-between p-3.5 rounded-xl border transition cursor-pointer"
                                >
                                    <div class="flex items-start gap-3">
                                        <input 
                                            type="radio" 
                                            name="modal_ticket_type" 
                                            :value="ticket.id" 
                                            x-model="selectedTicketId" 
                                            :disabled="(ticket.quantity - ticket.sold_quantity) <= 0"
                                            class="mt-1 text-[#8d85ec] focus:ring-[#8d85ec]"
                                        >
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-gray-900 dark:text-white text-sm" x-text="ticket.name"></span>
                                                <template x-if="(ticket.quantity - ticket.sold_quantity) <= 0">
                                                    <span class="text-[10px] bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300 px-2 py-0.5 rounded-full font-bold">Sold Out</span>
                                                </template>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5" x-text="ticket.description || 'Standard admission'"></p>
                                            
                                            <!-- Remaining capacity text -->
                                            <div class="mt-1 text-[11px]">
                                                <template x-if="(ticket.quantity - ticket.sold_quantity) > 5">
                                                    <span class="text-gray-500 dark:text-gray-400" x-text="(ticket.quantity - ticket.sold_quantity) + ' tickets available'"></span>
                                                </template>
                                                <template x-if="(ticket.quantity - ticket.sold_quantity) > 0 && (ticket.quantity - ticket.sold_quantity) <= 5">
                                                    <span class="text-amber-600 dark:text-amber-400 font-bold" x-text="'Only ' + (ticket.quantity - ticket.sold_quantity) + ' tickets remaining!'"></span>
                                                </template>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <span class="text-base font-extrabold text-[#8d85ec]" x-text="'Rs ' + Number(ticket.price).toFixed(2)"></span>
                                    </div>
                                </label>
                            </template>
                        </div>
                    </div>

                    <!-- 2. QUANTITY SELECTOR -->
                    <div>
                        <label class="block text-sm font-bold text-gray-800 dark:text-gray-200 mb-2">
                            2. Select Quantity:
                        </label>
                        <div class="flex items-center gap-3">
                            <button 
                                type="button" 
                                @click="if(tickets > 1) tickets--" 
                                :disabled="tickets <= 1 || isSoldOut()"
                                class="w-10 h-10 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white font-bold text-lg hover:bg-gray-300 transition flex items-center justify-center disabled:opacity-40"
                            >-</button>
                            <input 
                                type="number" 
                                x-model.number="tickets" 
                                min="1" 
                                :max="getMaxTickets()" 
                                :disabled="isSoldOut()"
                                class="w-24 text-center border border-gray-300 dark:border-gray-600 rounded-lg py-2 text-gray-900 dark:text-white font-bold focus:ring-2 focus:ring-[#8d85ec] focus:outline-none dark:bg-gray-700"
                            >
                            <button 
                                type="button" 
                                @click="if(tickets < getMaxTickets()) tickets++" 
                                :disabled="tickets >= getMaxTickets() || isSoldOut()"
                                class="w-10 h-10 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white font-bold text-lg hover:bg-gray-300 transition flex items-center justify-center disabled:opacity-40"
                            >+</button>
                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-2" x-text="'Max: ' + getMaxTickets() + ' ticket(s)'"></span>
                        </div>
                    </div>

                    <!-- 3. REAL-TIME PRICE BREAKDOWN -->
                    <div class="bg-gray-50 dark:bg-gray-700/60 p-4 rounded-xl border border-gray-200 dark:border-gray-600 space-y-2 text-sm">
                        <div class="flex justify-between text-gray-600 dark:text-gray-300">
                            <span>Selected Ticket:</span>
                            <span class="font-semibold text-gray-900 dark:text-white" x-text="getSelectedTicket() ? getSelectedTicket().name : 'None'"></span>
                        </div>
                        <div class="flex justify-between text-gray-600 dark:text-gray-300">
                            <span>Price per Ticket:</span>
                            <span class="font-semibold text-gray-900 dark:text-white" x-text="'Rs ' + getTicketPrice().toFixed(2)"></span>
                        </div>
                        <div class="flex justify-between text-gray-600 dark:text-gray-300">
                            <span>Subtotal (<span x-text="tickets"></span> &times; Rs <span x-text="getTicketPrice().toFixed(0)"></span>):</span>
                            <span class="font-semibold text-gray-900 dark:text-white" x-text="'Rs ' + getSubtotal().toFixed(2)"></span>
                        </div>
                        <div class="flex justify-between text-gray-600 dark:text-gray-300">
                            <span>Service Charge:</span>
                            <span class="font-semibold text-gray-900 dark:text-white">Rs 5.65</span>
                        </div>
                        <div class="border-t border-gray-200 dark:border-gray-600 pt-2 flex justify-between items-center">
                            <span class="font-bold text-gray-900 dark:text-white text-base">Total Amount:</span>
                            <span class="font-extrabold text-[#8d85ec] text-xl" x-text="'Rs ' + getTotal().toFixed(2)"></span>
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="openBookingId = null; selectedEvent = null" 
                            class="px-5 py-2.5 rounded-xl bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 font-semibold text-sm transition">
                            Cancel
                        </button>

                        <button
                            @click="if(!isSoldOut() && selectedTicketId) { showKhaltiPopup = true; }"
                            :disabled="isSoldOut() || !selectedTicketId"
                            class="px-6 py-2.5 rounded-xl text-white font-bold text-sm transition transform hover:scale-[1.02] active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                            style="background: linear-gradient(90deg, #8D85EC 0%, #7a72d6 100%); box-shadow: 0 4px 15px rgba(141, 133, 236, 0.4);">
                            <span x-text="isSoldOut() ? 'Sold Out' : 'Proceed to Pay with Khalti'"></span>
                        </button>
                    </div>

                    <!-- Khalti Modal Popup -->
                    <div x-show="showKhaltiPopup" x-transition.opacity
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4">
                        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl max-w-4xl w-full overflow-hidden flex flex-col md:flex-row border border-gray-200 dark:border-gray-700">

                            <!-- Left Section: Payment Summary -->
                            <div class="w-full md:w-2/3 bg-[#FAF9FC] dark:bg-gray-800 p-6 flex flex-col justify-between gap-6">
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Payment Details</h2>
                                    <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">Complete your ticket booking via official Khalti verification</p>

                                    <!-- User & Event Info -->
                                    <div class="mt-4 bg-white dark:bg-gray-700 p-4 rounded-xl border border-gray-200 dark:border-gray-600 space-y-1">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-bold tracking-wider">Booking For</p>
                                        <p class="font-bold text-gray-900 dark:text-white text-base" x-text="selectedEvent.event_name"></p>
                                        <p class="text-xs text-purple-600 dark:text-purple-300 font-semibold" x-text="'Ticket Tier: ' + (getSelectedTicket() ? getSelectedTicket().name : '') + ' (' + tickets + ' ticket(s))'"></p>
                                        <p class="text-xs text-gray-600 dark:text-gray-300" x-text="'Billed to: {{ Auth::user()->name ?? 'User' }} ({{ Auth::user()->email ?? '' }})'"></p>
                                    </div>

                                    <!-- Amount Summary -->
                                    <div class="mt-4 bg-white dark:bg-gray-700 p-4 rounded-xl border border-gray-200 dark:border-gray-600 space-y-2 text-sm">
                                        <div class="flex justify-between text-gray-600 dark:text-gray-300">
                                            <span>Ticket Price (<span x-text="tickets"></span> &times; Rs <span x-text="getTicketPrice().toFixed(0)"></span>)</span>
                                            <span class="font-semibold text-gray-900 dark:text-white" x-text="'Rs ' + getSubtotal().toFixed(2)"></span>
                                        </div>
                                        <div class="flex justify-between text-gray-600 dark:text-gray-300">
                                            <span>Service Charge</span>
                                            <span class="font-semibold text-gray-900 dark:text-white">Rs 5.65</span>
                                        </div>
                                        <div class="border-t border-gray-200 dark:border-gray-600 my-2"></div>
                                        <div class="flex justify-between font-extrabold text-gray-900 dark:text-white text-lg">
                                            <span>Total Payable</span>
                                            <span class="text-[#8D85EC]" x-text="'Rs ' + getTotal().toFixed(2)"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-[#7B2CBF] text-white text-xs font-semibold py-2 px-4 text-center rounded-xl">
                                    PAYMENT POWERED BY <span class="ml-1 font-bold">KHALTI WALLET</span>
                                </div>
                            </div>

                            <!-- Right Section: Khalti Wallet Credentials -->
                            <div class="w-full md:w-1/3 bg-white dark:bg-gray-900 p-6 flex flex-col justify-between gap-4 relative">
                                <button @click="showKhaltiPopup=false; paymentError='';" 
                                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 dark:hover:text-white text-lg font-bold">
                                    ✕
                                </button>

                                <div>
                                    <div class="flex items-center justify-center mb-3">
                                        <img src="uploads/khalti.png" alt="Khalti Logo" class="h-8">
                                    </div>
                                    <h3 class="text-base font-bold text-gray-900 dark:text-white text-center">Pay via Khalti Wallet</h3>
                                    <p class="text-gray-500 dark:text-gray-400 text-xs text-center mt-1">Enter your Khalti Mobile Number and MPIN</p>

                                    <div class="mt-4 space-y-3">
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">Khalti Mobile Number</label>
                                            <input type="text" x-model="phone" placeholder="e.g. 9800000000"
                                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#8D85EC] dark:bg-gray-800 dark:text-white outline-none">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">Khalti MPIN</label>
                                            <input type="password" x-model="mpin" placeholder="MPIN (1111)"
                                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#8D85EC] dark:bg-gray-800 dark:text-white outline-none">
                                        </div>
                                        <p class="text-red-500 text-xs font-semibold" x-text="paymentError"></p>
                                    </div>
                                </div>

                                <div>
                                    <button @click="
                                        paymentError='';
                                        let allowedPhones = ['9800000000','9800000001','9800000002','9800000003','9800000004','9800000005'];
                                        if (allowedPhones.includes(phone.trim()) && mpin === '1111') {
                                            let eventToSave = {...selectedEvent};
                                            let ticketTypeId = selectedTicketId;
                                            let count = tickets;
                                            showKhaltiPopup = false;
                                            openBookingId = null;
                                            selectedEvent = null;
                                            saveBooking(eventToSave, ticketTypeId, count);
                                        } else {
                                            paymentError = '❌ Invalid Khalti ID or MPIN. (Use test phone 9800000000 and PIN 1111)';
                                        }"
                                        class="w-full py-3 rounded-xl text-white font-bold text-sm transition transform hover:scale-[1.02] active:scale-95 shadow-md"
                                        style="background: linear-gradient(90deg,#8D85EC 0%,#6E29B0 100%);">
                                        Confirm & Pay
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </template>
        </div>
    </div>

  </div>
</div>

<script>
function toggleSection(id) {
    const section = document.getElementById(id);
    const icon = document.getElementById('icon-' + id);
    section.classList.toggle('hidden');
    if(icon) icon.classList.toggle('rotate-180');
}

function getQueryParams() {
    return new URLSearchParams(window.location.search);
}

function setQueryParams(params) {
    window.location.search = params.toString();
}

function resetFilters() {
  window.location.href = "{{ route('events') }}";
}

function applyCustomRange() {
    const start = document.getElementById('startDate').value;
    const end = document.getElementById('endDate').value;
    const params = getQueryParams();
    if (start) params.set('start_date', start);
    else params.delete('start_date');
    if (end) params.set('end_date', end);
    else params.delete('end_date');
    setQueryParams(params);
}

function applyPriceFilter() {
    const minPrice = document.getElementById('minPrice').value;
    const maxPrice = document.getElementById('maxPrice').value;
    const params = getQueryParams();
    if (minPrice) params.set('min_price', minPrice);
    else params.delete('min_price');
    if (maxPrice) params.set('max_price', maxPrice);
    else params.delete('max_price');
    setQueryParams(params);
}

function searchVenues() {
    const input = document.getElementById('venueSearchInput').value.trim();
    const params = getQueryParams();
    if(input) params.set('venue', input);
    else params.delete('venue');
    setQueryParams(params);
}

// Server-side safe booking function with ticket_type_id
function saveBooking(event, ticketTypeId, tickets) {
  fetch("{{ route('khalti.saveBooking') }}", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "Accept": "application/json",
      "X-CSRF-TOKEN": "{{ csrf_token() }}"
    },
    body: JSON.stringify({
      event_id: event.id,
      ticket_type_id: ticketTypeId,
      tickets: tickets
    })
  })
  .then(async response => {
    const data = await response.json();
    if (response.ok && data.success) {
      alert("✅ Payment & Booking Successful! Your ticket has been emailed to you.");
      window.location.href = "{{ route('usereventbook') }}";
    } else {
      console.error("Booking error:", data);
      alert("❌ " + (data.message || "Failed to complete booking."));
    }
  })
  .catch(err => {
    console.error("Fetch error:", err);
    alert("⚠️ Network or server error. Please try again.");
  });
}
</script>

@endsection