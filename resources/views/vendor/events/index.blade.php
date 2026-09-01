@extends('layouts.app')

@section('title', 'My Events')
@php 
    $noNavbar = true; 
    $noFooter = true; 
@endphp

@section('content')
@include('vendor.sidebar')

<div class="ml-0 sm:ml-64 p-6 bg-gray-100 dark:bg-gray-900 min-h-screen overflow-x-hidden">

    <!-- Header & Add Event Button -->
    <div class="mb-6 mt-6 max-w-6xl mx-auto flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-3xl font-bold text-[#8d85ec] truncate">My Events</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">Manage your published events and ticket types.</p>
        </div>
        
        <!-- Add Event Button -->
        <a href="{{ route('vendor.events.create') }}"
           class="bg-gradient-to-r from-purple-500 to-[#8d85ec] hover:from-purple-600 hover:to-[#7a72d6] text-white px-6 py-2.5 rounded-full shadow-md flex items-center gap-2 transition transform hover:-translate-y-0.5 font-semibold text-sm">
            <span class="text-lg font-bold">+</span>
            <span>Add New Event</span>
        </a>
    </div>

    <!-- Category Filters -->
    <div class="max-w-6xl mx-auto mb-6 flex flex-wrap items-center gap-2">
        
        <a href="{{ route('vendor.events.index') }}"
           class="px-4 py-1.5 rounded-full text-xs font-semibold transition {{ !$category ? 'bg-[#8d85ec] text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-purple-50 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700' }}">
            All
        </a>
        @foreach($categories as $eventCategory)
            <a href="{{ route('vendor.events.index', ['category' => $eventCategory]) }}"
               class="px-4 py-1.5 rounded-full text-xs font-semibold transition {{ $category === $eventCategory ? 'bg-[#8d85ec] text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-purple-50 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                {{ $eventCategory }}
            </a>
        @endforeach
    </div>

    <!-- Events Grid -->
    @if($events->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-4 max-w-6xl mx-auto">
            @foreach($events as $event)
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl overflow-hidden hover:shadow-2xl transition transform hover:-translate-y-1 hover:scale-[1.02] w-full flex flex-col justify-between">
                    <div>
                        <div class="w-full h-56 rounded-t-2xl relative flex items-center justify-center overflow-hidden">
    <img 
        src="{{ asset('uploads/' . $event->image) }}" 
        alt="{{ $event->event_name }}" 
        class="w-full h-full object-cover"
    >

    @if($event->category)
        <span class="absolute top-3 right-3 bg-white/90 dark:bg-gray-900/90 text-purple-700 dark:text-purple-300 text-xs font-bold px-3 py-1 rounded-full shadow">
            {{ $event->category }}
        </span>
    @endif
</div>

                        <div class="p-5 flex flex-col gap-2.5">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white truncate">{{ $event->event_name }}</h3>
                            <p class="text-gray-600 dark:text-gray-300 text-xs line-clamp-2 break-words">{{ \Illuminate\Support\Str::words($event->description, 20, '...') }}</p>
                            
                            <div class="flex items-center gap-1.5 text-xs font-medium text-gray-800 dark:text-gray-400">
                                <span>📍</span>
                                <span class="truncate">{{ $event->venue }}</span>
                            </div>
                            <div class="flex items-center gap-1.5 text-xs font-medium text-gray-800 dark:text-gray-400">
                                <span>📅</span>
                                <span>{{ \Carbon\Carbon::parse($event->event_date)->format('d M, Y - h:i A') }}</span>
                            </div>
                            @php
                                $basePrice = $event->ticketTypes->where('status', 'active')->min('price') ?? $event->price;
                            @endphp
                            <div class="text-sm font-bold text-[#8d85ec]">
                                From Rs {{ number_format($basePrice, 0) }} onwards
                            </div>

                        </div>
                    </div>

                    <div class="p-5 pt-0 flex justify-between gap-2">
                       <a href="{{ route('vendor.events.edit', $event->id) }}"
   class="flex-1 bg-[#7169D2] hover:bg-[#625BC0] text-white font-semibold text-xs py-2 rounded-lg text-center transition shadow-sm">
    Edit
</a>

                        <form action="{{ route('vendor.events.destroy', $event->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this event?');" class="flex-1">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold text-xs py-2 rounded-lg transition shadow-sm">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8 max-w-6xl mx-auto">
            {{ $events->links() }}
        </div>
    @else
        <div class="text-center mt-20 bg-white dark:bg-gray-800 p-12 rounded-2xl shadow-sm max-w-xl mx-auto">
            <span class="text-5xl">🎪</span>
            <p class="text-gray-700 dark:text-gray-200 text-lg font-semibold mt-4">No events found.</p>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1 mb-6">Start by publishing your first event with multiple ticket options.</p>
            <a href="{{ route('vendor.events.create') }}" class="bg-[#8d85ec] hover:bg-[#7a72d6] text-white font-semibold px-6 py-2.5 rounded-full transition shadow">
                + Add New Event
            </a>
        </div>
    @endif

</div>
@endsection
