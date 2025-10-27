<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VenueBooking;
use App\Models\Review;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:venue_bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);

        $booking = VenueBooking::findOrFail($request->booking_id);

        Review::create([
            'user_id' => auth()->id(),
            'venue_id' => $booking->venue_id,
            'booking_id' => $booking->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Review Submitted Successfully!');
    }


}
