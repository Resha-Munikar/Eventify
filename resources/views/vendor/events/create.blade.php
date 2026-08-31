@extends('layouts.app')

@section('title', 'Add Event')
@php 
    $noNavbar = true; 
    $noFooter = true; 
@endphp
@include('vendor.sidebar')

@section('content')
<div class="w-full mx-auto ml-0 sm:ml-64 p-6 bg-gray-100 dark:bg-gray-900 min-h-screen">
    <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-8">
        <h2 class="text-3xl font-bold text-[#8d85ec] mb-6 text-center">Add New Event</h2>

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

        <form action="{{ route('vendor.events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Event Name -->
            <div>
                <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-200">Event Name <span class="text-red-500">*</span></label>
                <input type="text" name="event_name" value="{{ old('event_name') }}" placeholder="e.g. Tech Conference 2026" class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-[#8d85ec] focus:outline-none" required>
            </div>

            <!-- Event Date & Category -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-200">Event Date & Time <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="event_date" value="{{ old('event_date') }}" class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-[#8d85ec] focus:outline-none" required>
                </div>
                <div>
                    <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-200">Category <span class="text-red-500">*</span></label>
                    <select name="category" class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-[#8d85ec] focus:outline-none" required>
                        <option value="" disabled {{ old('category') ? '' : 'selected' }}>Select Category</option>
                        <option value="Concert" {{ old('category') == 'Concert' ? 'selected' : '' }}>Concert</option>
                        <option value="Art" {{ old('category') == 'Art' ? 'selected' : '' }}>Exhibition / Art</option>
                        <option value="Food & Drink" {{ old('category') == 'Food & Drink' ? 'selected' : '' }}>Food & Drink</option>
                        <option value="Technology" {{ old('category') == 'Technology' ? 'selected' : '' }}>Technology</option>
                        <option value="Sports" {{ old('category') == 'Sports' ? 'selected' : '' }}>Sports</option>
                        <option value="Wellness" {{ old('category') == 'Wellness' ? 'selected' : '' }}>Workshop / Wellness</option>
                    </select>
                </div>
            </div>

            <!-- Venue / Location -->
            <div>
                <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-200">Venue / Location <span class="text-red-500">*</span></label>
                <input id="location" type="text" name="venue" value="{{ old('venue') }}" placeholder="e.g. Kathmandu Marriott Hotel" class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-[#8d85ec] focus:outline-none" required>
                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
            </div>

            <!-- TICKET TYPES SECTION (Alpine.js) -->
            <div x-data="{
                ticketTypes: {{ json_encode(old('ticket_types', [
                    ['name' => 'Regular', 'price' => '500', 'quantity' => '100', 'description' => 'General admission access'],
                    ['name' => 'VIP', 'price' => '1500', 'quantity' => '50', 'description' => 'VIP seating, priority lounge and refreshment access']
                ])) }},
                addTicketType() {
                    this.ticketTypes.push({ name: '', price: '', quantity: '', description: '' });
                },
                removeTicketType(index) {
                    if (this.ticketTypes.length > 1) {
                        this.ticketTypes.splice(index, 1);
                    } else {
                        alert('At least one ticket type must be added before an event can be published.');
                    }
                }
            }" class="p-6 bg-purple-50 dark:bg-gray-700 rounded-2xl border-2 border-dashed border-[#8d85ec]/50 space-y-4">
                
                <div class="flex items-center justify-between border-b border-purple-200 dark:border-gray-600 pb-3">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <span>🎫</span> Ticket Types & Pricing
                        </h3>
                        <p class="text-xs text-gray-600 dark:text-gray-300 mt-1">
                            Add multiple ticket options (e.g. Regular, VIP, Student) with their respective prices and quantities.
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
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-bold text-[#8d85ec] uppercase tracking-wider" x-text="'Ticket Option #' + (index + 1)"></span>
                                <button type="button" @click="removeTicketType(index)" x-show="ticketTypes.length > 1"
                                        class="text-red-500 hover:text-red-700 text-xs font-semibold px-2 py-1 bg-red-50 dark:bg-gray-700 rounded transition">
                                    ✕ Remove
                                </button>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <!-- Name -->
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-200 mb-1">Ticket Name <span class="text-red-500">*</span></label>
                                    <input type="text" :name="'ticket_types[' + index + '][name]'" x-model="ticket.name" placeholder="e.g. VIP, Regular, Student"
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
                                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-200 mb-1">Available Quantity <span class="text-red-500">*</span></label>
                                    <input type="number" min="1" :name="'ticket_types[' + index + '][quantity]'" x-model="ticket.quantity" placeholder="100"
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
            <div>
                <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-200">Event Description <span class="text-red-500">*</span></label>
                <textarea name="description" rows="4" placeholder="Provide full details about the event, schedule, speakers/artists..." class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-[#8d85ec] focus:outline-none" required>{{ old('description') }}</textarea>
            </div>

            <!-- Photo -->
            <div>
                <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-200">Event Banner Photo <span class="text-red-500">*</span></label>
                <input type="file" name="image" class="w-full text-gray-700 dark:text-gray-200 p-2 border border-gray-300 dark:border-gray-600 rounded-lg" accept="image/*" required>
            </div>

            <!-- Submit -->
            <div class="text-center pt-4">
                <button type="submit" class="bg-gradient-to-r from-purple-500 to-[#8d85ec] hover:from-purple-600 hover:to-[#7a72d6] text-white px-8 py-3 rounded-full font-bold shadow-lg transition transform hover:-translate-y-0.5">
                    Add Event & Publish Tickets
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
