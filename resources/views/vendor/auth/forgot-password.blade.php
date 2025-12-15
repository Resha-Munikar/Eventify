@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="bg-[#FFF8F0] dark:bg-gray-800 min-h-screen flex justify-center items-start pt-10">
    <div class="bg-white dark:bg-gray-700 shadow-xl rounded-2xl overflow-hidden w-[420px] flex flex-col">
        <div class="w-full p-5 flex flex-col justify-center">
            <h2 class="text-2xl font-bold text-[#8d85ec] mb-4 text-center">Forgot Password</h2>

            <!-- Success toast -->
@if (session('status'))
<div id="toast-success" class="flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow-sm dark:text-gray-400 dark:bg-gray-800 mx-auto" role="alert">
    <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
        <!-- Checkmark Icon -->
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
    </div>
    <div class="ms-3 text-sm font-normal">{{ session('status') }}</div>
    <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-500 dark:hover:text-white dark:hover:bg-gray-700" data-dismiss-target="#toast-success" aria-label="Close">
        <span class="sr-only">Close</span>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>
@endif


            <form method="POST" action="{{ route('vendor.password.email') }}" class="space-y-3">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-900 dark:text-gray-200">
                        Email Address
                    </label>
                    <input id="email" type="email" name="email" required autofocus
                        class="w-full mt-1 px-2 py-2 border rounded-lg focus:ring-2 focus:ring-[#C48F3A] outline-none dark:bg-gray-700 dark:text-gray-200"
                        placeholder="Enter your registered email" />
                </div>

                <button type="submit" 
                    class="w-full py-2 rounded-lg font-semibold transition text-white bg-[#8d85ec] hover:bg-[#883AFE]">
                    Send Reset Link
                </button>

                <p class="mt-3 text-sm text-gray-600 dark:text-gray-400 text-center">
                    Remembered your password? 
                    <a href="{{ route('login') }}" class="text-[#F76C6C] font-medium hover:underline">Login</a>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection
