@extends('layouts.app')

@section('title', 'Admin - Vendor KYC Management')
@php 
    $noNavbar = true; 
    $noFooter = true; 
@endphp

@section('content')
@include('admin.sidebar') 

<div class="ml-0 sm:ml-64 p-3 sm:p-6 min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200" x-data="adminKycManager()">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-100 dark:border-gray-700">
        
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-[#8d85ec]">Vendor KYC Requests</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Review and verify identification and business registration documents submitted by event organizers & vendors.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300">
                    <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                    {{ $pendingCount }} Pending Review
                </span>
                <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">
                    Total: <span class="font-bold text-gray-800 dark:text-gray-200">{{ $totalCount }}</span>
                </span>
            </div>
        </div>

        <!-- Metric Summary Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
            <!-- Total Requests -->
            <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-700/40 border border-gray-200 dark:border-gray-700 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/40 text-[#8d85ec] flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <div class="text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Submissions</div>
                    <div class="text-xl font-bold text-gray-900 dark:text-white">{{ $totalCount }}</div>
                </div>
            </div>

            <!-- Pending -->
            <div class="p-4 rounded-xl bg-blue-50/70 dark:bg-blue-900/20 border border-blue-200/80 dark:border-blue-700/50 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-800/40 text-blue-600 dark:text-blue-300 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 animate-spin-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <div class="text-[11px] font-bold text-blue-700 dark:text-blue-300 uppercase tracking-wider">Pending Review</div>
                    <div class="text-xl font-bold text-blue-800 dark:text-blue-200">{{ $pendingCount }}</div>
                </div>
            </div>

            <!-- Approved -->
            <div class="p-4 rounded-xl bg-green-50/70 dark:bg-green-900/20 border border-green-200/80 dark:border-green-700/50 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-800/40 text-green-600 dark:text-green-300 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <div class="text-[11px] font-bold text-green-700 dark:text-green-300 uppercase tracking-wider">Approved Vendors</div>
                    <div class="text-xl font-bold text-green-800 dark:text-green-200">{{ $approvedCount }}</div>
                </div>
            </div>

            <!-- Rejected -->
            <div class="p-4 rounded-xl bg-rose-50/70 dark:bg-rose-900/20 border border-rose-200/80 dark:border-rose-700/50 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-rose-100 dark:bg-rose-800/40 text-rose-600 dark:text-rose-300 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div>
                    <div class="text-[11px] font-bold text-rose-700 dark:text-rose-300 uppercase tracking-wider">Rejected</div>
                    <div class="text-xl font-bold text-rose-800 dark:text-rose-200">{{ $rejectedCount }}</div>
                </div>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <form method="GET" action="{{ route('admin.kyc.index') }}" class="mb-6 bg-gray-50 dark:bg-gray-700/30 p-4 rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
                
                <!-- Search -->
                <div class="lg:col-span-4 relative">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search business, vendor name, email, PAN..." 
                           class="w-full pl-9 pr-3 py-2 text-xs rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-[#8d85ec] focus:outline-none">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <!-- Status Filter -->
                <div class="lg:col-span-3">
                    <select name="status" class="w-full py-2 px-3 text-xs rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-[#8d85ec] focus:outline-none">
                        <option value="all">All Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <!-- Document Type Filter -->
                <div class="lg:col-span-3">
                    <select name="document_type" class="w-full py-2 px-3 text-xs rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-[#8d85ec] focus:outline-none">
                        <option value="all">All Document Types</option>
                        <option value="Citizenship" {{ request('document_type') === 'Citizenship' ? 'selected' : '' }}>Citizenship</option>
                        <option value="Passport" {{ request('document_type') === 'Passport' ? 'selected' : '' }}>Passport</option>
                        <option value="Business Registration" {{ request('document_type') === 'Business Registration' ? 'selected' : '' }}>Business Registration</option>
                        <option value="National ID" {{ request('document_type') === 'National ID' ? 'selected' : '' }}>National ID</option>
                        <option value="Driving License" {{ request('document_type') === 'Driving License' ? 'selected' : '' }}>Driving License</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="lg:col-span-2 flex items-center gap-2">
                    <button type="submit" class="w-full py-2 px-3 bg-[#8d85ec] hover:bg-[#7b76e4] text-white text-xs font-semibold rounded-lg shadow-sm transition">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'status', 'document_type', 'date_filter']))
                        <a href="{{ route('admin.kyc.index') }}" class="p-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 transition text-xs flex items-center justify-center" title="Reset Filters">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                </div>

            </div>
        </form>

        <!-- KYC Table -->
        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
            <table class="w-full text-left text-xs text-gray-700 dark:text-gray-300">
                <thead class="bg-gray-100 dark:bg-gray-700/60 text-gray-600 dark:text-gray-300 uppercase text-[11px] font-bold tracking-wider">
                    <tr>
                        <th class="px-4 py-3">Vendor / Organizer</th>
                        <th class="px-4 py-3">Business Name & PAN</th>
                        <th class="px-4 py-3">Doc Type</th>
                        <th class="px-4 py-3">Attached Files</th>
                        <th class="px-4 py-3">Submitted At</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60 bg-white dark:bg-gray-800">
                    @forelse($kycs as $kyc)
                        <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700/40 transition">
                            
                            <!-- Vendor Column -->
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-purple-100 dark:bg-purple-900/50 text-[#8d85ec] flex items-center justify-center font-bold text-xs">
                                        {{ strtoupper(substr($kyc->user->name ?? 'V', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900 dark:text-white">{{ $kyc->user->name ?? 'Unknown' }}</div>
                                        <div class="text-[11px] text-gray-500 dark:text-gray-400">{{ $kyc->user->email ?? 'No email' }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Business Name & PAN -->
                            <td class="px-4 py-3.5">
                                <div class="font-semibold text-gray-900 dark:text-white">{{ $kyc->business_name }}</div>
                                <div class="text-[11px] text-gray-500 dark:text-gray-400">
                                    PAN/VAT: <span class="font-medium text-gray-700 dark:text-gray-300">{{ $kyc->pan_vat_number ?? 'N/A' }}</span>
                                </div>
                            </td>

                            <!-- Document Type -->
                            <td class="px-4 py-3.5">
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                    {{ $kyc->document_type }}
                                </span>
                            </td>

                            <!-- Attached Documents -->
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    @if($kyc->document_front)
                                        <button type="button" 
                                                @click="viewDocument('{{ $kyc->business_name }} - Front', '{{ $kyc->document_front_url }}', {{ \App\Models\VendorKyc::isPdf($kyc->document_front) ? 'true' : 'false' }})"
                                                class="px-2 py-1 rounded bg-purple-50 hover:bg-purple-100 dark:bg-purple-900/30 dark:hover:bg-purple-900/60 text-[#8d85ec] text-[10px] font-semibold transition inline-flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            Front
                                        </button>
                                    @endif

                                    @if($kyc->document_back)
                                        <button type="button" 
                                                @click="viewDocument('{{ $kyc->business_name }} - Back', '{{ $kyc->document_back_url }}', {{ \App\Models\VendorKyc::isPdf($kyc->document_back) ? 'true' : 'false' }})"
                                                class="px-2 py-1 rounded bg-purple-50 hover:bg-purple-100 dark:bg-purple-900/30 dark:hover:bg-purple-900/60 text-[#8d85ec] text-[10px] font-semibold transition inline-flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            Back
                                        </button>
                                    @endif

                                    @if($kyc->company_registration_doc)
                                        <button type="button" 
                                                @click="viewDocument('{{ $kyc->business_name }} - Reg Doc', '{{ $kyc->company_registration_doc_url }}', {{ \App\Models\VendorKyc::isPdf($kyc->company_registration_doc) ? 'true' : 'false' }})"
                                                class="px-2 py-1 rounded bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/30 dark:hover:bg-blue-900/60 text-blue-600 dark:text-blue-300 text-[10px] font-semibold transition inline-flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            Reg Doc
                                        </button>
                                    @endif
                                </div>
                            </td>

                            <!-- Submitted At -->
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <div class="text-gray-900 dark:text-white font-medium">{{ $kyc->created_at->format('M d, Y') }}</div>
                                <div class="text-[10px] text-gray-500 dark:text-gray-400">{{ $kyc->created_at->format('h:i A') }}</div>
                            </td>

                            <!-- Status Badge -->
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                @if($kyc->isPending())
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                        Pending
                                    </span>
                                @elseif($kyc->isApproved())
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Approved
                                    </span>
                                @elseif($kyc->isRejected())
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300" title="{{ $kyc->rejection_reason }}">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Rejected
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    
                                    <!-- Inspect Details -->
                                    <button type="button" 
                                            @click="openDetailsModal({{ $kyc->id }})"
                                            class="p-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 transition" 
                                            title="View Full Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>

                                    <!-- Quick Approve (if not approved) -->
                                    @if(!$kyc->isApproved())
                                        <form action="{{ route('admin.kyc.approve', $kyc->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to approve KYC for {{ addslashes($kyc->business_name) }}?');">
                                            @csrf
                                            <button type="submit" 
                                                    class="px-2.5 py-1.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold text-[11px] shadow-xs transition inline-flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                Approve
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Quick Reject Modal Trigger -->
                                    @if(!$kyc->isRejected())
                                        <button type="button" 
                                                @click="openRejectModal({{ $kyc->id }}, '{{ addslashes($kyc->business_name) }}', '{{ addslashes($kyc->user->name ?? 'Vendor') }}')"
                                                class="px-2.5 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-semibold text-[11px] shadow-xs transition inline-flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            Reject
                                        </button>
                                    @endif

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span class="font-medium text-sm">No KYC requests found.</span>
                                    <p class="text-xs text-gray-400 mt-1">Submitted vendor verification requests will appear here.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $kycs->links() }}
        </div>

    </div>

    <!-- MODAL 1: Document Viewer Modal -->
    <div x-show="docModal.open" 
         x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center bg-black/70 backdrop-blur-xs p-4"
         @click.self="docModal.open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-4xl w-full overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="p-4 bg-gray-100 dark:bg-gray-700/60 flex items-center justify-between border-b border-gray-200 dark:border-gray-700">
                <h3 class="font-bold text-gray-900 dark:text-white text-sm truncate" x-text="docModal.title"></h3>
                <div class="flex items-center gap-2">
                    <a :href="docModal.url" target="_blank" class="px-3 py-1 bg-[#8d85ec] text-white text-xs font-semibold rounded-lg hover:bg-[#7b76e4] transition">
                        Open in New Tab &nearr;
                    </a>
                    <button @click="docModal.open = false" class="text-gray-500 hover:text-gray-800 dark:hover:text-white text-lg font-bold px-2">
                        &times;
                    </button>
                </div>
            </div>
            <div class="p-4 bg-gray-900 flex items-center justify-center min-h-[400px] max-h-[75vh] overflow-auto">
                <template x-if="!docModal.isPdf">
                    <img :src="docModal.url" class="max-h-[70vh] max-w-full object-contain rounded-lg">
                </template>
                <template x-if="docModal.isPdf">
                    <iframe :src="docModal.url" class="w-full h-[65vh] rounded-lg border-0 bg-white"></iframe>
                </template>
            </div>
        </div>
    </div>

    <!-- MODAL 2: Details Modal -->
    <div x-show="detailsModal.open" 
         x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center bg-black/60 backdrop-blur-xs p-4"
         @click.self="detailsModal.open = false"
         x-transition>
        
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="p-5 bg-[#8d85ec] text-white flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-lg">Vendor KYC Details</h3>
                    <p class="text-xs text-white/90" x-text="'Application ID #' + (detailsModal.kyc ? detailsModal.kyc.id : '')"></p>
                </div>
                <button @click="detailsModal.open = false" class="text-white hover:text-gray-200 text-2xl font-bold leading-none">
                    &times;
                </button>
            </div>

            <div class="p-6 space-y-5" x-show="detailsModal.kyc">
                <div class="grid grid-cols-2 gap-4 bg-gray-50 dark:bg-gray-700/40 p-4 rounded-xl text-xs">
                    <div>
                        <span class="text-gray-400 uppercase font-bold block mb-0.5">Vendor Name</span>
                        <span class="font-bold text-gray-900 dark:text-white text-sm" x-text="detailsModal.kyc ? detailsModal.kyc.vendor_name : ''"></span>
                    </div>
                    <div>
                        <span class="text-gray-400 uppercase font-bold block mb-0.5">Vendor Email</span>
                        <span class="font-bold text-gray-900 dark:text-white text-sm" x-text="detailsModal.kyc ? detailsModal.kyc.vendor_email : ''"></span>
                    </div>
                    <div>
                        <span class="text-gray-400 uppercase font-bold block mb-0.5">Business Name</span>
                        <span class="font-bold text-gray-900 dark:text-white text-sm" x-text="detailsModal.kyc ? detailsModal.kyc.business_name : ''"></span>
                    </div>
                    <div>
                        <span class="text-gray-400 uppercase font-bold block mb-0.5">PAN / VAT</span>
                        <span class="font-bold text-gray-900 dark:text-white text-sm" x-text="detailsModal.kyc && detailsModal.kyc.pan_vat_number ? detailsModal.kyc.pan_vat_number : 'N/A'"></span>
                    </div>
                    <div>
                        <span class="text-gray-400 uppercase font-bold block mb-0.5">Document Type</span>
                        <span class="font-bold text-gray-900 dark:text-white text-sm" x-text="detailsModal.kyc ? detailsModal.kyc.document_type : ''"></span>
                    </div>
                    <div>
                        <span class="text-gray-400 uppercase font-bold block mb-0.5">Status</span>
                        <span class="font-bold uppercase" :class="{
                            'text-blue-600': detailsModal.kyc && detailsModal.kyc.status === 'pending',
                            'text-green-600': detailsModal.kyc && detailsModal.kyc.status === 'approved',
                            'text-rose-600': detailsModal.kyc && detailsModal.kyc.status === 'rejected',
                        }" x-text="detailsModal.kyc ? detailsModal.kyc.status : ''"></span>
                    </div>
                </div>

                <!-- Rejection feedback if any -->
                <template x-if="detailsModal.kyc && detailsModal.kyc.rejection_reason">
                    <div class="p-3.5 bg-rose-50 dark:bg-rose-950/40 border-l-4 border-rose-500 rounded-lg text-xs">
                        <span class="font-bold text-rose-800 dark:text-rose-300 block mb-1">Rejection Feedback:</span>
                        <p class="text-rose-900 dark:text-rose-200" x-text="detailsModal.kyc.rejection_reason"></p>
                    </div>
                </template>

                <!-- Documents List -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">Uploaded Document Files</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <template x-if="detailsModal.kyc && detailsModal.kyc.document_front_url">
                            <a :href="detailsModal.kyc.document_front_url" target="_blank" class="p-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30 flex items-center justify-between hover:border-[#8d85ec] transition text-xs font-semibold text-gray-800 dark:text-gray-200">
                                <span>Front Page</span>
                                <span class="text-[#8d85ec]">&nearr;</span>
                            </a>
                        </template>
                        <template x-if="detailsModal.kyc && detailsModal.kyc.document_back_url">
                            <a :href="detailsModal.kyc.document_back_url" target="_blank" class="p-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30 flex items-center justify-between hover:border-[#8d85ec] transition text-xs font-semibold text-gray-800 dark:text-gray-200">
                                <span>Back Page</span>
                                <span class="text-[#8d85ec]">&nearr;</span>
                            </a>
                        </template>
                        <template x-if="detailsModal.kyc && detailsModal.kyc.company_registration_doc_url">
                            <a :href="detailsModal.kyc.company_registration_doc_url" target="_blank" class="p-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30 flex items-center justify-between hover:border-[#8d85ec] transition text-xs font-semibold text-gray-800 dark:text-gray-200">
                                <span>Reg. Certificate</span>
                                <span class="text-[#8d85ec]">&nearr;</span>
                            </a>
                        </template>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="flex justify-end gap-2 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <template x-if="detailsModal.kyc && detailsModal.kyc.status !== 'approved'">
                        <form :action="'/admin/kyc-requests/' + detailsModal.kyc.id + '/approve'" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl text-xs font-bold transition">
                                Approve KYC
                            </button>
                        </form>
                    </template>
                    <template x-if="detailsModal.kyc && detailsModal.kyc.status !== 'rejected'">
                        <button type="button" 
                                @click="detailsModal.open = false; openRejectModal(detailsModal.kyc.id, detailsModal.kyc.business_name, detailsModal.kyc.vendor_name)"
                                class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition">
                            Reject with Reason
                        </button>
                    </template>
                    <button type="button" @click="detailsModal.open = false" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl text-xs font-semibold hover:bg-gray-300 transition">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL 3: Reject Prompt Modal -->
    <div x-show="rejectModal.open" 
         x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center bg-black/60 backdrop-blur-xs p-4"
         @click.self="rejectModal.open = false"
         x-transition>
        
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="p-5 bg-rose-600 text-white flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-lg">Reject KYC Verification</h3>
                    <p class="text-xs text-white/90" x-text="rejectModal.businessName + ' (' + rejectModal.vendorName + ')'"></p>
                </div>
                <button @click="rejectModal.open = false" class="text-white hover:text-gray-200 text-2xl font-bold leading-none">
                    &times;
                </button>
            </div>

            <form :action="'/admin/kyc-requests/' + rejectModal.kycId + '/reject'" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label for="rejection_reason" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">
                        Reason for Rejection / Feedback <span class="text-red-500">*</span>
                    </label>
                    <textarea name="rejection_reason" 
                              id="rejection_reason" 
                              rows="4" 
                              class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent text-sm"
                              placeholder="Please explain clearly why the KYC documents were rejected so the vendor can resubmit correctly (e.g. Document image is blurry, PAN certificate is expired, or name does not match)."
                              required></textarea>
                    <p class="text-[11px] text-gray-400 mt-1">This explanation will be emailed to the vendor and displayed on their dashboard.</p>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-gray-100 dark:border-gray-700">
                    <button type="button" @click="rejectModal.open = false" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl text-xs font-semibold hover:bg-gray-300 transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-md transition">
                        Confirm Rejection & Send Feedback
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
function adminKycManager() {
    return {
        docModal: {
            open: false,
            title: '',
            url: '',
            isPdf: false,
        },
        detailsModal: {
            open: false,
            kyc: null,
        },
        rejectModal: {
            open: false,
            kycId: null,
            businessName: '',
            vendorName: '',
        },
        viewDocument(title, url, isPdf) {
            this.docModal.title = title;
            this.docModal.url = url;
            this.docModal.isPdf = isPdf;
            this.docModal.open = true;
        },
        openDetailsModal(kycId) {
            fetch(`/admin/kyc-requests/${kycId}`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.detailsModal.kyc = data.kyc;
                    this.detailsModal.open = true;
                }
            })
            .catch(err => console.error('Error fetching KYC details:', err));
        },
        openRejectModal(kycId, businessName, vendorName) {
            this.rejectModal.kycId = kycId;
            this.rejectModal.businessName = businessName;
            this.rejectModal.vendorName = vendorName;
            this.rejectModal.open = true;
        }
    };
}
</script>
@endsection
