<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venue;

class VendorVenueController extends Controller
{
    // Show all venues for the logged-in vendor
    public function index()
    {
        $venues = Venue::where('vendor_id', auth()->id())->get();
        return view('vendor.venues.index', compact('venues'));
    }

    // Store new venue
    public function store(Request $request)
    {
        $request->validate([
            'venue_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'price_per_person' => 'required|numeric',
            'image' => 'required|image|max:2048',
        ]);

        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('uploads'), $imageName);

        Venue::create([
            'venue_name' => $request->venue_name,
            'location' => $request->location,
            'description' => $request->description,
            'price_per_person' => $request->price_per_person,
            'image' => $imageName,
            'vendor_id' => auth()->id(),
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('vendor.venues.index')
            ->with('success', 'Venue added successfully!');
    }

    // Edit venue
    public function edit(Venue $venue)
    {
        return view('vendor.venues.edit', compact('venue'));
    }

    // Update venue
public function update(Request $request, Venue $venue)
{
    $request->validate([
        'venue_name' => 'required|string|max:255',
        'location' => 'required|string|max:255',
        'description' => 'required|string',
        'price_per_person' => 'required|numeric',
        'image' => 'nullable|image|max:2048',
    ]);

    // Handle image upload
    if ($request->hasFile('image')) {
        // Delete old image if exists
        if ($venue->image && file_exists(public_path('uploads/' . $venue->image))) {
            unlink(public_path('uploads/' . $venue->image));
        }

        // Save new image
        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('uploads'), $imageName);
        $venue->image = $imageName;
    }

    // Update venue details
    $venue->update([
        'venue_name' => $request->venue_name,
        'location' => $request->location,
        'description' => $request->description,
        'price_per_person' => $request->price_per_person,
    ]);

    return redirect()->route('vendor.venues.index')
        ->with('success', 'Venue updated successfully!');
}


    // Delete venue
    public function destroy(Venue $venue)
    {
        $venue->delete();
        return redirect()->route('vendor.venues.index')
            ->with('success', 'Venue deleted successfully!');
    }
}
