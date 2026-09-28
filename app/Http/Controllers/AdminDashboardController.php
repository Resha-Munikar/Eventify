<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Booking;
use App\Models\Event;
use App\Models\Inquiry;
use App\Models\User;
use App\Models\VendorKyc;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Render the production-ready Admin Dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // 1. Core Summary Metrics
        $totalUsers = User::where('role', 'user')->count();
        $totalVendors = User::where('role', 'vendor')->count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalAccounts = User::count();

        // KYC Verification Metrics
        $pendingKyc = VendorKyc::where('status', 'pending')->count();
        $approvedKyc = VendorKyc::where('status', 'approved')->count();
        $rejectedKyc = VendorKyc::where('status', 'rejected')->count();
        $totalKycSubmissions = VendorKyc::count();
        $unsubmittedVendors = User::where('role', 'vendor')->doesntHave('kyc')->count();

        // Event Metrics
        $totalEvents = Event::count();
        $upcomingEventsCount = Event::where('event_date', '>=', now())->count();
        $pastEventsCount = Event::where('event_date', '<', now())->count();

        // Booking & Ticket Metrics
        $totalBookings = Booking::where('booking_status', '!=', 'cancelled')->count();
        $totalTicketsSold = (int) Booking::where('booking_status', '!=', 'cancelled')->sum('tickets');

        // Revenue Calculation (Strictly confirmed/paid payments from non-cancelled bookings)
        $totalRevenue = (float) Booking::whereIn('payment_status', ['paid', 'confirmed', 'completed'])
            ->where('booking_status', '!=', 'cancelled')
            ->sum(DB::raw('COALESCE(total_amount, amount)'));

        // Contact & Inquiry Metrics
        $unreadInquiries = Inquiry::where('status', 'unread')->count();
        $resolvedInquiries = Inquiry::where('status', 'resolved')->count();
        $totalInquiries = Inquiry::count();

        // 2. Event Category Distribution (Real DB data)
        $categoriesData = Event::select('category', DB::raw('count(*) as count'))
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->groupBy('category')
            ->orderByDesc('count')
            ->get();

        $categoryLabels = $categoriesData->pluck('category')->toArray();
        $categoryCounts = $categoriesData->pluck('count')->toArray();

        // 3. Time Series Activity: Last 7 Days and Last 30 Days (Daily aggregation)
        // 7 Days
        $days7Labels = [];
        $bookings7Data = [];
        $revenue7Data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateString = $date->toDateString();
            $days7Labels[] = $date->format('D (M d)');

            $bookingCount = Booking::whereDate('booking_date', $dateString)
                ->where('booking_status', '!=', 'cancelled')
                ->count();

            $revenueAmount = Booking::whereDate('booking_date', $dateString)
                ->whereIn('payment_status', ['paid', 'confirmed', 'completed'])
                ->where('booking_status', '!=', 'cancelled')
                ->sum(DB::raw('COALESCE(total_amount, amount)'));

            $bookings7Data[] = (int) $bookingCount;
            $revenue7Data[] = round((float) $revenueAmount, 2);
        }

        // 30 Days
        $days30Labels = [];
        $bookings30Data = [];
        $revenue30Data = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateString = $date->toDateString();
            $days30Labels[] = $date->format('M d');

            $bookingCount = Booking::whereDate('booking_date', $dateString)
                ->where('booking_status', '!=', 'cancelled')
                ->count();

            $revenueAmount = Booking::whereDate('booking_date', $dateString)
                ->whereIn('payment_status', ['paid', 'confirmed', 'completed'])
                ->where('booking_status', '!=', 'cancelled')
                ->sum(DB::raw('COALESCE(total_amount, amount)'));

            $bookings30Data[] = (int) $bookingCount;
            $revenue30Data[] = round((float) $revenueAmount, 2);
        }

        // 4. Data Lists for Dashboard Widgets
        // Recent Bookings (with relations)
        $recentBookings = Booking::with(['user', 'event', 'ticketType'])
            ->latest()
            ->take(6)
            ->get();

        // Recently Added Events
        $recentEvents = Event::with(['vendor', 'ticketTypes'])
            ->latest()
            ->take(5)
            ->get();

        // Upcoming Events (event_date >= now)
        $upcomingEvents = Event::with(['vendor', 'ticketTypes'])
            ->where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->take(4)
            ->get();

        // If no future events in test DB, fallback to nearest chronological events
        if ($upcomingEvents->isEmpty()) {
            $upcomingEvents = Event::with(['vendor', 'ticketTypes'])
                ->orderBy('event_date', 'desc')
                ->take(4)
                ->get();
        }

        // Recent System Activity Logs
        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->take(6)
            ->get();

        // Recent Inquiries
        $recentInquiries = Inquiry::with(['user', 'vendor', 'event', 'venue'])
            ->latest()
            ->take(4)
            ->get();

        // Pending KYC Applications Preview
        $pendingKycsList = VendorKyc::with(['user'])
            ->where('status', 'pending')
            ->latest()
            ->take(4)
            ->get();

        return view('admin.dashboard', compact(
            'user',
            'totalUsers',
            'totalVendors',
            'totalAdmins',
            'totalAccounts',
            'pendingKyc',
            'approvedKyc',
            'rejectedKyc',
            'totalKycSubmissions',
            'unsubmittedVendors',
            'totalEvents',
            'upcomingEventsCount',
            'pastEventsCount',
            'totalBookings',
            'totalTicketsSold',
            'totalRevenue',
            'unreadInquiries',
            'resolvedInquiries',
            'totalInquiries',
            'categoryLabels',
            'categoryCounts',
            'days7Labels',
            'bookings7Data',
            'revenue7Data',
            'days30Labels',
            'bookings30Data',
            'revenue30Data',
            'recentBookings',
            'recentEvents',
            'upcomingEvents',
            'recentActivities',
            'recentInquiries',
            'pendingKycsList'
        ));
    }
}
