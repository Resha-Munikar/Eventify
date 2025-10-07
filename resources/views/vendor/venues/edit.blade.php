@extends('layouts.app')

@section('title', 'Edit Venue')
@php 
    $noNavbar = true; 
    $noFooter = true; 
@endphp
@include('vendor.sidebar')

@section('content')
<div class="ml-0 sm:ml-64 p-6 bg-gray-100 dark:bg-gray-900 min-h-screen overflow-x-hidden">

    <div class="max-w-xl mx-auto bg-white dark:bg-gray-800 shadow-lg rounded-xl p-5">

        <h2 class="text-2xl font-bold text-[#8d85ec] mb-6 text-center">Edit Venue</h2>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('vendor.venues.update', $venue->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
            @csrf
            @method('PUT')

            <!-- Venue Name -->
            <div>
                <label class="block mb-1 text-gray-700 dark:text-gray-200 text-sm">Venue Name</label>
                <input type="text" name="venue_name" value="{{ old('venue_name', $venue->venue_name) }}"
                       class="w-full p-2 rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm" required>
            </div>

            <!-- Location -->
            <div>
                <label class="block mb-1 text-gray-700 dark:text-gray-200 text-sm">Location</label>
                <input type="text" name="location" value="{{ old('location', $venue->location) }}"
                       class="w-full p-2 rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm" required>
            </div>

            <!-- Description -->
            <div>
                <label class="block mb-1 text-gray-700 dark:text-gray-200 text-sm">Description</label>
                <textarea name="description" rows="3"
                          class="w-full p-2 rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm" required>{{ old('description', $venue->description) }}</textarea>
            </div>

            <!-- Price per Person -->
            <div>
                <label class="block mb-1 text-gray-700 dark:text-gray-200 text-sm">Price per Person</label>
                <input type="number" name="price_per_person" value="{{ old('price_per_person', $venue->price_per_person) }}"
                       class="w-full p-2 rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm" required>
            </div>

            <!-- Current Image (Optional) -->
            @if($venue->image)
                <div>
                    <label class="block mb-1 text-gray-700 dark:text-gray-200 text-sm">Current Image</label>
                    <img src="{{ asset('uploads/' . $venue->image) }}" alt="Venue Image" class="w-32 h-32 mt-2 object-cover rounded">
                </div>
            @endif

            <!-- Image Upload (Optional) -->
            <div>
                <label class="block mb-1 text-gray-700 dark:text-gray-200 text-sm">Change Image (optional)</label>
                <input type="file" name="image" class="w-full text-gray-700 dark:text-gray-200 text-sm" accept="image/*">
            </div>

            <!-- Buttons -->
            <div class="flex justify-center gap-12 mt-8">
                <button type="submit"
                    class="bg-gradient-to-r from-purple-500 to-purple-700 hover:from-purple-600 hover:to-purple-800 text-white px-4 py-2 rounded-full font-semibold shadow-md transition text-sm w-40 text-center">
                    Update Venue
                </button>

                <a href="{{ route('vendor.venues.index') }}"
                    class="bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-full font-semibold transition text-sm w-40 text-center inline-flex items-center justify-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script src="//unpkg.com/alpinejs" defer></script>
@endsection
