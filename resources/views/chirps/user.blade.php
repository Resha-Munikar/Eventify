@extends('layouts.app')

@section('title', 'Users')
@php $noNavbar = true; @endphp

@section('content')

@include('admin.sidebar')

<div class="max-w-6xl mx-auto mt-8 ml-72">
    <h2 class="text-3xl font-bold text-[#8d85ec] mb-6">Manage Users</h2>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400" style="background-color: #8d85ec; color: white;">
                <tr>
                    <th scope="col" class="px-6 py-3">Id</th>
                    <th scope="col" class="px-6 py-3">Name</th>
                    <th scope="col" class="px-6 py-3">Email</th>
                    <th scope="col" class="px-6 py-3">Role</th>
                    <th scope="col" class="px-6 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                        <td class="px-6 py-4">{{ $user->id }}</td>
                        <td class="px-6 py-4">{{ $user->name }}</td>
                        <td class="px-6 py-4">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            {{ $user->role ?? 'User' }}
                        </td>
                        <td class="px-6 py-4 flex space-x-4">
                            <a href="{{ route('chirps.adminEdit', $user->id) }}" class="text-[#8d85ec] hover:text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" 
                                    d="M11 5h2m-2 14h2m7-7h2M4 12h2m9.586-6.586a2 2 0 012.828 2.828L7.828 21H4v-3.828l11.586-11.586z" />
                            </svg>
                            </a>

                        <form action="{{ route('chirps.adminDestroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" 
                                        d="M6 18a2 2 0 002 2h8a2 2 0 002-2V7H6v11zM9 7V5a2 2 0 012-2h2a2 2 0 012 2v2M4 7h16" />
                                </svg>
                            </button>
                        </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
