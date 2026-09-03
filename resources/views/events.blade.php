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
      activeModal: null, // null | 'booking' | 'khalti'
      selectedEvent: null,
      selectedTicketId: null,
      tickets: 1,
      phone: '',
      mpin: '',
      paymentError: '',
      isSubmitting: false,
      
      init() {
          this.$watch('activeModal', (value) => {
              if (value) {
                  document.body.style.overflow = 'hidden';
              } else {
                  document.body.style.overflow = '';
              }
          });
      },

      initBooking(event) {
          this.selectedEvent = event;
          this.tickets = 1;
          this.phone = '';
          this.mpin = '';
          this.paymentError = '';
          this.isSubmitting = false;
          
          // Auto-select first active available ticket type
          if (event.ticket_types && event.ticket_types.length > 0) {
              let firstAvail = event.ticket_types.find(t => t.status === 'active' && ((t.quantity - t.sold_quantity) > 0));
              this.selectedTicketId = firstAvail ? firstAvail.id : event.ticket_types[0].id;
          } else {
              this.selectedTicketId = null;
          }

          this.activeModal = 'booking';
      },

      proceedToKhalti() {
          if (this.isSoldOut() || !this.selectedTicketId) return;
          this.paymentError = '';
          this.activeModal = 'khalti';
      },

      backToBooking() {
          this.paymentError = '';
          this.activeModal = 'booking';
      },

      closeModal() {
          this.activeModal = null;
          this.paymentError = '';
          this.phone = '';
          this.mpin = '';
          this.isSubmitting = false;
          document.body.style.overflow = '';
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
      },

      confirmPayment() {
          this.paymentError = '';
          let allowedPhones = ['9800000000','9800000001','9800000002','9800000003','9800000004','9800000005'];
          if (allowedPhones.includes(this.phone.trim()) && this.mpin === '1111') {
              this.isSubmitting = true;
              let eventToSave = {...this.selectedEvent};
              let ticketTypeId = this.selectedTicketId;
              let count = this.tickets;
              
              saveBooking(eventToSave, ticketTypeId, count, () => {
                  this.closeModal();
              }, (err) => {
                  this.isSubmitting = false;
                  this.paymentError = err || 'Booking failed. Please try again.';
              });
          } else {
              this.paymentError = '❌ Invalid Khalti ID or MPIN. (Use test phone 9800000000 and PIN 1111)';
          }
      }
  }" 
  @keydown.escape.window="closeModal()"
  class="flex-1">
    
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

    <!-- 1. Full-screen Background Overlay (Strong Blur + Darkened) -->
    <div
        x-show="activeModal !== null"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-cloak
        class="fixed inset-0 z-[100] bg-slate-900/60 backdrop-blur-xl modal-backdrop-blur"
        style="backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); background-color: rgba(15, 23, 42, 0.60);"
        @click="closeModal()"
        aria-hidden="true"
    ></div>

    <!-- 2. Full-screen Active Modal Container (Sharp and Clear Content) -->
    <div
        x-show="activeModal === 'booking' || activeModal === 'khalti'"
        x-cloak
        class="fixed inset-0 z-[110] flex items-center justify-center p-4 sm:p-6 overflow-y-auto pointer-events-none"
    >
        <!-- 1. BOOKING MODAL -->
        <div 
            x-show="activeModal === 'booking'" 
            x-transition:enter="transition ease-out duration-250"
            x-transition:enter-start="opacity-0 scale-95 translate-y-3"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-3"
            @click.stop
            class="relative w-full max-w-lg bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-purple-100 dark:border-gray-700 my-auto overflow-hidden flex flex-col max-h-[90vh] pointer-events-auto"
        >
            <!-- Booking Modal Header -->
            <div class="p-5 sm:p-6 border-b border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 flex items-start justify-between gap-3">
                <div class="flex-1 min-w-0">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-[#8D85EC]/15 text-[#6f69d9] dark:bg-purple-900/50 dark:text-purple-300">
                        🎟️ Book Tickets
                    </span>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white truncate mt-2" x-text="selectedEvent ? selectedEvent.event_name : ''"></h2>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-gray-500 dark:text-gray-400 mt-1">
                        <span class="flex items-center gap-1 truncate">
                            <span>📍</span> <span x-text="selectedEvent ? selectedEvent.venue : ''"></span>
                        </span>
                        <span class="flex items-center gap-1">
                            <span>📅</span> <span x-text="selectedEvent ? selectedEvent.event_date : ''"></span>
                        </span>
                    </div>
                </div>
                <button 
                    type="button"
                    @click="closeModal()" 
                    class="w-9 h-9 rounded-full flex items-center justify-center text-gray-400 hover:text-gray-700 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 transition shrink-0"
                    aria-label="Close modal"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Booking Modal Scrollable Body -->
            <div class="p-5 sm:p-6 space-y-5 overflow-y-auto">
                <template x-if="selectedEvent">
                    <div class="space-y-5">
                        <!-- 1. CHOOSE TICKET TYPE -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-2.5">
                                1. Select Ticket Tier
                            </label>

                            <div class="space-y-2.5">
                                <template x-for="ticket in (selectedEvent.ticket_types || [])" :key="ticket.id">
                                    <label 
                                        :class="{
                                            'border-[#8D85EC] bg-purple-50/70 dark:bg-purple-950/30 ring-2 ring-[#8D85EC] shadow-sm': selectedTicketId === ticket.id,
                                            'border-gray-200 dark:border-gray-700 hover:border-[#8D85EC]/60 hover:bg-gray-50/50 dark:hover:bg-gray-700/50': selectedTicketId !== ticket.id,
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
                                                class="mt-1 text-[#8D85EC] focus:ring-[#8D85EC] border-gray-300"
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
                                                        <span class="text-amber-600 dark:text-amber-400 font-bold" x-text="'🔥 Only ' + (ticket.quantity - ticket.sold_quantity) + ' tickets remaining!'"></span>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-right shrink-0">
                                            <span class="text-base font-extrabold text-[#6f69d9] dark:text-[#8D85EC]" x-text="'Rs ' + Number(ticket.price).toFixed(2)"></span>
                                        </div>
                                    </label>
                                </template>
                            </div>
                        </div>

                        <!-- 2. QUANTITY SELECTOR -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-2.5">
                                2. Select Quantity
                            </label>
                            <div class="flex items-center gap-3">
                                <button 
                                    type="button" 
                                    @click="if(tickets > 1) tickets--" 
                                    :disabled="tickets <= 1 || isSoldOut()"
                                    class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white font-bold text-lg hover:bg-purple-100 hover:text-[#6f69d9] dark:hover:bg-gray-600 transition flex items-center justify-center disabled:opacity-40 disabled:cursor-not-allowed shadow-sm"
                                >-</button>
                                <input 
                                    type="number" 
                                    x-model.number="tickets" 
                                    min="1" 
                                    :max="getMaxTickets()" 
                                    :disabled="isSoldOut()"
                                    class="w-20 text-center border border-gray-300 dark:border-gray-600 rounded-xl py-2 text-gray-900 dark:text-white font-bold text-base focus:ring-2 focus:ring-[#8D85EC] focus:border-[#8D85EC] focus:outline-none dark:bg-gray-700"
                                >
                                <button 
                                    type="button" 
                                    @click="if(tickets < getMaxTickets()) tickets++" 
                                    :disabled="tickets >= getMaxTickets() || isSoldOut()"
                                    class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white font-bold text-lg hover:bg-purple-100 hover:text-[#6f69d9] dark:hover:bg-gray-600 transition flex items-center justify-center disabled:opacity-40 disabled:cursor-not-allowed shadow-sm"
                                >+</button>
                                <span class="text-xs text-gray-500 dark:text-gray-400 ml-1" x-text="'Max: ' + getMaxTickets() + ' ticket(s)'"></span>
                            </div>
                        </div>

                        <!-- 3. REAL-TIME PRICE BREAKDOWN -->
                        <div class="bg-[#f7f7fc] dark:bg-gray-700/60 p-4 rounded-2xl border border-purple-100 dark:border-gray-600 space-y-2.5 text-xs sm:text-sm">
                            <div class="flex justify-between text-gray-600 dark:text-gray-300">
                                <span>Selected Ticket Tier:</span>
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
                            <div class="border-t border-purple-200/70 dark:border-gray-600 pt-2.5 flex justify-between items-center">
                                <span class="font-bold text-gray-900 dark:text-white text-sm sm:text-base">Total Payable:</span>
                                <span class="font-black text-[#6f69d9] dark:text-[#8D85EC] text-xl" x-text="'Rs ' + getTotal().toFixed(2)"></span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Booking Modal Footer Actions -->
            <div class="p-4 sm:p-5 bg-gray-50 dark:bg-gray-800/80 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-3">
                <button 
                    type="button" 
                    @click="closeModal()" 
                    class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 font-semibold text-sm transition"
                >
                    Cancel
                </button>

                <button
                    type="button"
                    @click="proceedToKhalti()"
                    :disabled="isSoldOut() || !selectedTicketId"
                    class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl text-white font-bold text-sm transition shadow-md hover:shadow-lg transform active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                    style="background: linear-gradient(135deg, #8D85EC 0%, #7b76e4 50%, #6f69d9 100%);"
                >
                    <span x-text="isSoldOut() ? 'Sold Out' : 'Proceed to Pay with Khalti'"></span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- 2. KHALTI PAYMENT MODAL (MUTUALLY EXCLUSIVE SIBLING) -->
        <div 
            x-show="activeModal === 'khalti'" 
            x-transition:enter="transition ease-out duration-250"
            x-transition:enter-start="opacity-0 scale-95 translate-y-3"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-3"
            @click.stop
            class="relative w-full max-w-3xl lg:max-w-4xl bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-purple-100 dark:border-gray-700 my-auto overflow-hidden flex flex-col md:flex-row max-h-[90vh] pointer-events-auto"
        >
            <!-- Left Side: Order & Payment Summary -->
            <div class="w-full md:w-5/12 bg-[#f7f7fc] dark:bg-gray-800 p-6 sm:p-7 flex flex-col justify-between border-b md:border-b-0 md:border-r border-purple-100 dark:border-gray-700 overflow-y-auto">
                <div>
                    <!-- Back Button -->
                    <button 
                        type="button" 
                        @click="backToBooking()" 
                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#6f69d9] hover:text-[#8D85EC] dark:text-purple-300 dark:hover:text-purple-200 mb-4 transition"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        &larr; Back to Ticket Selection
                    </button>

                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Payment Summary</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Please review your ticket details before checkout.</p>

                    <template x-if="selectedEvent">
                        <div class="mt-4 space-y-3">
                            <!-- Booking For Card -->
                            <div class="bg-white dark:bg-gray-700 p-4 rounded-xl border border-purple-100/80 dark:border-gray-600 shadow-sm space-y-1">
                                <p class="text-[10px] uppercase tracking-wider font-bold text-[#8D85EC]">Booking For</p>
                                <p class="font-bold text-gray-900 dark:text-white text-sm" x-text="selectedEvent.event_name"></p>
                                <p class="text-xs text-purple-700 dark:text-purple-300 font-semibold" x-text="'Tier: ' + (getSelectedTicket() ? getSelectedTicket().name : '') + ' (' + tickets + ' ticket' + (tickets > 1 ? 's' : '') + ')'"></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1" x-text="'Billed to: {{ Auth::user()->name ?? 'User' }}'"></p>
                            </div>

                            <!-- Amount Breakdown -->
                            <div class="bg-white dark:bg-gray-700 p-4 rounded-xl border border-purple-100/80 dark:border-gray-600 shadow-sm space-y-2 text-xs">
                                <div class="flex justify-between text-gray-600 dark:text-gray-300">
                                    <span>Tickets (<span x-text="tickets"></span> &times; Rs <span x-text="getTicketPrice().toFixed(0)"></span>)</span>
                                    <span class="font-semibold text-gray-900 dark:text-white" x-text="'Rs ' + getSubtotal().toFixed(2)"></span>
                                </div>
                                <div class="flex justify-between text-gray-600 dark:text-gray-300">
                                    <span>Service Charge</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">Rs 5.65</span>
                                </div>
                                <div class="border-t border-gray-200 dark:border-gray-600 pt-2 flex justify-between items-center text-sm font-bold">
                                    <span class="text-gray-900 dark:text-white">Total Amount</span>
                                    <span class="text-[#6f69d9] dark:text-[#8D85EC] font-extrabold text-base" x-text="'Rs ' + getTotal().toFixed(2)"></span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="mt-6 pt-4 border-t border-purple-100 dark:border-gray-700 flex items-center justify-center gap-2 text-[11px] text-gray-500 dark:text-gray-400">
                    <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V8H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 7V5.5a3 3 0 10-6 0V8h6z" clip-rule="evenodd" />
                    </svg>
                    <span>Official Khalti Sandbox Verification</span>
                </div>
            </div>

            <!-- Right Side: Khalti Wallet Credentials -->
            <div class="w-full md:w-7/12 bg-white dark:bg-gray-900 p-6 sm:p-8 flex flex-col justify-between relative overflow-y-auto">
                <!-- Close Button -->
                <button 
                    type="button" 
                    @click="closeModal()" 
                    class="absolute top-4 right-4 w-9 h-9 rounded-full flex items-center justify-center text-gray-400 hover:text-gray-700 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 transition"
                    aria-label="Close modal"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div class="space-y-4">
                    <!-- Khalti Brand Header -->
                    <div class="text-center pt-2">
                        <img src="{{ asset('uploads/khalti.png') }}" alt="Khalti Logo" class="h-9 mx-auto object-contain">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mt-2">Pay via Khalti Wallet</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Enter your Khalti credentials to complete this payment.</p>
                    </div>

                    <!-- Sandbox credentials hint card -->
                    <div class="bg-purple-50 dark:bg-purple-950/40 border border-purple-200 dark:border-purple-800/60 rounded-xl p-3 text-xs text-purple-900 dark:text-purple-200">
                        <p class="font-bold flex items-center gap-1.5 mb-1 text-[#6f69d9] dark:text-purple-300">
                            <span>💡</span> Test Sandbox Credentials
                        </p>
                        <div class="flex flex-wrap items-center gap-2 text-[11px]">
                            <span>Phone: <code class="bg-white dark:bg-gray-800 px-1.5 py-0.5 rounded font-mono font-bold text-purple-700 dark:text-purple-300 border border-purple-100 dark:border-purple-900">9800000000</code></span>
                            <span class="text-purple-300 dark:text-purple-600">|</span>
                            <span>MPIN: <code class="bg-white dark:bg-gray-800 px-1.5 py-0.5 rounded font-mono font-bold text-purple-700 dark:text-purple-300 border border-purple-100 dark:border-purple-900">1111</code></span>
                        </div>
                    </div>

                    <!-- Form Inputs -->
                    <div class="space-y-3.5 pt-1">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">
                                Khalti Mobile Number
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 text-sm">📱</span>
                                <input 
                                    type="text" 
                                    x-model="phone" 
                                    placeholder="9800000000"
                                    maxlength="10"
                                    class="w-full pl-9 pr-3 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:bg-white dark:focus:bg-gray-900 focus:ring-2 focus:ring-[#8D85EC] focus:border-[#8D85EC] outline-none transition"
                                >
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">
                                Khalti MPIN
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 text-sm">🔒</span>
                                <input 
                                    type="password" 
                                    x-model="mpin" 
                                    placeholder="1111"
                                    maxlength="6"
                                    class="w-full pl-9 pr-3 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:bg-white dark:focus:bg-gray-900 focus:ring-2 focus:ring-[#8D85EC] focus:border-[#8D85EC] outline-none transition"
                                >
                            </div>
                        </div>

                        <!-- Error Banner -->
                        <template x-if="paymentError">
                            <div class="p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-xl text-xs flex items-start gap-2">
                                <span class="shrink-0 mt-0.5">⚠️</span>
                                <span x-text="paymentError"></span>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Payment Action Button -->
                <div class="mt-6 pt-2">
                    <button 
                        type="button"
                        @click="confirmPayment()"
                        :disabled="isSubmitting || !phone || !mpin"
                        class="w-full py-3 px-4 rounded-xl text-white font-bold text-sm transition shadow-lg hover:shadow-xl transform active:scale-98 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                        style="background: linear-gradient(135deg, #8D85EC 0%, #7b76e4 50%, #5C2D91 100%);"
                    >
                        <template x-if="isSubmitting">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                        </template>
                        <span x-text="isSubmitting ? 'Processing Payment...' : 'Confirm & Pay Rs ' + getTotal().toFixed(2)"></span>
                    </button>
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
function saveBooking(event, ticketTypeId, tickets, onSuccess, onError) {
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
      if (typeof onSuccess === 'function') onSuccess(data);
      alert("✅ Payment & Booking Successful! Your ticket has been emailed to you.");
      window.location.href = "{{ route('usereventbook') }}";
    } else {
      console.error("Booking error:", data);
      const errMsg = data.message || "Failed to complete booking.";
      if (typeof onError === 'function') onError(errMsg);
      alert("❌ " + errMsg);
    }
  })
  .catch(err => {
    console.error("Fetch error:", err);
    const networkErr = "Network or server error. Please try again.";
    if (typeof onError === 'function') onError(networkErr);
    alert("⚠️ " + networkErr);
  });
}
</script>

@endsection