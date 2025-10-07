@extends('layouts.app')

@section('title', 'My Venues')
@php 
    $noNavbar = true; 
    $noFooter = true; 
@endphp

@section('content')
@include('vendor.sidebar')

<div class="ml-0 sm:ml-64 p-6 bg-gray-100 dark:bg-gray-900 min-h-screen overflow-x-hidden">

    <!-- Header & Add Venue Button -->
    <div class="mb-6 mt-6 max-w-4xl mx-auto flex justify-between">
        <h2 class="text-3xl font-bold text-[#8d85ec] truncate">My Venues</h2>
        
        <button id="toggleFormBtn"
            class="bg-gradient-to-r from-purple-500 to-purple-700 hover:from-purple-600 hover:to-purple-800 text-white px-5 py-2 rounded-full shadow-md flex items-center transition transform hover:-translate-y-0.5">
            <span id="toggleIcon" class="inline-block mr-2 transition-transform duration-300">+</span>
            <span id="toggleText">Add Venue</span>
        </button>
    </div>

    <!-- Add Venue Form -->
    <div id="addVenueFormWrapper" class="max-w-xl mx-auto overflow-hidden transition-all duration-500" style="height:0">
        <div id="addVenueForm" class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-5">
            <h2 class="text-xl font-bold text-[#8d85ec] mb-4 text-center">Add New Venue</h2>

            <form action="{{ route('vendor.venues.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                @csrf

                <div>
                    <label class="block mb-1 text-gray-700 dark:text-gray-200 text-sm">Venue Name</label>
                    <input type="text" name="venue_name" value="{{ old('venue_name') }}"
                        class="w-full p-2 rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm" required>
                </div>

                <!-- Location with Map -->
                <div>
                    <label class="block mb-1 text-gray-700 dark:text-gray-200 text-sm">Location</label>
                    <input id="venue-location" type="text" name="location" value="{{ old('location') }}"
                        class="w-full p-2 rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm" placeholder="Search or click on map..." required>
                    <div id="map" class="mt-3 rounded-lg border border-gray-300 dark:border-gray-700" style="height:250px;"></div>
                </div>

                <div>
                    <label class="block mb-1 text-gray-700 dark:text-gray-200 text-sm">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full p-2 rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm" required>{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="block mb-1 text-gray-700 dark:text-gray-200 text-sm">Price Per Person</label>
                    <input type="number" name="price_per_person" value="{{ old('price_per_person') }}"
                        class="w-full p-2 rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm" required>
                </div>

                <div>
                    <label class="block mb-1 text-gray-700 dark:text-gray-200 text-sm">Venue Image</label>
                    <input type="file" name="image" class="w-full text-gray-700 dark:text-gray-200 text-sm" accept="image/*" required>
                </div>

                <div class="flex justify-center gap-12 mt-8">
                    <button type="submit"
                        class="bg-gradient-to-r from-purple-500 to-purple-700 hover:from-purple-600 hover:to-purple-800 text-white px-4 py-2 rounded-full font-semibold shadow-md transition text-sm w-40">
                        Add Venue
                    </button>
                    <button type="button" id="cancelFormBtn"
                        class="bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-full font-semibold transition text-sm w-40">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Venues Grid -->
    @if($venues->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-10 max-w-6xl mx-auto">
            @foreach($venues as $venue)
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl overflow-hidden hover:shadow-2xl transition transform hover:-translate-y-1 hover:scale-105 w-full">
                    <img src="{{ asset('uploads/' . $venue->image) }}" alt="{{ $venue->venue_name }}" class="h-40 w-full object-cover">

                    <div class="p-5 flex flex-col gap-2">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white truncate">{{ $venue->venue_name }}</h3>
                        <p class="text-gray-900 font-medium dark:text-gray-200 text-sm line-clamp-2">{{ $venue->description }}</p>
                        <p class="text-gray-600 dark:text-gray-300 text-sm truncate">Location: {{ $venue->location }}</p>
                        <p class="text-[#8d85ec] font-semibold text-sm mt-1">Price: Rs {{ number_format($venue->price_per_person, 2) }} / person</p>
                        <div class="flex justify-between mt-3 gap-2">
                            <a href="{{ route('vendor.venues.edit', $venue->id) }}"
                               class="flex-1 bg-gradient-to-r from-green-400 to-green-600 hover:from-green-500 hover:to-green-700 text-white font-semibold text-sm py-2 rounded-lg text-center transition">Edit</a>

                            <form action="{{ route('vendor.venues.destroy', $venue->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure?');" class="flex-1">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="w-full bg-gradient-to-r from-red-400 to-red-600 hover:from-red-500 hover:to-red-700 text-white font-semibold text-sm py-2 rounded-lg transition">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center mt-20">
            <p class="text-gray-700 dark:text-gray-200 text-lg font-semibold">No venues found. Start by adding a new venue!</p>
        </div>
    @endif

</div>

<!-- Map Script -->
<script>
function initMap() {
    const defaultLocation = { lat: 27.7172, lng: 85.3240 }; // Kathmandu
    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 13,
        center: defaultLocation,
    });
    const marker = new google.maps.Marker({
        position: defaultLocation,
        map: map,
        draggable: true,
    });

    const input = document.getElementById("venue-location");
    const searchBox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    searchBox.addListener("places_changed", () => {
        const places = searchBox.getPlaces();
        if (places.length === 0) return;
        const place = places[0];
        if (!place.geometry || !place.geometry.location) return;
        map.setCenter(place.geometry.location);
        marker.setPosition(place.geometry.location);
    });

    marker.addListener("dragend", function() {
        const position = marker.getPosition();
        input.value = position.lat().toFixed(6) + ", " + position.lng().toFixed(6);
    });
}

const toggleBtn = document.getElementById('toggleFormBtn');
const formWrapper = document.getElementById('addVenueFormWrapper');
const toggleIcon = document.getElementById('toggleIcon');
const toggleText = document.getElementById('toggleText');
const cancelBtn = document.getElementById('cancelFormBtn');

toggleBtn.addEventListener('click', () => {
    if(formWrapper.style.height === '0px' || formWrapper.style.height === ''){
        formWrapper.style.height = formWrapper.scrollHeight + 'px';
        toggleIcon.style.transform = 'rotate(45deg)';
        toggleText.textContent = 'Close Form';
    } else {
        formWrapper.style.height = '0';
        toggleIcon.style.transform = 'rotate(0deg)';
        toggleText.textContent = 'Add Venue';
    }
});

cancelBtn.addEventListener('click', () => {
    formWrapper.style.height = '0';
    toggleIcon.style.transform = 'rotate(0deg)';
    toggleText.textContent = 'Add Venue';
});
</script>

<!-- Load Google Maps API -->
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places&callback=initMap">
</script>
@endsection
