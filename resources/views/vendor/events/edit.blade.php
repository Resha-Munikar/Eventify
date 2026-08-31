@extends('layouts.app')

@section('title', 'Edit Event')
@php 
    $noNavbar = true; 
    $noFooter = true; 

    $initialTickets = old('ticket_types', $event->ticketTypes->map(function($t) {
        return [
            'id' => $t->id,
            'name' => $t->name,
            'price' => (float)$t->price,
            'quantity' => (int)$t->quantity,
            'sold_quantity' => (int)$t->sold_quantity,
            'description' => $t->description ?? '',
            'status' => $t->status ?? 'active',
        ];
    })->toArray());

    if (empty($initialTickets)) {
        $initialTickets = [
            ['id' => null, 'name' => 'General Admission', 'price' => (float)$event->price, 'quantity' => (int)$event->available_seats, 'sold_quantity' => 0, 'description' => '', 'status' => 'active']
        ];
    }
@endphp
@include('vendor.sidebar')

@section('content')
<div class="ml-0 sm:ml-64 p-6 bg-gray-100 dark:bg-gray-900 min-h-screen overflow-x-hidden">
    <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-8">
        <h2 class="text-3xl font-bold text-[#8d85ec] mb-6 text-center">Edit Event</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-6 border border-green-300">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 text-red-800 p-4 rounded-lg mb-6 border border-red-300">
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('vendor.events.update', $event->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Event Name -->
            <div>
                <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-200">Event Name <span class="text-red-500">*</span></label>
                <input type="text" name="event_name" value="{{ old('event_name', $event->event_name) }}"
                       class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-[#8d85ec] focus:outline-none" required>
            </div>

            <!-- Event Date & Category -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-200">Event Date & Time <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="event_date" 
                        value="{{ old('event_date', $event->event_date ? $event->event_date->format('Y-m-d\TH:i') : '') }}"
                        class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-[#8d85ec] focus:outline-none" required>
                </div>
                <div>
                    <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-200">Category <span class="text-red-500">*</span></label>
                    <select name="category" class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-[#8d85ec] focus:outline-none" required>
                        <option value="" disabled>Select a category</option>
                        <option value="Concert" {{ old('category', $event->category) == 'Concert' ? 'selected' : '' }}>Concert</option>
                        <option value="Art" {{ old('category', $event->category) == 'Art' || old('category', $event->category) == 'Exhibition' ? 'selected' : '' }}>Exhibition / Art</option>
                        <option value="Food & Drink" {{ old('category', $event->category) == 'Food & Drink' ? 'selected' : '' }}>Food & Drink</option>
                        <option value="Technology" {{ old('category', $event->category) == 'Technology' ? 'selected' : '' }}>Technology</option>
                        <option value="Sports" {{ old('category', $event->category) == 'Sports' ? 'selected' : '' }}>Sports</option>
                        <option value="Wellness" {{ old('category', $event->category) == 'Wellness' || old('category', $event->category) == 'Workshop' ? 'selected' : '' }}>Workshop / Wellness</option>
                    </select>
                </div>
            </div>

            <!-- Venue / Location -->
            <div>
                <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-200">Venue / Location <span class="text-red-500">*</span></label>
                <input type="text" name="venue" value="{{ old('venue', $event->venue) }}"
                       class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-[#8d85ec] focus:outline-none" required>
            </div>

            <!-- TICKET TYPES SECTION (Alpine.js) -->
            <div x-data="{
                ticketTypes: {{ json_encode($initialTickets) }},
                addTicketType() {
                    this.ticketTypes.push({ id: null, name: '', price: '', quantity: '', sold_quantity: 0, description: '', status: 'active' });
                },
                removeTicketType(index) {
                    let ticket = this.ticketTypes[index];
                    if (ticket.sold_quantity && ticket.sold_quantity > 0) {
                        if (confirm('This ticket type already has ' + ticket.sold_quantity + ' ticket(s) sold. Deleting it will mark it as Inactive instead of deleting historical data. Continue?')) {
                            ticket.status = 'inactive';
                            this.ticketTypes.splice(index, 1);
                        }
                    } else {
                        if (this.ticketTypes.length > 1) {
                            this.ticketTypes.splice(index, 1);
                        } else {
                            alert('At least one ticket type must remain.');
                        }
                    }
                }
            }" class="p-6 bg-purple-50 dark:bg-gray-700 rounded-2xl border-2 border-dashed border-[#8d85ec]/50 space-y-4">
                
                <div class="flex items-center justify-between border-b border-purple-200 dark:border-gray-600 pb-3">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <span>🎫</span> Manage Ticket Types
                        </h3>
                        <p class="text-xs text-gray-600 dark:text-gray-300 mt-1">
                            Modify prices, increase capacity, add new tiers, or manage existing ticket tiers.
                        </p>
                    </div>
                    <button type="button" @click="addTicketType()" 
                            class="inline-flex items-center gap-1 bg-[#8d85ec] hover:bg-[#7a72d6] text-white px-3 py-1.5 rounded-lg text-sm font-semibold transition shadow-sm">
                        <span>+</span> Add Ticket Type
                    </button>
                </div>

                <!-- Ticket Type Cards -->
                <div class="space-y-4">
                    <template x-for="(ticket, index) in ticketTypes" :key="index">
                        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-600 shadow-sm relative space-y-3">
                            <input type="hidden" :name="'ticket_types[' + index + '][id]'" x-model="ticket.id">
                            <input type="hidden" :name="'ticket_types[' + index + '][status]'" x-model="ticket.status">

                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-bold text-[#8d85ec] uppercase tracking-wider" x-text="'Ticket Option #' + (index + 1)"></span>
                                    <template x-if="ticket.sold_quantity > 0">
                                        <span class="bg-blue-100 text-blue-800 text-xs px-2.5 py-0.5 rounded-full font-bold" x-text="ticket.sold_quantity + ' Sold'"></span>
                                    </template>
                                </div>
                                <button type="button" @click="removeTicketType(index)" x-show="ticketTypes.length > 1"
                                        class="text-red-500 hover:text-red-700 text-xs font-semibold px-2 py-1 bg-red-50 dark:bg-gray-700 rounded transition">
                                    ✕ Remove
                                </button>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <!-- Name -->
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-200 mb-1">Ticket Name <span class="text-red-500">*</span></label>
                                    <input type="text" :name="'ticket_types[' + index + '][name]'" x-model="ticket.name" placeholder="e.g. VIP, Regular"
                                           class="w-full p-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:ring-2 focus:ring-[#8d85ec] focus:outline-none" required>
                                </div>

                                <!-- Price -->
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-200 mb-1">Price (Rs.) <span class="text-red-500">*</span></label>
                                    <input type="number" step="0.01" min="0" :name="'ticket_types[' + index + '][price]'" x-model="ticket.price" placeholder="500"
                                           class="w-full p-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:ring-2 focus:ring-[#8d85ec] focus:outline-none" required>
                                </div>

                                <!-- Quantity -->
                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-200">Total Quantity <span class="text-red-500">*</span></label>
                                        <template x-if="ticket.sold_quantity > 0">
                                            <span class="text-[11px] text-amber-600 font-medium" x-text="'Min: ' + ticket.sold_quantity"></span>
                                        </template>
                                    </div>
                                    <input type="number" :min="ticket.sold_quantity > 0 ? ticket.sold_quantity : 1" :name="'ticket_types[' + index + '][quantity]'" x-model="ticket.quantity" placeholder="100"
                                           class="w-full p-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:ring-2 focus:ring-[#8d85ec] focus:outline-none" required>
                                </div>
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-200 mb-1">Description / Perks (Optional)</label>
                                <input type="text" :name="'ticket_types[' + index + '][description]'" x-model="ticket.description" placeholder="e.g. VIP Front row seating + Refreshments"
                                       class="w-full p-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:ring-2 focus:ring-[#8d85ec] focus:outline-none">
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Description -->
            <div x-data="{ wordCount: {{ str_word_count(old('description', $event->description)) }} }">
                <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-200">Description <span class="text-red-500">*</span></label>
                <textarea name="description" rows="4"
                          maxlength="500" class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-[#8d85ec] focus:outline-none" required @input="wordCount = $event.target.value.trim() ? $event.target.value.trim().split(/\s+/).length : 0">{{ old('description', $event->description) }}</textarea>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400"><span x-text="wordCount"></span>/50 words</p>
            </div>

            <!-- Current Image (Optional) -->
            @if($event->image)
                <div>
                    <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-200">Current Banner Photo</label>
                    <img src="{{ asset('uploads/' . $event->image) }}" alt="Event Image" class="w-48 h-32 object-cover rounded-lg shadow-md border border-gray-300 dark:border-gray-600">
                </div>
            @endif

            <!-- Image Upload (Optional) -->
            <div>
                <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-200">Change Banner Photo (optional)</label>
                <input type="file" name="image" class="w-full text-gray-700 dark:text-gray-200 p-2 border border-gray-300 dark:border-gray-600 rounded-lg" accept="image/*">
            </div>

            <!-- Buttons -->
            <div class="flex justify-center gap-6 pt-4">
                <button type="submit"
                    class="bg-gradient-to-r from-purple-500 to-[#8d85ec] hover:from-purple-600 hover:to-[#7a72d6] text-white px-8 py-3 rounded-full font-bold shadow-lg transition transform hover:-translate-y-0.5">
                    Update Event & Tickets
                </button>

                <a href="{{ route('vendor.events.index') }}"
                    class="bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-200 px-8 py-3 rounded-full font-bold transition inline-flex items-center justify-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
