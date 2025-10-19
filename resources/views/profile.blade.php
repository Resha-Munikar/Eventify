@extends('layouts.app')

@section('title', 'Profile')
@php 
    $noFooter = true; 
@endphp

@section('content')
<div class="max-w-2xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-lg mt-10">
    <h2 class="text-2xl font-bold mb-8 text-gray-900 dark:text-white text-center">My Profile</h2>
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row gap-8 items-center md:items-start">
        @csrf

        <!-- Left Side: Profile Photo -->
        <div class="relative flex flex-col items-center">
            <label for="profile_photo" class="cursor-pointer">
                @if($user->profile_photo)
                    <img id="photoPreview" src="{{ asset('uploads/profile_photos/' . $user->profile_photo) }}" 
                         alt="Profile Photo" 
                         class="w-28 h-28 sm:w-32 sm:h-32 rounded-full object-cover mb-2 transition transform hover:scale-105 shadow-md">
                @else
                    <div id="photoPreview" class="w-28 h-28 sm:w-32 sm:h-32 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center mb-2 transition transform hover:scale-105 shadow-inner">
                        <span class="text-gray-700 dark:text-gray-200 text-sm">No Photo</span>
                    </div>
                @endif
            </label>
            <input type="file" name="profile_photo" id="profile_photo" accept="image/*" class="hidden">

            <span class="text-sm text-gray-500 dark:text-gray-300 mt-2">Click the photo to change</span>
        </div>

        <!-- Right Side: Profile Details -->
        <div class="flex-1 space-y-6 w-full">
            <div>
                <label class="block text-gray-700 dark:text-gray-200 mb-1 font-semibold">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                       class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-purple-400">
            </div>

            <div>
                <label class="block text-gray-700 dark:text-gray-200 mb-1 font-semibold">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                       class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-purple-400">
            </div>

            <div class="text-center">
                <button type="submit" class="px-8 py-2 bg-[#8D85EC] hover:bg-[#7b76e4] text-white font-semibold rounded-lg shadow-md transition transform hover:scale-105">
                    Update Profile
                </button>
            </div>
        </div>
    </form>

    <!-- Bookings Section -->
    <div class="mt-8 text-center">
    <h3 class="text-xl font-semibold mb-2 text-gray-900 dark:text-white">My Bookings</h3>
    <div class="flex justify-center space-x-4">
        <a href="#" class="text-white px-4 py-2 rounded bg-[#8D85EC] hover:bg-[#7b76e4]">View My Events</a>
        <a href="{{ route('userbooking') }}" class="bg-[#8D85EC] hover:bg-[#7b76e4] text-white px-4 py-2 rounded ">View My Venue Bookings</a>
    </div>
</div>
</div>

<!-- Profile Photo Preview Script -->
<script>
    const profileInput = document.getElementById('profile_photo');
    const photoPreview = document.getElementById('photoPreview');

    profileInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if(photoPreview.tagName === 'IMG') {
                    photoPreview.src = e.target.result;
                } else {
                    const img = document.createElement('img');
                    img.id = 'photoPreview';
                    img.src = e.target.result;
                    img.className = 'w-28 h-28 sm:w-32 sm:h-32 rounded-full object-cover mb-2 transition transform hover:scale-105 shadow-md';
                    photoPreview.replaceWith(img);
                }
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
