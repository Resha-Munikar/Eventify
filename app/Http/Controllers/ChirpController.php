<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use App\Models\Contact;
use App\Models\User;
use App\Models\Event;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function welcome(){
        return view('welcome');
    }

    public function about(){
        return view('about');
    }
    
//     public function events(Request $request){
//     // Fetch the query parameter 'category' from URL
//     $category = $request->query('category');

//     // If category filter is present, filter events
//     if ($category && $category != '') {
//         $events = Event::where('category', $category)->get();
//     } else {
//         $events = Event::all();
//     }

//     return view('events', compact('events', 'category'));
// }
public function events(Request $request){
    // Fetch query parameters
    $category = $request->query('category');
    $venue = $request->query('venue');
    $startDate = $request->query('start_date');
    $endDate = $request->query('end_date');
    $minPrice = $request->query('min_price');
    $maxPrice = $request->query('max_price');

    // Start building the query
    $query = Event::query();

    // Filter by category if provided
    if ($category && $category != '') {
        $query->where('category', $category);
    }

    // Filter by venue if provided
    if ($venue && $venue != '') {
        $query->where('venue', $venue);
    }

    // Filter by date range if provided
    if ($startDate && $startDate != '') {
        $query->where('event_date', '>=', $startDate);
    }
    if ($endDate && $endDate != '') {
        $query->where('event_date', '<=', $endDate);
    }

    // Filter by price range if provided
    if ($minPrice && $minPrice != '') {
        $query->where('price', '>=', $minPrice);
    }
    if ($maxPrice && $maxPrice != '') {
        $query->where('price', '<=', $maxPrice);
    }

    // Order by event_date descending (latest first)
    $events = $query->orderBy('event_date', 'desc')->get();

    return view('events', compact('events', 'category', 'venue', 'startDate', 'endDate', 'minPrice', 'maxPrice'));
}
      // Show contact form
    public function contact()
    {
        return view('contact'); // your contact form view
    }

    // Store contact form data
    public function storeContact(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'required|string',
        ]);

        // Save to database
        Contact::create($validated);

        // Redirect back with success message
        return redirect()->route('contact')->with('success', 'Message successful !');
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
    public function venues(Request $request)
{
    // Fetch query parameters
    $searchTerm = $request->query('query'); // Assuming 'query' is the search input name

    // Start building the query
    $query = Venue::query();

    // If a search term is provided, filter by venue_name
    if ($searchTerm && $searchTerm != '') {
        $query->where('venue_name', 'like', '%' . $searchTerm . '%');
    }

    // You can keep other filters if needed, or remove them for simplicity

    // Fetch venues ordered by creation date
    $venues = $query->orderBy('created_at', 'desc')->get();

    // Check if the request expects JSON (AJAX) or a full page load
    if ($request->ajax() || $request->wantsJson()) {
        return response()->json($venues);
    }

    // Otherwise, return the view with venues
    return view('venues', compact('venues'));
}
public function getUpcomingEvents($limit = 5){
    return Event::orderBy('event_date', 'asc')->take($limit)->get();
}

}