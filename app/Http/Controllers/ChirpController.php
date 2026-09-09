<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use App\Models\Contact;
use App\Models\User;
use App\Models\Event;
use App\Models\Venue;
use App\Models\Review;
use App\Services\EventifyCacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class ChirpController extends Controller
{
    public function index() {
        
        $user = Auth::user();
        $chirps = $user->chirps()->orderBy('created_at', 'desc')->get();
        return view('chirps.index', compact('chirps', 'user'));
    } 

    public function adminIndex() {
        $chirps = Chirp::latest()->get();
        $user = Auth::user();
        $totalCount = User::count();
        $userCount = User::where('role', 'user')->count();
        $adminCount = User::where('role', 'admin')->count();
        $vendorCount = User::where('role', 'vendor')->count();
        return view('chirps.adminIndex', compact('chirps', 'user', 'userCount', 'adminCount','vendorCount', 'totalCount'));
    }

    public function store(Request $request){
        // validation
        $request->validate([
            'chirp' => 'required|string|max:255',
        ]);

        // save
        Chirp::create([
            'chirp' => $request->chirp,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('chirps.index');
    }

    public function edit(string $id) {
        $chirp = Chirp::findOrFail($id);

        return view('chirps.edit', compact('chirp'));
    }

    public function update(Request $request, string $id) {
        // validation
        $request->validate([
            'chirp' => 'required|string|max:255',
        ]);

        // update
        $chirp = Chirp::findOrFail($id);
        // $chirp->chirp = $request->chirp;
        // $chirp->save();

        $chirp->update([
            'chirp' => $request->chirp,
        ]);

        return redirect()->route('chirps.index');
    }

    public function destroy(string $id) {
        $chirp = Chirp::findOrFail($id);
        $chirp->delete();

        return redirect()->route('chirps.index');
    }

public function showWelcomePage()
{
    $reviews = Cache::remember(EventifyCacheService::KEY_WELCOME_REVIEWS, EventifyCacheService::TTL_LONG, function () {
        return Review::with('user')->latest()->get();
    });

    $upcomingEvents = Cache::remember(EventifyCacheService::KEY_WELCOME_UPCOMING, EventifyCacheService::TTL_MEDIUM, function () {
        return Event::with('ticketTypes')->orderBy('event_date', 'asc')->get();
    });

    $trendingEvents = Cache::remember(EventifyCacheService::KEY_WELCOME_TRENDING, EventifyCacheService::TTL_MEDIUM, function () {
        return Event::with('ticketTypes')->orderBy('created_at', 'desc')->take(4)->get();
    });

    $categoryCounts = Cache::remember(EventifyCacheService::KEY_WELCOME_CATEGORY_COUNTS, EventifyCacheService::TTL_MEDIUM, function () {
        return [
            'Concert' => Event::where('category', 'Concert')->count(),
            'Sports' => Event::where('category', 'Sports')->count(),
            'Theatre' => Event::where('category', 'Theatre')->count(),
            'Comedy' => Event::where('category', 'Comedy')->count(),
        ];
    });

    $savedEventIds = Auth::check() ? Auth::user()->savedEvents()->pluck('events.id')->toArray() : [];

    return view('welcome', compact('reviews', 'upcomingEvents', 'trendingEvents', 'categoryCounts', 'savedEventIds'));
}
    public function about(){
        return view('about');
    }

public function events(Request $request)
{
    // Fetch query parameters
    $category = $request->query('category');
    $venue = $request->query('venue');
    $location = $request->query('location');
    $startDate = $request->query('start_date');
    $endDate = $request->query('end_date');
    $minPrice = $request->query('min_price');
    $maxPrice = $request->query('max_price');
    $searchTerm = $request->query('query') ?? $request->query('search');
    
    // Tab selection: 'hot' (default), 'upcoming', or 'saved'
    $tab = $request->query('tab');
    if ($request->query('saved')) {
        $tab = 'saved';
    }
    if (empty($tab) || !in_array($tab, ['hot', 'upcoming', 'saved'])) {
        $tab = 'hot';
    }

    $savedEventIds = Auth::check() ? Auth::user()->savedEvents()->pluck('events.id')->toArray() : [];

    // Calculate active filter count for the single Filter Drawer button badge
    $activeFilterCount = 0;
    if (!empty($category)) $activeFilterCount++;
    if (!empty($venue)) $activeFilterCount++;
    if (!empty($location) && strtolower($location) !== 'all' && strtolower($location) !== 'all locations') $activeFilterCount++;
    if (!empty($startDate)) $activeFilterCount++;
    if (!empty($endDate)) $activeFilterCount++;
    if (!empty($minPrice)) $activeFilterCount++;
    if (!empty($maxPrice)) $activeFilterCount++;
    if (!empty($searchTerm)) $activeFilterCount++;

    // Base query with eager loading
    $query = Event::with(['ticketTypes', 'savedByUsers']);

    // Location filter
    if (!empty($location) && strtolower($location) !== 'all' && strtolower($location) !== 'all locations') {
        $query->where(function($q) use ($location) {
            $q->where('venue', 'like', "%{$location}%")
              ->orWhere('description', 'like', "%{$location}%");
        });
    }

    // Search query filter
    if (!empty($searchTerm)) {
        $query->where(function($q) use ($searchTerm) {
            $q->where('event_name', 'like', "%{$searchTerm}%")
              ->orWhere('venue', 'like', "%{$searchTerm}%")
              ->orWhere('description', 'like', "%{$searchTerm}%")
              ->orWhere('category', 'like', "%{$searchTerm}%");
        });
    }

    // Category filter
    if (!empty($category)) {
        $query->where('category', $category);
    }

    // Venue filter
    if (!empty($venue)) {
        $query->where('venue', 'like', "%{$venue}%");
    }

    // Date range filters if explicitly set
    if (!empty($startDate)) {
        $query->where('event_date', '>=', $startDate);
    }
    if (!empty($endDate)) {
        $query->where('event_date', '<=', $endDate);
    }

    // Price range filters
    if (!empty($minPrice)) {
        $query->where('price', '>=', $minPrice);
    }
    if (!empty($maxPrice)) {
        $query->where('price', '<=', $maxPrice);
    }

    $now = \Carbon\Carbon::now();

    // Tab-specific filtering and smart ranking
    if ($tab === 'saved') {
        if (Auth::check()) {
            $query->whereIn('id', $savedEventIds);
        } else {
            $query->whereRaw('1 = 0');
        }
        $events = $query->orderBy('event_date', 'asc')->get();
    } elseif ($tab === 'upcoming') {
        if (empty($startDate)) {
            $query->where('event_date', '>=', $now->copy()->startOfDay());
        }
        $events = $query->orderBy('event_date', 'asc')->get();
        // If no future events due to test data timestamps, gracefully fallback to all matching
        if ($events->isEmpty() && empty($startDate)) {
            $events = Event::with(['ticketTypes', 'savedByUsers'])->orderBy('event_date', 'asc')->get();
        }
    } else {
        // Default Tab: 'hot' (Hot & Happening - next 4-5 days with intelligent ranking)
        $hotQuery = clone $query;
        if (empty($startDate) && empty($endDate)) {
            // Events within the next 5 days
            $windowStart = $now->copy()->startOfDay();
            $windowEnd = $now->copy()->addDays(5)->endOfDay();
            $hotQuery->whereBetween('event_date', [$windowStart, $windowEnd]);
        }
        $events = $hotQuery->get();

        // If fewer than 3 events fall within strict 5-day window, gracefully expand to nearest 10-14 days
        if ($events->count() < 3 && empty($startDate) && empty($endDate)) {
            $expandedQuery = clone $query;
            $expandedQuery->where('event_date', '>=', $now->copy()->startOfDay());
            $expandedEvents = $expandedQuery->orderBy('event_date', 'asc')->take(12)->get();
            if ($expandedEvents->isNotEmpty()) {
                $events = $expandedEvents;
            } else {
                // If all dates in database are in the past, retrieve all matching events
                $events = $query->orderBy('event_date', 'asc')->get();
            }
        }

        // Intelligently rank Hot & Happening events by proximity, availability, and popularity
        $events = $events->sortByDesc(function ($event) use ($now) {
            $score = 0;

            // 1. Proximity in time (closer in days = higher score)
            $eventDate = \Carbon\Carbon::parse($event->event_date);
            $daysDiff = $now->diffInDays($eventDate, false);
            if ($daysDiff >= 0 && $daysDiff <= 5) {
                $score += (100 - ($daysDiff * 15)); // Up to 100 points
            } elseif ($daysDiff > 5) {
                $score += max(10, 80 - ($daysDiff * 3));
            } else {
                $score += 5; // Past events lower priority
            }

            // 2. Availability / Urgency signals
            $seatsLeft = (int)$event->available_seats;
            if ($seatsLeft > 0 && $seatsLeft <= 25) {
                $score += 30; // High urgency bonus
            } elseif ($seatsLeft > 25 && $seatsLeft <= 100) {
                $score += 15;
            }

            // 3. Social / Popularity signals (saves, ticket types)
            $savesCount = $event->savedByUsers ? $event->savedByUsers->count() : 0;
            $score += min(20, $savesCount * 5);

            return $score;
        })->values();
    }

    $availableLocations = ['All Locations', 'Kathmandu', 'Lalitpur', 'Bhaktapur', 'Pokhara'];

    return view('events', compact(
        'events',
        'category',
        'venue',
        'location',
        'startDate',
        'endDate',
        'minPrice',
        'maxPrice',
        'searchTerm',
        'tab',
        'activeFilterCount',
        'savedEventIds',
        'availableLocations'
    ));
}


public function showEvent($identifier)
{
    $cacheKey = "event_show_{$identifier}";
    $event = Cache::remember($cacheKey, EventifyCacheService::TTL_MEDIUM, function () use ($identifier) {
        $found = null;
        if (is_numeric($identifier)) {
            $found = Event::with(['ticketTypes' => function ($q) {
                $q->orderBy('price', 'asc');
            }, 'vendor'])->find($identifier);
        }

        if (!$found) {
            $cleanIdentifier = strtolower(trim($identifier));
            $allEvents = Event::with(['ticketTypes' => function ($q) {
                $q->orderBy('price', 'asc');
            }, 'vendor'])->get();

            $found = $allEvents->first(function ($e) use ($cleanIdentifier) {
                $slug = \Illuminate\Support\Str::slug($e->event_name);
                if ($slug === $cleanIdentifier) {
                    return true;
                }
                if (\Illuminate\Support\Str::is($cleanIdentifier . '*', $slug) || \Illuminate\Support\Str::is('*' . $cleanIdentifier . '*', $slug)) {
                    return true;
                }
                $nameClean = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $e->event_name));
                $identClean = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $cleanIdentifier));
                return $nameClean === $identClean || str_contains($nameClean, $identClean) || str_contains($identClean, $nameClean);
            });
        }

        return $found;
    });

    if (!$event) {
        abort(404, 'Event not found');
    }

    $savedEventIds = Auth::check() ? Auth::user()->savedEvents()->pluck('events.id')->toArray() : [];
    $isSaved = in_array($event->id, $savedEventIds);

    // Fetch related events in the same category (cached)
    $relatedCacheKey = "event_related_{$event->category}_{$event->id}";
    $relatedEvents = Cache::remember($relatedCacheKey, EventifyCacheService::TTL_MEDIUM, function () use ($event) {
        $rel = Event::with('ticketTypes')
            ->where('id', '!=', $event->id)
            ->where('category', $event->category)
            ->take(3)
            ->get();

        if ($rel->isEmpty()) {
            $rel = Event::with('ticketTypes')
                ->where('id', '!=', $event->id)
                ->take(3)
                ->get();
        }
        return $rel;
    });

    return view('events.show', compact('event', 'relatedEvents', 'savedEventIds', 'isSaved'));
}

public function toggleSave(Request $request, $eventId)
{
    if (!Auth::check()) {
        return response()->json([
            'success' => false,
            'redirect' => route('login'),
            'message' => 'Please log in to save events.'
        ], 401);
    }

    $user = Auth::user();
    $event = Event::findOrFail($eventId);

    $isSaved = $user->savedEvents()->where('events.id', $event->id)->exists();

    if ($isSaved) {
        $user->savedEvents()->detach($event->id);
        $saved = false;
        $message = 'Event removed from saved events.';
    } else {
        $user->savedEvents()->attach($event->id);
        $saved = true;
        $message = 'Event saved to your favorites!';
    }

    return response()->json([
        'success' => true,
        'saved' => $saved,
        'saved_count' => $user->savedEvents()->count(),
        'message' => $message,
        'event_id' => $event->id
    ]);
}

      // Show contact form
    
    public function contact()
{
  $vendors = User::where('role', 'vendor')->get();
 $events = Event::all(); // Fetch all events
$venues = Venue::all(); // Fetch all venues
 return view('contact', compact('vendors', 'events', 'venues'));
}
// public function storeContact(Request $request)
// {
//     // Validate input
//     $validated = $request->validate([
//         'name' => 'required|string|max:255',
//         'email' => 'required|email|max:255',
//         'phone' => 'nullable|string|max:20',
//         'message' => 'required|string',
//         'type' => 'required|string|in:general,vendor,event',
//         'vendor_id' => 'nullable|exists:users,id',
//     ]);

//     // Handle vendor_id validation
//     if ($validated['type'] === 'vendor' && empty($validated['vendor_id'])) {
//         return redirect()->back()->withErrors(['vendor_id' => 'Please select a vendor.'])->withInput();
//     }

//     // Save contact info in database
//     Contact::create($validated);

//     // Determine email recipient based on inquiry type
//     if ($validated['type'] === 'general') {
//         $recipientEmail = 'mah.bristiofficial@gmail.com'; // Your admin/support email
//     } 
//       elseif ($validated['type'] === 'vendor') {
//     // Find user with id equal to vendor_id and role 'vendor'
//     $vendorUser = User::where('id', $validated['vendor_id'])
//                       ->where('role', 'vendor') // adjust role as needed
//                       ->first();

//     $recipientEmail = $vendorUser ? $vendorUser->email : 'support@yourdomain.com';
// }  elseif ($validated['type'] === 'event') {
//     // Fetch the event and get the related vendor's email
//     $event = Event::find($request->input('event_id'));
//     if ($event && $event->vendor_id) {
//         $vendor = User::where('id', $event->vendor_id)->where('role', 'vendor')->first();
//         $recipientEmail = $vendor ? $vendor->email : 'support@yourdomain.com';
//     } else {
//         $recipientEmail = 'support@yourdomain.com'; // fallback if no vendor linked
//     }
// } elseif ($validated['type'] === 'venue') {
//     // Fetch the venue and get the related vendor's email
//     $venue = Venue::find($request->input('venue_id'));
//     if ($venue && $venue->vendor_id) {
//         $vendor = User::where('id', $venue->vendor_id)->where('role', 'vendor')->first();
//         $recipientEmail = $vendor ? $vendor->email : 'support@yourdomain.com';
//     } else {
//         $recipientEmail = 'support@yourdomain.com'; // fallback if no vendor linked
//     }
// } else {
//     $recipientEmail = 'support@yourdomain.com';
// }

//     // Prepare email data
//     $emailData = [
//         'name' => $validated['name'],
//         'email' => $validated['email'],
//         'phone' => $validated['phone'],
//         'bodymessage' => $validated['message'],
//         'type' => $validated['type'],
//     ];

//     // Send email
//     Mail::send('emails.contact', $emailData, function ($message) use ($recipientEmail, $validated) {
//         $message->to($recipientEmail)
//                 ->subject('New Contact Inquiry');
//         $message->replyTo($validated['email'], $validated['name']);
//     });

//     $message = 'Message sent successfully!';
// return redirect()->route('contact')->with('success', $message);
// }
public function storeContact(Request $request)
{
    // Validate input
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:20',
        'message' => 'required|string',
        'type' => 'required|string|in:general,vendor,event,venue',
        'vendor_id' => 'nullable|exists:users,id',
        'event_id' => 'nullable|exists:events,id',
        'venue_id' => 'nullable|exists:venues,id',
    ]);

    // Handle vendor_id validation
    if ($validated['type'] === 'vendor' && empty($validated['vendor_id'])) {
        return redirect()->back()->withErrors(['vendor_id' => 'Please select a vendor.'])->withInput();
    }

    // Save contact info in database
    Contact::create($validated);

    // Determine email recipient based on inquiry type
    if ($validated['type'] === 'general') {
        $recipientEmail = 'mah.bristiofficial@gmail.com'; // Your admin/support email
    } elseif ($validated['type'] === 'vendor') {
        // Find user with id equal to vendor_id and role 'vendor'
        $vendorUser = User::where('id', $validated['vendor_id'])->where('role', 'vendor')->first();
        $recipientEmail = $vendorUser ? $vendorUser->email : 'support@yourdomain.com';
        \Log::info('Vendor type: Vendor, Email: ' . $recipientEmail);
    } elseif ($validated['type'] === 'event') {
        // Fetch the event and get the related vendor's email
        $event = Event::find($request->input('event_id'));
        if ($event && $event->vendor_id) {
            $vendor = User::where('id', $event->vendor_id)->where('role', 'vendor')->first();
            $recipientEmail = $vendor ? $vendor->email : 'support@yourdomain.com';
            \Log::info('Event type: Event, Vendor email: ' . $recipientEmail);
        } else {
            $recipientEmail = 'mah.bristiofficial@gmail.com'; // fallback if no vendor linked
            \Log::info('Event type: Event, No vendor linked');
        }
    } elseif ($validated['type'] === 'venue') {
        // Fetch the venue and get the related vendor's email
        $venue = Venue::find($request->input('venue_id'));
        \Log::info('Venue ID: ' . ($request->input('venue_id') ?? 'null'));
        if ($venue && $venue->vendor_id) {
            $vendor = User::where('id', $venue->vendor_id)->where('role', 'vendor')->first();
            $recipientEmail = $vendor ? $vendor->email : 'support@yourdomain.com';
            \Log::info('Venue type: Venue, Vendor email: ' . $recipientEmail);
        } else {
            $recipientEmail = 'mah.bristiofficial@gmail.com'; // fallback if no vendor linked
            \Log::info('Venue type: Venue, No vendor linked');
        }
    } else {
        $recipientEmail = 'support@yourdomain.com';
        \Log::info('Default fallback email');
    }

    // Prepare email data
    $emailData = [
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'],
        'bodymessage' => $validated['message'],
        'type' => $validated['type'],
    ];

    // Send email with error handling
    try {
        \Log::info('Attempting to send email to: ' . $recipientEmail);
        Mail::send('emails.contact', $emailData, function ($message) use ($recipientEmail, $validated) {
            $message->to($recipientEmail)
                ->subject('New Contact Inquiry');
            $message->replyTo($validated['email'], $validated['name']);
        });
        \Log::info('Email sent successfully to: ' . $recipientEmail);
    } catch (\Exception $e) {
        \Log::error('Mail send error: ' . $e->getMessage());
    }

    $message = 'Message sent successfully!';
    return redirect()->route('contact')->with('success', $message);
}

    public function book(Request $request, $eventId)
    {
        $request->validate([
            'tickets' => 'required|integer|min:1',
        ]);

        $event = Event::findOrFail($eventId); // Make sure you have Event model

        if ($request->tickets > $event->available_seats) {
            return back()->with('error', 'Not enough seats available.');
        }

        $event->available_seats -= $request->tickets;
        $event->save();

        // Optionally, save booking to a separate Booking table

        return back()->with('success', 'Booking confirmed!');
    }
//     public function venues(Request $request)
// {
//     // Fetch query parameters
//     $venueName = $request->query('venue_name');
//     $startDate = $request->query('start_date');
//     $endDate = $request->query('end_date');
//     $minPrice = $request->query('min_price');
//     $maxPrice = $request->query('max_price');

//     // Start building the query
//     $query = Venue::query();

//     // Filter by venue name if provided
//     if ($venueName && $venueName != '') {
//         $query->where('venue_name', 'like', '%' . $venueName . '%');
//     }

//     // Filter by date range if applicable
//     if ($startDate && $startDate != '') {
//         $query->where('available_from', '>=', $startDate);
//     }
//     if ($endDate && $endDate != '') {
//         $query->where('available_to', '<=', $endDate);
//     }

//     // Filter by price if applicable
//     if ($minPrice && $minPrice != '') {
//         $query->where('base_price', '>=', $minPrice);
//     }
//     if ($maxPrice && $maxPrice != '') {
//         $query->where('base_price', '<=', $maxPrice);
//     }

// //     // Add ordering by 'available_from' in descending order
//    $venues = $query->orderBy('created_at', 'desc')->get();

//     return view('venues', compact('venues','venueName', 'startDate', 'endDate', 'minPrice', 'maxPrice'));
// }

public function venues(Request $request)
{
    // Fetch query parameters
    $searchTerm = $request->query('query');

    if (empty($searchTerm)) {
        $venues = Cache::remember(EventifyCacheService::KEY_VENUES_ALL, EventifyCacheService::TTL_MEDIUM, function () {
            return Venue::orderBy('created_at', 'desc')->get();
        });
    } else {
        $venues = Venue::where('venue_name', 'like', '%' . $searchTerm . '%')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // Check if the request expects JSON (AJAX) or a full page load
    if ($request->ajax() || $request->wantsJson()) {
        return response()->json($venues);
    }

    // Otherwise, return the view with venues
    return view('venues', compact('venues'));
}
}