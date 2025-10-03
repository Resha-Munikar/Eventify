<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use App\Models\Contact;
use App\Models\User;
use App\Models\Event;
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
    
    public function events(Request $request){
    // Fetch the query parameter 'category' from URL
    $category = $request->query('category');

    // If category filter is present, filter events
    if ($category && $category != '') {
        $events = Event::where('category', $category)->get();
    } else {
        $events = Event::all();
    }

    return view('events', compact('events', 'category'));
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
        return redirect()->route('contact')->with('success', 'Your message has been sent!');
    }
}
