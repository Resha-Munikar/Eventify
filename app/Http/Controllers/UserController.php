<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\VenueBooking;
use App\Models\Booking;
use App\Models\Event;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use PDF;

class UserController extends Controller
{
    public function adminView()
    {
        // Get all users
        $users = User::all();

        // Pass them to the view
        return view('chirps.user', compact('users'));
    }

    public function adminEdit($id)
    {
        $user = User::findOrFail($id);
        return view('chirps.edit', compact('user'));
    }

    // Handle update
    public function adminUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'role'  => 'required|string',
        ]);

        $user->update($request->only('name', 'email', 'role'));

        return redirect()->route('chirps.user')
                         ->with('success', 'User updated successfully.');
    }

    public function adminDestroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting yourself (optional)
        if (auth()->id() === $user->id) {
            return redirect()->route('chirps.user')
                            ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('chirps.user')
                        ->with('success', 'User deleted successfully.');
    }

    // Show profile page
    public function profile()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate inputs
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo && file_exists(public_path('uploads/profile_photos/' . $user->profile_photo))) {
                unlink(public_path('uploads/profile_photos/' . $user->profile_photo));
            }

            $file = $request->file('profile_photo');
            $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profile_photos'), $filename);
            $user->profile_photo = $filename;
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->route('profile')->with('success', 'Profile updated successfully!');
    }


    // Show bookings
    public function bookings() {
        $user = Auth::user();
        $eventBookings = Booking::where('user_id', $user->id)->with(['event', 'ticketType'])->latest()->get();
        $venueBookings = VenueBooking::where('user_id', $user->id)->with('venue')->latest()->get();

        return view('profile-bookings', compact('eventBookings', 'venueBookings'));
    }

    public function deletePhoto()
    {
        $user = Auth::user();

        if ($user->profile_photo && file_exists(public_path('uploads/profile_photos/' . $user->profile_photo))) {
            unlink(public_path('uploads/profile_photos/' . $user->profile_photo));
        }

        $user->profile_photo = null;
        $user->save();

        return redirect()->route('profile')->with('success', 'Profile photo deleted successfully!');
    }
    


public function showReport()
{
    // Get the current logged-in user
    $user = Auth::user();

    // Fetch venue bookings related to the logged-in user with related user and venue info
    $venueBookings = VenueBooking::with(['user', 'venue'])
        ->where('user_id', $user->id)
        ->latest()
        ->get();

    // Pass the bookings to the view
    return view('userbooking', compact('venueBookings'));
}

 public function showUserEvent()
{
   $user = Auth::user();

     $eventBookings = Booking::with(['user', 'event', 'ticketType'])
        ->where('user_id', $user->id)
        ->latest()
        ->get();

    // Pass the bookings to the view
    return view('usereventbook', compact('eventBookings'));
}
 public function downloadAdminPdf(Request $request)
{
    // Build the query
    $query = Booking::with(['user', 'event', 'ticketType']);

    // Apply date filters if provided
    if ($request->filled('from_date')) {
        $query->whereDate('booking_date', '>=', $request->input('from_date'));
    }
    if ($request->filled('to_date')) {
        $query->whereDate('booking_date', '<=', $request->input('to_date'));
    }

    // Fetch filtered bookings
    $eventBookings = $query->latest()->get();

    // Generate PDF
    $pdf = PDF::loadView('admin.reports.eventbooking_pdf', compact('eventBookings'));

    return $pdf->download('admin_event_bookings.pdf');
}
 public function showAllEvents(Request $request)
{
    // Build the query
    $query = Booking::with(['user', 'event', 'ticketType']);

    // Apply date filters if provided
    if ($request->filled('from_date')) {
        $query->whereDate('booking_date', '>=', $request->input('from_date'));
    }
    if ($request->filled('to_date')) {
        $query->whereDate('booking_date', '<=', $request->input('to_date'));
    }

    // Fetch filtered bookings
    $eventBookings = $query->latest()->get();

    // Pass data to the view
    return view('admin.reports.admineventbooking', compact('eventBookings'));
}

    /**
     * Cancel an event ticket booking and restore seats/quantities
     */
    public function cancelEventBooking($id)
    {
        $user = Auth::user();
        $booking = Booking::with(['event', 'ticketType'])
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        if ($booking->booking_status === 'cancelled') {
            return redirect()->back()->with('error', 'This event booking has already been cancelled.');
        }

        DB::transaction(function () use ($booking) {
            // 1. Mark booking as cancelled
            $booking->booking_status = 'cancelled';
            $booking->payment_status = 'cancelled';
            $booking->save();

            // 2. Adjust Ticket Type sold_quantity
            if ($booking->ticket_type_id) {
                $ticketType = TicketType::where('id', $booking->ticket_type_id)->lockForUpdate()->first();
                if ($ticketType) {
                    $ticketType->sold_quantity = max(0, (int)$ticketType->sold_quantity - (int)$booking->tickets);
                    $ticketType->save();
                }
            }

            // 3. Adjust Event aggregate available seats
            if ($booking->event_id) {
                $event = Event::where('id', $booking->event_id)->lockForUpdate()->first();
                if ($event) {
                    $event->available_seats = (int)$event->available_seats + (int)$booking->tickets;
                    $event->save();
                }
            }
        });

        return redirect()->back()->with('success', 'Event booking cancelled successfully! ' . $booking->tickets . ' seat(s) have been restored.');
    }

}
