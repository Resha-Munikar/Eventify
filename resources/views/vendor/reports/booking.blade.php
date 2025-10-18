@extends('layouts.app')

@section('title', 'Venues')
@php 
$noNavbar = true;
$noFooter = true; 
@endphp

@section('content')
@include('vendor.sidebar') {{-- Or 'admin.sidebar' if applicable --}}

<div class="max-w-7xl mx-auto mt-10 ml-72 mr-10">
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <h2 class="text-3xl font-bold text-[#8d85ec] mb-6"> Venues Booking</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
               <thead class="bg-[#8D85EC] text-white">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold">Booking ID</th>
                        <th class="px-6 py-4 text-left font-semibold">User</th>
                        <th class="px-6 py-4 text-left font-semibold">Venue</th>
                        <th class="px-6 py-4 text-left font-semibold">Booking Date</th>
                        <th class="px-6 py-4 text-left font-semibold">Guests</th>
                        <th class="px-6 py-4 text-left font-semibold">Status</th>
                        <th class="px-6 py-4 text-left font-semibold">Total Price (Rs)</th>
                    </tr>
                </thead>
                @php
                $totalAmount = 0;
                @endphp
                <tbody>
                    @forelse($venueBookings as $booking)
                    @php
                        $totalAmount += $booking->total_price;
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm">{{ $booking->id }}</td>
                        <td class="px-6 py-4 text-sm">{{ $booking->user->id }}</td>
                        <td class="px-6 py-4 text-sm">{{ $booking->venue->venue_name }}</td>
                        <td class="px-6 py-4 text-sm">{{ \Carbon\Carbon::parse($booking->event_date)->format('Y-m-d') }}</td>
                        <td class="px-6 py-4 text-sm">{{ $booking->guests }}</td>
                        <td class="px-6 py-4 text-sm">
                            @if($booking->status === 'paid')
                            <span class="bg-green-200 text-green-800 px-3 py-1 rounded-md text-xs font-semibold">Paid</span>
                            @else
                            <span class="bg-red-200 text-red-800 px-3 py-1 rounded-md text-xs font-semibold">Unpaid</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">{{ number_format($booking->total_price, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No bookings found.</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6" class="px-6 py-4 font-semibold text-right">Total Amount:</td>
                        <td class="px-6 py-4 font-semibold text-green-600">{{ number_format($totalAmount, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="mt-4">
            <button class="bg-[#8D85EC] hover:bg-[#7b76e4] text-white px-4 py-2 text-sm rounded-md transition duration-200">Download PDF Report</button>
        </div>
    </div>
</div>
@endsection