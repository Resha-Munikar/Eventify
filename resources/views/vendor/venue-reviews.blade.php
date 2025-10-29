@extends('layouts.app')

@section('title', 'My Venue Reviews')
@php 
$noNavbar = true;
$noFooter = true; 
@endphp

@section('content')
@include('vendor.sidebar')

<div class="max-w-7xl mx-auto mt-10 ml-72 mr-10">
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <h2 class="text-3xl font-bold text-[#8d85ec] mb-6">My Venue Reviews</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead>
                    <tr class="bg-[#8d85ec] text-white">
                        <th scope="col" class="px-6 py-3 rounded-tl-lg">Id</th>
                        <th scope="col" class="px-6 py-3">Venue Details</th>
                        <th scope="col" class="px-6 py-3">User</th>
                        <th scope="col" class="px-6 py-3">Rating</th>
                        <th scope="col" class="px-6 py-3">Comment</th>
                        <th scope="col" class="px-6 py-3 rounded-tr-lg">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium text-gray-700">{{ $loop->iteration }}</td>

                            <!-- Venue Details -->
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-4">
                                    <!-- Venue Photo -->
                                    <img src="{{ asset('uploads/' . ($review->venue->image ?? 'default.jpg')) }}" 
                                        alt="Venue Photo" 
                                        class="w-28 h-28 object-cover rounded-lg border">

                                    <div>
                                        <div class="font-semibold text-gray-900 text-lg">
                                            {{ $review->venue->venue_name ?? 'N/A' }}
                                        </div>
                                        <div class="text-gray-600 text-sm mt-1 space-y-1">
                                            <p>📍 <strong>Location:</strong> {{ $review->venue->location ?? '-' }}</p>
                                            <p>💰 <strong>Price:</strong> Rs. 
                                                {{ number_format($review->venueBooking->total_price ?? 0, 2) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">{{ $review->user->name ?? 'N/A' }}</td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex gap-1">
                                    @for ($i = 0; $i < $review->rating; $i++)
                                        ⭐
                                    @endfor
                                </span>
                            </td>


                            <td class="px-6 py-4 max-w-xs break-words">
                                @php
                                    $fullComment = $review->comment ?? '-';
                                    $preview = Str::limit($fullComment, 100);
                                @endphp

                                <span class="comment-preview">{{ $preview }}</span>
                                @if(strlen($fullComment) > 100)
                                    <button class="text-blue-500 ml-1 read-more-btn" onclick="toggleComment(this)">Read more</button>
                                    <span class="comment-full hidden">{{ $fullComment }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $review->created_at->format('Y-m-d') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-6 text-center text-gray-500">
                                No reviews found for your venues.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
function toggleComment(button) {
    const full = button.nextElementSibling;
    const preview = button.previousElementSibling;

    if(full.classList.contains('hidden')) {
        full.classList.remove('hidden');
        preview.classList.add('hidden');
        button.textContent = 'Show less';
    } else {
        full.classList.add('hidden');
        preview.classList.remove('hidden');
        button.textContent = 'Read more';
    }
}
</script>

@endsection
