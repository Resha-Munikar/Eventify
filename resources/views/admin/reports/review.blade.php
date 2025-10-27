@extends('layouts.app')

@section('title', 'Venue Reviews')
@php 
$noNavbar = true;
$noFooter = true; 
@endphp

@section('content')
@include('admin.sidebar')

<div class="max-w-7xl mx-auto mt-10 ml-72 mr-10">
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <h2 class="text-3xl font-bold text-[#8d85ec] mb-6">Venue Reviews</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead>
                    <tr class="bg-[#8d85ec] text-white">
                        <th scope="col" class="px-6 py-3 rounded-tl-lg">Id</th>
                        <th scope="col" class="px-6 py-3">User</th>
                        <th scope="col" class="px-6 py-3">Venue</th>
                        <th scope="col" class="px-6 py-3">Rating</th>
                        <th scope="col" class="px-6 py-3">Comment</th>
                        <th scope="col" class="px-6 py-3">Date</th>
                        <th scope="col" class="px-6 py-3 text-center rounded-tr-lg">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium text-gray-700">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">{{ $review->user->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4">{{ $review->venue->venue_name ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                @for ($i = 0; $i < $review->rating; $i++)
                                    ⭐️
                                @endfor
                            </td>
                            <td class="px-6 py-4">{{ $review->comment ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $review->created_at->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 flex justify-center space-x-4">
                                <!-- Delete Button -->
                                <form action="{{ route('venueReview.destroy', $review->id) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this review?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" 
                                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" 
                                                  d="M6 18a2 2 0 002 2h8a2 2 0 002-2V7H6v11zM9 7V5a2 2 0 012-2h2a2 2 0 012 2v2M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-6 text-center text-gray-500">
                                No reviews found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
