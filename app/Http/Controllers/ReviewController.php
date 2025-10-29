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

    public function index()
    {
        // Get all reviews with associated user and event/venue if needed
        $reviews = Review::with(['user', 'venue'])->latest()->paginate(10);

        return view('admin.reports.review', compact('reviews'));
    }
    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->back()->with('success', 'Review deleted successfully.');
    }
    public function vendorIndex()
    {
        $vendorId = auth()->id();

        // Fetch all reviews for venues that belong to the logged-in vendor
        $reviews = \App\Models\Review::with(['user', 'venue'])
            ->whereHas('venue', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->latest()
            ->get();

        return view('vendor.venue-reviews', compact('reviews'));
    }



}
