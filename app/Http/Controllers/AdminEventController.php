<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Services\ActivityLogger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminEventController extends Controller
{
    /**
     * Display a listing of all platform events for administrators.
     */
    public function index(Request $request)
    {
        $query = Event::with(['vendor', 'ticketTypes'])->orderBy('event_date', 'desc');

        // 1. Search by Event Name or Vendor Name/Email
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('event_name', 'like', "%{$search}%")
                  ->orWhere('venue', 'like', "%{$search}%")
                  ->orWhereHas('vendor', function ($vendorQuery) use ($search) {
                      $vendorQuery->where('name', 'like', "%{$search}%")
                                  ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // 2. Category Filter
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        // 3. Status Filter (Active / Past / Sold Out)
        if ($request->filled('status')) {
            $status = strtolower($request->input('status'));
            $now = Carbon::now();

            if ($status === 'active' || $status === 'upcoming') {
                $query->where('event_date', '>=', $now);
            } elseif ($status === 'past' || $status === 'completed') {
                $query->where('event_date', '<', $now);
            } elseif ($status === 'sold_out') {
                $query->where('available_seats', '<=', 0);
            }
        }

        // Server-side pagination
        $events = $query->paginate(10)->withQueryString();

        // Distinct categories for filter dropdown
        $categories = Event::whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('admin.events.index', compact('events', 'categories'));
    }

    /**
     * Show detailed event information for administrators.
     */
    public function show(Event $event)
    {
        $event->load(['vendor', 'ticketTypes', 'bookings.user']);

        // Log admin viewing event
        ActivityLogger::log('event_viewed', 'Viewed event details for "' . $event->event_name . '"', $event);

        return view('admin.events.show', compact('event'));
    }
}
