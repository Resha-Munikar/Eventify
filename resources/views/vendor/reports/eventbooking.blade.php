@extends('layouts.app')

@section('title', 'Event Bookings')
@php 
$noNavbar = true;
$noFooter = true; 
@endphp

@section('content')
@include('vendor.sidebar') 

<div class="max-w-7xl mx-auto mt-10 ml-72 mr-10">
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <h2 class="text-3xl font-bold text-[#8d85ec] mb-6">Event Bookings</h2>

        <!-- Table -->
        <div class="overflow-x-auto mb-4">
            <table class="w-full text-sm text-left border-collapse border border-gray-300">
                <thead class="bg-[#8D85EC] text-white">
                    <tr>
                        <th class="px-6 py-4">Booking ID</th>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Event</th>
                        <th class="px-6 py-4">Tickets</th>
                        <th class="px-6 py-4">Amount (Rs)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($eventBookings as $booking)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 border border-gray-300">{{ $booking->id }}</td>
                        <td class="px-6 py-4 border border-gray-300">{{ $booking->user->name }}</td>
                        <td class="px-6 py-4 border border-gray-300">{{ $booking->event->event_name }}</td>
                        <td class="px-6 py-4 border border-gray-300">{{ $booking->tickets }}</td>
                        <td class="px-6 py-4 border border-gray-300">{{ number_format($booking->amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No event bookings found.</td>
                    </tr>
                    @endforelse
                </tbody>
                <!-- Footer row for total amount -->
                <tfoot>
                    <tr>
                        <td colspan="4" class="px-6 py-4 font-semibold text-right">Total Amount:</td>
                        <td class="px-6 py-4 font-semibold text-green-600">{{ number_format($eventBookings->sum('amount'), 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Download PDF Button -->
        <div class="mt-4 text-right">
            <a href="{{ route('vendor.reports.eventbooking.pdf') }}" class="bg-[#8D85EC] hover:bg-[#7b76e4] text-white font-bold py-2 px-4 rounded">
                Download PDF Report
            </a>
        </div>
    </div>
</div>
@endsection