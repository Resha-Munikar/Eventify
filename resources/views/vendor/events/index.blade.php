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

    @if(session('success'))
        <div class="max-w-6xl mx-auto mb-6 bg-green-100 text-green-800 p-4 rounded-lg border border-green-300">
            {{ session('success') }}
        </div>
    @endif

    <!-- Events Grid -->
    @if($events->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-4 max-w-6xl mx-auto">
            @foreach($events as $event)
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl overflow-hidden hover:shadow-2xl transition transform hover:-translate-y-1 hover:scale-[1.02] w-full flex flex-col justify-between">
                    <div>
                        <div class="w-full h-56 overflow-hidden rounded-t-2xl relative">
                            <img src="{{ asset('uploads/' . $event->image) }}" 
                                alt="{{ $event->event_name }}" 
                                class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                            @if($event->category)
                                <span class="absolute top-3 right-3 bg-white/90 dark:bg-gray-900/90 text-purple-700 dark:text-purple-300 text-xs font-bold px-3 py-1 rounded-full shadow">
                                    {{ $event->category }}
                                </span>
                            @endif
                        </div>

                        <div class="p-5 flex flex-col gap-2.5">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white truncate">{{ $event->event_name }}</h3>
                            <p class="text-gray-600 dark:text-gray-300 text-xs line-clamp-2">{{ $event->description }}</p>
                            
                            <div class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                                <span>📍</span>
                                <span class="truncate">{{ $event->venue }}</span>
                            </div>
                            <div class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                                <span>📅</span>
                                <span>{{ \Carbon\Carbon::parse($event->event_date)->format('d M, Y - h:i A') }}</span>
                            </div>

                            <!-- Ticket Types Summary -->
                            <div class="mt-2 pt-3 border-t border-gray-100 dark:border-gray-700">
                                <div class="flex justify-between items-center mb-1.5">
                                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Tickets:</span>
                                    <span class="text-xs font-bold text-[#8d85ec]">
                                        @if($event->ticketTypes->isNotEmpty())
                                            @php
                                                $min = $event->ticketTypes->where('status', 'active')->min('price') ?? $event->price;
                                                $max = $event->ticketTypes->where('status', 'active')->max('price') ?? $event->price;
                                            @endphp
                                            @if($min == $max)
                                                Rs {{ number_format($min, 2) }}
                                            @else
                                                Rs {{ number_format($min, 0) }} - {{ number_format($max, 0) }}
                                            @endif
                                        @else
                                            Rs {{ number_format($event->price, 2) }}
                                        @endif
                                    </span>
                                </div>

                                <div class="flex flex-wrap gap-1.5">
                                    @forelse($event->ticketTypes as $t)
                                        <span class="text-[11px] px-2 py-0.5 rounded-full {{ $t->status === 'active' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300' : 'bg-gray-100 text-gray-600' }}">
                                            {{ $t->name }}: Rs {{ number_format($t->price, 0) }} 
                                            <span class="text-[10px] opacity-75">({{ $t->remaining_quantity }} left)</span>
                                        </span>
                                    @empty
                                        <span class="text-xs text-gray-400">Standard ({{ $event->available_seats }} seats)</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 pt-0 flex justify-between gap-2">
                        <a href="{{ route('vendor.events.edit', $event->id) }}"
                        class="flex-1 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold text-xs py-2 rounded-lg text-center transition shadow-sm">Edit</a>

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
