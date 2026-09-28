<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Event;
use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    public function dashboard()
    {
        $vendor = Auth::user();
        $vendor->load('kyc');
        $vendorId = $vendor->id;

        // 1. Vendor Event Metrics
        $myEventsCount = Event::where('vendor_id', $vendorId)->count();
        $upcomingEventsCount = Event::where('vendor_id', $vendorId)->where('event_date', '>=', now())->count();
        $pastEventsCount = Event::where('vendor_id', $vendorId)->where('event_date', '<', now())->count();

        // 2. Vendor Bookings & Revenue (Only bookings for this vendor's events)
        $totalVendorBookings = Booking::whereHas('event', function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
        })->where('booking_status', '!=', 'cancelled')->count();

        $totalVendorTicketsSold = (int) Booking::whereHas('event', function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
        })->where('booking_status', '!=', 'cancelled')->sum('tickets');

        $totalVendorRevenue = (float) Booking::whereHas('event', function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
        })->whereIn('payment_status', ['paid', 'confirmed', 'completed'])
          ->where('booking_status', '!=', 'cancelled')
          ->sum(DB::raw('COALESCE(total_amount, amount)'));

        // 3. Vendor Recent Bookings
        $recentBookings = Booking::whereHas('event', function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
        })->with(['user', 'event', 'ticketType'])
          ->latest()
          ->take(6)
          ->get();

        // 4. Vendor Events List
        $vendorEvents = Event::where('vendor_id', $vendorId)
            ->with('ticketTypes')
            ->orderBy('event_date', 'desc')
            ->take(5)
            ->get();

        // 5. Vendor Specific Inquiries
        $unreadInquiriesCount = Inquiry::where('vendor_id', $vendorId)->where('status', 'unread')->count();
        $recentInquiries = Inquiry::where('vendor_id', $vendorId)
            ->with(['user', 'event', 'venue'])
            ->latest()
            ->take(4)
            ->get();

        // 6. Vendor Booking & Revenue Trend (Last 7 Days)
        $days7Labels = [];
        $bookings7Data = [];
        $revenue7Data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subDays($i);
            $dateString = $date->toDateString();
            $days7Labels[] = $date->format('D (M d)');

            $bCount = Booking::whereHas('event', function ($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId);
            })->whereDate('booking_date', $dateString)
              ->where('booking_status', '!=', 'cancelled')
              ->count();

            $rev = Booking::whereHas('event', function ($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId);
            })->whereDate('booking_date', $dateString)
              ->whereIn('payment_status', ['paid', 'confirmed', 'completed'])
              ->where('booking_status', '!=', 'cancelled')
              ->sum(DB::raw('COALESCE(total_amount, amount)'));

            $bookings7Data[] = (int) $bCount;
            $revenue7Data[] = round((float) $rev, 2);
        }

        return view('vendor.dashboard', compact(
            'vendor',
            'myEventsCount',
            'upcomingEventsCount',
            'pastEventsCount',
            'totalVendorBookings',
            'totalVendorTicketsSold',
            'totalVendorRevenue',
            'recentBookings',
            'vendorEvents',
            'unreadInquiriesCount',
            'recentInquiries',
            'days7Labels',
            'bookings7Data',
            'revenue7Data'
        ));
    }

    public function bookings()
    {
        $vendor = Auth::user();

        $bookings = $vendor->bookings; 

        return view('vendor.bookings', compact('bookings'));
    }
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login.form');
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'location' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
        'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $photoPath = $request->file('photo')->store('events', 'public');

    Event::create([
        'name' => $request->name,
        'location' => $request->location,
        'description' => $request->description,
        'price' => $request->price,
        'photo' => $photoPath,
        'vendor_id' => auth()->id(),
    ]);

    return back()->with('success', 'Event added successfully!');
    }

}