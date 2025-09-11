@extends('layouts.app')

@section('title', 'Admin')
@php
    $noNavbar = true;
@endphp
@include('admin.sidebar')

@section('content')

   <div class="max-w-4xl mx-auto mt-8 space-y-8">
    <div>
         <h2 class="text-3xl font-bold text-[#8d85ec] mb-4 text-center">Admin Dashboard</h2>        
         <p class="text-center dark:text-gray-50">Welcome, {{ $user->name }}</p>

        <div class="flex justify-center w-full gap-8">
                <div class="mt-8 text-center w-1/3">
                    <!-- {{ $userCount }} users are registered. -->
                    
                    <a href="#" class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">

                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $totalCount }}</h5>
                    <p class="font-normal text-gray-700 dark:text-gray-400">Total</p>
                    </a>

                </div>
                <div class="mt-8 text-center w-1/3">
                    <!-- {{ $userCount }} users are registered. -->
                    
                    <a href="#" class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">

                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $adminCount }}</h5>
                    <p class="font-normal text-gray-700 dark:text-gray-400">Admins</p>
                    </a>

                </div>
                <div class="mt-8 text-center w-1/3">
                    <!-- {{ $userCount }} users are registered. -->
                    
                    <a href="#" class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">

                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $userCount }}</h5>
                    <p class="font-normal text-gray-700 dark:text-gray-400">Users</p>
                    </a>
                </div>
                <div class="mt-8 text-center w-1/3">
                    <!-- {{ $userCount }} users are registered. -->
                    
                    <a href="#" class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">

                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $vendorCount }}</h5>
                    <p class="font-normal text-gray-700 dark:text-gray-400">Vendors</p>
                    </a>
                </div>
        </div>
    </div>
</div>

</div>
@endsection