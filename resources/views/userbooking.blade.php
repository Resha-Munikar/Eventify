@extends('layouts.app')

@section('title', 'Venues')
@php 
$noFooter = true; 
@endphp

@section('content')

<div class="max-w-7xl mx-auto mt-10 px-4">
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <h2 class="text-3xl font-bold text-[#8d85ec] mb-6"> Venues Booking</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
               <thead class="bg-[#8D85EC]  text-white">
                    <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold">Booking ID</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold">User</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold">Venue</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold">Booking Date</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold">Total Price (Rs)</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold">Status</th>
                </tr>
                </thead>
                <tbody>
                     @forelse($venueBookings as $booking)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm">{{ $booking->id }}</td>
                    <td class="px-6 py-4 text-sm">{{ $booking->user->name }}</td>
                    <td class="px-6 py-4 text-sm">{{ $booking->venue->venue_name }}</td>
                    <td class="px-6 py-4 text-sm">{{ \Carbon\Carbon::parse($booking->event_date)->format('Y-m-d') }}</td>
        
                    <td class="px-6 py-4 text-sm">{{ number_format($booking->total_price, 2) }}</td>
                    <td class="px-6 py-4 text-sm">
                        @if($booking->status === 'paid')
                        <span class="bg-green-200 text-green-800 px-3 py-1  rounded-md  text-xs font-semibold">Paid</span>
                        @else
                        <span class="bg-red-200 text-red-800 px-3 py-1  rounded-md  text-xs font-semibold">Unpaid</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">No bookings found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection