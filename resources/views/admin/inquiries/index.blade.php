@extends('layouts.app')

@section('title', 'Admin - Contact & Inquiry Management')
@php 
$noNavbar = true;
$noFooter = true; 
@endphp

@section('content')
@include('admin.sidebar') 

<div class="ml-0 sm:ml-64 p-3 sm:p-6 min-h-screen" x-data="adminInquiryManager()">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-100 dark:border-gray-700">
        
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-[#8d85ec]">Contact & Inquiry Management</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Manage customer contact messages and inquiries sent to vendors and administrators.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 dark:bg-purple-900/40 text-[#8d85ec]">
                    <span class="w-2 h-2 rounded-full bg-[#8d85ec] animate-pulse"></span>
                    {{ $unreadCount }} Unread
                </span>
                <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">
                    Total: <span class="font-bold text-gray-800 dark:text-gray-200">{{ $totalCount }}</span>
                </span>
            </div>
        </div>

        <!-- Metric Summary Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
            <!-- Total Inquiries -->
            <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-700/40 border border-gray-200 dark:border-gray-700 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/40 text-[#8d85ec] flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                </div>
                <div>
                    <div class="text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Inquiries</div>
                    <div class="text-xl font-bold text-gray-900 dark:text-white">{{ $totalCount }}</div>
                </div>
            </div>

            <!-- Unread -->
            <div class="p-4 rounded-xl bg-amber-50/60 dark:bg-amber-900/20 border border-amber-200/80 dark:border-amber-700/50 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-800/40 text-amber-600 dark:text-amber-400 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <div class="text-[11px] font-bold text-amber-700 dark:text-amber-400 uppercase tracking-wider">Unread Messages</div>
                    <div class="text-xl font-bold text-amber-800 dark:text-amber-300">{{ $unreadCount }}</div>
                </div>
            </div>

            <!-- Resolved -->
            <div class="p-4 rounded-xl bg-green-50/60 dark:bg-green-900/20 border border-green-200/80 dark:border-green-700/50 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-800/40 text-green-600 dark:text-green-400 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <div class="text-[11px] font-bold text-green-700 dark:text-green-400 uppercase tracking-wider">Resolved</div>
                    <div class="text-xl font-bold text-green-800 dark:text-green-300">{{ $resolvedCount }}</div>
                </div>
            </div>

            <!-- Today's Inquiries -->
            <div class="p-4 rounded-xl bg-purple-50/60 dark:bg-purple-900/20 border border-purple-200/80 dark:border-purple-700/50 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-800/40 text-[#8d85ec] flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <div class="text-[11px] font-bold text-[#8d85ec] dark:text-purple-300 uppercase tracking-wider">Received Today</div>
                    <div class="text-xl font-bold text-gray-900 dark:text-white">{{ $todayCount }}</div>
                </div>
            </div>
        </div>

        <!-- Filter / Search Toolbar -->
        <form method="GET" action="{{ route('admin.inquiries.index') }}" 
              class="mb-6 p-3.5 sm:p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <!-- 1. Search (Customer Name, Email, Vendor, Message) -->
                <div class="sm:col-span-2 lg:col-span-1">
                    <label for="search" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Search Inquiries</label>
                    <div class="relative">
                        <input type="text" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Search customer, vendor, message..." 
                               class="block w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2 text-xs dark:bg-gray-800 dark:text-white pl-8 focus:ring-[#8d85ec] focus:border-[#8d85ec]">
                        <svg class="w-4 h-4 text-gray-400 absolute left-2.5 top-2.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- 2. Type Filter -->
                <div>
                    <label for="type" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Inquiry Type</label>
                    <select id="type" 
                            name="type" 
                            class="block w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2 text-xs dark:bg-gray-800 dark:text-white focus:ring-[#8d85ec] focus:border-[#8d85ec]">
                        <option value="">All Types</option>
                        <option value="general" {{ request('type') === 'general' ? 'selected' : '' }}>General Contact</option>
                        <option value="event" {{ request('type') === 'event' ? 'selected' : '' }}>Event Inquiry</option>
                        <option value="venue" {{ request('type') === 'venue' ? 'selected' : '' }}>Venue Inquiry</option>
                        <option value="vendor" {{ request('type') === 'vendor' ? 'selected' : '' }}>Vendor Inquiry</option>
                    </select>
                </div>

                <!-- 3. Status Filter -->
                <div>
                    <label for="status" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Status</label>
                    <select id="status" 
                            name="status" 
                            class="block w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2 text-xs dark:bg-gray-800 dark:text-white focus:ring-[#8d85ec] focus:border-[#8d85ec]">
                        <option value="">All Statuses</option>
                        <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>New / Unread</option>
                        <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
                        <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                    </select>
                </div>

                <!-- 4. Vendor Filter -->
                <div>
                    <label for="vendor_id" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Assigned Vendor</label>
                    <select id="vendor_id" 
                            name="vendor_id" 
                            class="block w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2 text-xs dark:bg-gray-800 dark:text-white focus:ring-[#8d85ec] focus:border-[#8d85ec]">
                        <option value="">All Vendors</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}" {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                {{ $vendor->name }} ({{ $vendor->email }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2 justify-end mt-3 pt-2 border-t border-gray-200 dark:border-gray-600">
                <button type="submit" 
                        class="bg-[#8D85EC] hover:bg-[#7b76e4] text-white px-4 py-1.5 rounded-lg text-xs font-semibold transition shadow-sm inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Apply Filters
                </button>
                <a href="{{ route('admin.inquiries.index') }}" 
                   class="px-3.5 py-1.5 bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 rounded-lg text-xs font-semibold transition">
                    Reset
                </a>
            </div>
        </form>

        <!-- Inquiries Table -->
        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 mb-5">
            <table class="w-full text-xs sm:text-sm text-left border-collapse">
                <thead class="bg-[#8D85EC] text-white text-[11px] sm:text-xs uppercase tracking-wider">
                    <tr>
                        <th scope="col" class="px-3.5 py-3">Customer</th>
                        <th scope="col" class="px-3 py-3 text-center">Type</th>
                        <th scope="col" class="px-3.5 py-3">Subject / Related</th>
                        <th scope="col" class="px-3.5 py-3">Target Vendor</th>
                        <th scope="col" class="px-3 py-3 text-center">Status</th>
                        <th scope="col" class="px-3.5 py-3">Date</th>
                        <th scope="col" class="px-3.5 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-gray-800 dark:text-gray-200">
                    @forelse($inquiries as $inquiry)
                        <tr class="hover:bg-purple-50/50 dark:hover:bg-gray-700/50 transition {{ $inquiry->status === 'unread' ? 'bg-amber-50/30 dark:bg-amber-900/10 font-medium' : '' }}">
                            
                            <!-- Customer Column -->
                            <td class="px-3.5 py-3">
                                <div class="font-bold text-gray-900 dark:text-white flex items-center gap-1.5">
                                    {{ $inquiry->name }}
                                    @if($inquiry->status === 'unread')
                                        <span class="w-2 h-2 rounded-full bg-amber-500 inline-block" title="Unread"></span>
                                    @endif
                                </div>
                                <div class="text-[11px] text-gray-500 dark:text-gray-400">
                                    {{ $inquiry->email }}
                                </div>
                                @if(!empty($inquiry->phone))
                                    <div class="text-[10px] text-gray-400 dark:text-gray-500">
                                        {{ $inquiry->phone }}
                                    </div>
                                @endif
                            </td>

                            <!-- Type Column -->
                            <td class="px-3 py-3 text-center whitespace-nowrap">
                                @if($inquiry->type === 'general')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                        General
                                    </span>
                                @elseif($inquiry->type === 'event')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300">
                                        Event
                                    </span>
                                @elseif($inquiry->type === 'venue')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">
                                        Venue
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300">
                                        Vendor
                                    </span>
                                @endif
                            </td>

                            <!-- Subject / Related Column -->
                            <td class="px-3.5 py-3">
                                <div class="font-semibold text-gray-900 dark:text-white truncate max-w-[200px]" title="{{ $inquiry->subject }}">
                                    {{ $inquiry->subject ?? 'No Subject' }}
                                </div>
                                <div class="text-[11px] text-gray-500 dark:text-gray-400 truncate max-w-[200px]">
                                    @if($inquiry->event)
                                        <span class="text-[#8d85ec]">Event:</span> {{ $inquiry->event->event_name }}
                                    @elseif($inquiry->venue)
                                        <span class="text-[#8d85ec]">Venue:</span> {{ $inquiry->venue->venue_name }}
                                    @else
                                        {{ \Illuminate\Support\Str::limit($inquiry->message, 35) }}
                                    @endif
                                </div>
                            </td>

                            <!-- Vendor Column -->
                            <td class="px-3.5 py-3 whitespace-nowrap">
                                @if($inquiry->vendor)
                                    <div class="font-semibold text-gray-900 dark:text-white">
                                        {{ $inquiry->vendor->name }}
                                    </div>
                                    <div class="text-[10px] text-gray-500 dark:text-gray-400">
                                        {{ $inquiry->vendor->email }}
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400 italic">Platform / Admin</span>
                                @endif
                            </td>

                            <!-- Status Column -->
                            <td class="px-3 py-3 text-center whitespace-nowrap">
                                @if($inquiry->status === 'unread')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300">
                                        New / Unread
                                    </span>
                                @elseif($inquiry->status === 'read')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">
                                        Read
                                    </span>
                                @elseif($inquiry->status === 'resolved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                                        Resolved
                                    </span>
                                @endif
                            </td>

                            <!-- Date Column -->
                            <td class="px-3.5 py-3 whitespace-nowrap">
                                <div class="font-medium text-gray-900 dark:text-gray-200">
                                    {{ $inquiry->created_at->format('M d, Y') }}
                                </div>
                                <div class="text-[11px] text-gray-400 dark:text-gray-500">
                                    {{ $inquiry->created_at->format('h:i A') }}
                                </div>
                            </td>

                            <!-- Actions Column -->
                            <td class="px-3.5 py-3 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <!-- View Details Button -->
                                    <button type="button"
                                            @click="openModal({{ json_encode($inquiry) }})"
                                            class="inline-flex items-center gap-1 bg-[#8D85EC] hover:bg-[#7b76e4] text-white px-2.5 py-1.5 rounded-lg text-xs font-semibold transition shadow-sm">
                                        <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        View
                                    </button>

                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.inquiries.destroy', $inquiry->id) }}" method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this inquiry?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition"
                                                title="Delete Inquiry">
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400 font-medium">
                                <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-[#8d85ec] text-3xl">
                                    <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <p class="text-base font-semibold text-gray-700 dark:text-gray-300">No contact messages or inquiries found.</p>
                                <p class="text-xs text-gray-400 mt-1">Try clearing your search or adjusting the filters above.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($inquiries->hasPages())
            <div class="mt-4">
                {{ $inquiries->links() }}
            </div>
        @endif
    </div>

    <!-- Inquiry Details Modal -->
    <div x-show="showModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-xs flex items-center justify-center p-4"
         style="display: none;">
        
        <div @click.away="closeModal()" 
             class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full p-6 border border-gray-100 dark:border-gray-700 overflow-hidden transform transition-all">
            
            <!-- Modal Header -->
            <div class="flex items-start justify-between pb-4 border-b border-gray-100 dark:border-gray-700">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xs font-bold uppercase tracking-wider text-[#8d85ec]" x-text="activeInquiry?.type?.toUpperCase() + ' INQUIRY'"></span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold"
                              :class="{
                                  'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300': activeInquiry?.status === 'unread',
                                  'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300': activeInquiry?.status === 'read',
                                  'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300': activeInquiry?.status === 'resolved'
                              }"
                              x-text="activeInquiry?.status?.toUpperCase()"></span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white" x-text="activeInquiry?.subject || 'Contact Inquiry'"></h3>
                </div>
                <button @click="closeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-1.5 rounded-lg transition">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="py-4 space-y-4 max-h-[70vh] overflow-y-auto">
                <!-- Customer & Sender Info Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-3.5 rounded-xl bg-gray-50 dark:bg-gray-700/40 border border-gray-200 dark:border-gray-600">
                    <div>
                        <div class="text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase">Customer Name</div>
                        <div class="text-sm font-semibold text-gray-900 dark:text-white" x-text="activeInquiry?.name"></div>
                    </div>
                    <div>
                        <div class="text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase">Email Address</div>
                        <div class="text-sm font-semibold text-gray-900 dark:text-white" x-text="activeInquiry?.email"></div>
                    </div>
                    <div x-show="activeInquiry?.phone">
                        <div class="text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase">Phone Number</div>
                        <div class="text-sm font-semibold text-gray-900 dark:text-white" x-text="activeInquiry?.phone"></div>
                    </div>
                    <div>
                        <div class="text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase">Submission Date</div>
                        <div class="text-sm font-semibold text-gray-900 dark:text-white" x-text="activeInquiry ? new Date(activeInquiry.created_at).toLocaleString() : ''"></div>
                    </div>
                </div>

                <!-- Related Context (Event / Venue / Vendor) -->
                <div x-show="activeInquiry?.event || activeInquiry?.venue || activeInquiry?.vendor" 
                     class="p-3.5 rounded-xl bg-purple-50/50 dark:bg-purple-900/20 border border-purple-100 dark:border-purple-800/40 text-xs">
                    <div class="font-bold text-[#8d85ec] uppercase mb-1">Target & Related Details</div>
                    <template x-if="activeInquiry?.event">
                        <div class="text-gray-700 dark:text-gray-300">
                            <strong>Event:</strong> <span x-text="activeInquiry.event.event_name"></span> (<span x-text="activeInquiry.event.venue"></span>)
                        </div>
                    </template>
                    <template x-if="activeInquiry?.venue">
                        <div class="text-gray-700 dark:text-gray-300">
                            <strong>Venue:</strong> <span x-text="activeInquiry.venue.venue_name"></span> (<span x-text="activeInquiry.venue.location"></span>)
                        </div>
                    </template>
                    <template x-if="activeInquiry?.vendor">
                        <div class="text-gray-700 dark:text-gray-300 mt-0.5">
                            <strong>Vendor:</strong> <span x-text="activeInquiry.vendor.name"></span> (<span x-text="activeInquiry.vendor.email"></span>)
                        </div>
                    </template>
                </div>

                <!-- Message Content -->
                <div>
                    <div class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Message Body</div>
                    <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 text-sm text-gray-800 dark:text-gray-200 whitespace-pre-wrap leading-relaxed"
                         x-text="activeInquiry?.message"></div>
                </div>
            </div>

            <!-- Modal Footer & Status Quick Update -->
            <div class="pt-4 border-t border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-3">
                <!-- Status Actions -->
                <div class="flex items-center gap-1.5 w-full sm:w-auto">
                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 mr-1">Status:</span>
                    
                    <button type="button" 
                            @click="changeStatus('unread')"
                            :class="activeInquiry?.status === 'unread' ? 'bg-amber-500 text-white' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300'"
                            class="px-2.5 py-1 rounded-lg text-xs font-semibold hover:opacity-90 transition">
                        Unread
                    </button>
                    
                    <button type="button" 
                            @click="changeStatus('read')"
                            :class="activeInquiry?.status === 'read' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300'"
                            class="px-2.5 py-1 rounded-lg text-xs font-semibold hover:opacity-90 transition">
                        Read
                    </button>
                    
                    <button type="button" 
                            @click="changeStatus('resolved')"
                            :class="activeInquiry?.status === 'resolved' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300'"
                            class="px-2.5 py-1 rounded-lg text-xs font-semibold hover:opacity-90 transition">
                        Resolved
                    </button>
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                    <a :href="'mailto:' + activeInquiry?.email + '?subject=' + encodeURIComponent('Re: ' + (activeInquiry?.subject || 'Your Inquiry on Eventify'))"
                       class="inline-flex items-center gap-1 bg-[#8D85EC] hover:bg-[#7b76e4] text-white px-3.5 py-1.5 rounded-lg text-xs font-semibold transition shadow-sm">
                        <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Reply via Email
                    </a>
                    
                    <button type="button" @click="closeModal()"
                            class="px-3.5 py-1.5 bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 rounded-lg text-xs font-semibold transition">
                        Close
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function adminInquiryManager() {
    return {
        showModal: false,
        activeInquiry: null,

        openModal(inquiry) {
            this.activeInquiry = inquiry;
            this.showModal = true;

            // Automatically mark as read if it was unread
            if (inquiry.status === 'unread') {
                this.changeStatus('read');
            }
        },

        closeModal() {
            this.showModal = false;
        },

        changeStatus(newStatus) {
            if (!this.activeInquiry) return;
            const inquiryId = this.activeInquiry.id;

            fetch(`/admin/inquiries/${inquiryId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.activeInquiry.status = newStatus;
                }
            })
            .catch(err => console.error('Status update failed:', err));
        }
    }
}
</script>
@endsection
