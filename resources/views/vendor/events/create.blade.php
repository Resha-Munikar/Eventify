@extends('layouts.app')

@section('title', 'Add Event')
@php $noNavbar = true; @endphp

@include('vendor.sidebar')

@section('content')
<div class="w-full mx-auto ml-0 sm:ml-64 p-6 bg-gray-100 dark:bg-gray-900 min-h-screen">
    <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 shadow rounded-2xl p-8">
        <h2 class="text-2xl font-bold text-[#8d85ec] mb-6 text-center">Add New Event</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('vendor.events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Event Name -->
            <div>
                <label class="block mb-2 text-gray-700 dark:text-gray-200">Event Name</label>
                <input type="text" name="event_name" value="{{ old('event_name') }}" class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200" required>
            </div>

            <!-- Event Date -->
            <div>
                <label class="block mb-2 text-gray-700 dark:text-gray-200">Event Date</label>
                <input type="date" name="event_date" value="{{ old('event_date') }}" class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200" required>
            </div>

            <!-- Venue / Location -->
            <div>
                <label class="block mb-2 text-gray-700 dark:text-gray-200">Venue / Location</label>
                <input id="location" type="text" name="venue" value="{{ old('venue') }}" class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200" required>
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">
                <div id="map" class="w-full h-64 mt-3 rounded-lg border border-gray-300 dark:border-gray-600"></div>
            </div>

            <!-- Description -->
            <div>
                <label class="block mb-2 text-gray-700 dark:text-gray-200">Description</label>
                <textarea name="description" rows="5" class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200" required>{{ old('description') }}</textarea>
            </div>

            <!-- Price -->
            <div>
                <label class="block mb-2 text-gray-700 dark:text-gray-200">Price</label>
                <input type="number" name="price" value="{{ old('price') }}" class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200" required>
            </div>

            <!-- Available Seats -->
            <div>
                <label class="block mb-2 text-gray-700 dark:text-gray-200">Available Seats</label>
                <input type="number" name="available_seats" value="{{ old('available_seats') }}" class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200" required>
            </div>

            <!-- Photo -->
            <div>
                <label class="block mb-2 text-gray-700 dark:text-gray-200">Event Photo</label>
                <input type="file" name="image" class="w-full text-gray-700 dark:text-gray-200" accept="image/*" required>
            </div>

            <!-- Submit -->
            <div class="text-center">
                <button type="submit" class="bg-[#8d85ec] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#7a72d6] transition">Add Event</button>
            </div>
        </form>
    </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places"></script>
<script>
let map, marker;
function initMap() {
    const defaultLatLng = { lat: -33.8688, lng: 151.2195 };
    map = new google.maps.Map(document.getElementById("map"), { center: defaultLatLng, zoom: 13 });
    marker = new google.maps.Marker({ position: defaultLatLng, map: map, draggable: true });

    const input = document.getElementById("location");
    const autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo("bounds", map);

    autocomplete.addListener("place_changed", function() {
        const place = autocomplete.getPlace();
        if (!place.geometry) return;
        map.setCenter(place.geometry.location);
        map.setZoom(15);
        marker.setPosition(place.geometry.location);
        document.getElementById('latitude').value = place.geometry.location.lat();
        document.getElementById('longitude').value = place.geometry.location.lng();
    });

    marker.addListener('dragend', function() {
        const pos = marker.getPosition();
        document.getElementById('latitude').value = pos.lat();
        document.getElementById('longitude').value = pos.lng();
    });
}
document.addEventListener("DOMContentLoaded", initMap);
</script>
@endsection
