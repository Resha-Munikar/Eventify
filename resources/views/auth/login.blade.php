@extends('layouts.app')

@section('title', 'Login')

@section('content')

<div class="w-full max-w-sm mx-auto">
    @if ($errors->any())
    @foreach ($errors->all() as $error)
            <div id="toast-danger" class="flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow-sm dark:text-gray-400 dark:bg-gray-800" role="alert">
                <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg dark:bg-red-800 dark:text-red-200">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>
                    </svg>
                    <span class="sr-only">Error icon</span>
                </div>
                <div class="ms-3 text-sm font-normal">{{ $error }}</div>
                <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" data-dismiss-target="#toast-danger" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>
    @endforeach
    @endif
</div>

<div class="bg-[#FFF8F0] min-h-screen flex justify-center items-start pt-6">
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden w-[420px] flex flex-col">
        <div class="w-full p-5 flex flex-col justify-center">
            <h2 class="text-3xl font-bold text-[#8d85ec] mb-4 text-center">Login</h2>
            <form action="{{ route('login') }}" method="POST" class="space-y-2">
            @csrf
            <!-- Email -->
            <div class="mb-3">
            <label class="block text-sm font-medium text-gray-900">Email</label>
            <input name="email" type="email" id="email" 
                    class="w-full mt-1 px-2 py-2 border rounded-lg focus:ring-2 focus:ring-[#C48F3A] outline-none" 
                    placeholder="eventify@gmail.com" required />
            </div>
            <!-- Password -->
            <div class="mb-3">
            <label class="block text-sm font-medium text-gray-900">Password</label>
            <div class="relative mt-1">
                <input type="password" id="password" name="password" required 
                    class="w-full px-2 py-2 border rounded-lg pr-10"/>
            </div>
            </div>

            <!-- Button -->
            <button type="submit" 
                    class="w-full py-2 rounded-lg font-semibold transition text-white 
                        bg-[#8d85ec] hover:bg-[#883AFE]">
            Login
            </button>

            <p class="mt-3 text-sm text-gray-600 text-center">
                Don't have an account? <a href="{{route('register')}}" class="text-[#F76C6C] font-medium hover:underline">Sign Up</a>
            </p>  
</div>
</div>
</div>

@endsection