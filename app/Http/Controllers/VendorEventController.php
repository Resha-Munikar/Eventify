<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
 use Illuminate\Support\Str;

class VendorEventController extends Controller
{
    // Display all events for the logged-in vendor
    public function index()
    {
        $events = Event::where('vendor_id', Auth::id())->latest()->paginate(9);
        return view('vendor.events.index', compact('events'));
    }


    // Show form to create a new event
    public function create()
    {
        return view('vendor.events.create');
    }

    // Store a new event
    public function store(Request $request)
    {
        $request->validate([
            'event_name' => 'required|string|max:255',
            'event_date' => 'required|date',
            'category' => 'nullable|string|max:255',
            'venue' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'available_seats' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

       // Generate a unique filename with extension
$extension = $request->file('image')->getClientOriginalExtension();
$filename = Str::uuid() . '.' . $extension;

// Save the image to 'public/uploads' directory
$request->file('image')->move(public_path('uploads'), $filename);

// Store the filename or relative path in the database
$imagePath = $filename;
        Event::create([
            'vendor_id' => Auth::id(),
            'event_name' => $request->event_name,
            'event_date' => $request->event_date,
            'venue' => $request->venue,
            'category' => $request->category,
            'description' => $request->description,
            'price' => $request->price,
            'available_seats' => $request->available_seats,
           'image' => $imagePath,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->route('vendor.events.index')->with('success', 'Event added successfully!');
    }

    // Show form to edit an existing event
    public function edit(Event $event)
    {
        // Ensure the vendor owns the event
        if ($event->vendor_id !== Auth::id()) {
            abort(403);
        }

        return view('vendor.events.edit', compact('event'));
    }

    // Update an existing event
    public function update(Request $request, Event $event)
    {
        if ($event->vendor_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'event_name' => 'required|string|max:255',
            'event_date' => 'required|date',
            'category' => 'nullable|string|max:255',
            'venue' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'available_seats' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($event->image && file_exists(public_path('uploads/' . $event->image))) {
                unlink(public_path('uploads/' . $event->image));
            }

            // Save new image
            $extension = $request->file('image')->getClientOriginalExtension();
            $filename = \Str::uuid() . '.' . $extension;
            $request->file('image')->move(public_path('uploads'), $filename);

            $event->image = $filename;
        }

        // Update other event details
        $event->update([
            'event_name' => $request->event_name,
            'event_date' => $request->event_date,
            'category' => $request->category,
            'venue' => $request->venue,
            'description' => $request->description,
            'price' => $request->price,
            'available_seats' => $request->available_seats,
        ]);

        return redirect()->route('vendor.events.index')->with('success', 'Event updated successfully!');
    }


    // Delete an event
    public function destroy(Event $event)
    {
        if ($event->vendor_id !== Auth::id()) {
            abort(403);
        }

        $event->delete();

        return redirect()->route('vendor.events.index')->with('success', 'Event deleted successfully!');
    }
}
