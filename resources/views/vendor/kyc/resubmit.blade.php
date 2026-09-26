@extends('layouts.app')

@section('title', 'Resubmit KYC Verification - Eventify')
@php 
    $noNavbar = true; 
    $noFooter = true; 
@endphp

@section('content')
@include('vendor.sidebar')

<div class="ml-0 sm:ml-64 p-4 sm:p-8 min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200" x-data="kycResubmitManager()">
    <div class="max-w-4xl mx-auto space-y-6">

        <!-- Back Navigation -->
        <div class="flex items-center justify-between">
            <a href="{{ route('vendor.dashboard') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-gray-500 hover:text-[#8d85ec] dark:text-gray-400 dark:hover:text-[#8d85ec] transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Dashboard
            </a>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-800 dark:bg-rose-900/50 dark:text-rose-300">
                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                Action Required: Rejected
            </span>
        </div>

        <!-- 1. TOP CORRECTION BANNER -->
        <div class="bg-gradient-to-br from-rose-50 via-white to-red-50 dark:from-gray-800 dark:via-gray-800 dark:to-rose-950/40 p-6 sm:p-8 rounded-2xl border-2 border-rose-200 dark:border-rose-800 shadow-sm">
            <div class="flex flex-col sm:flex-row items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-500 text-white flex items-center justify-center flex-shrink-0 shadow-md shadow-rose-500/20 mt-1">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-gray-900 dark:text-white">
                        Your KYC Verification Requires Correction
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                        Please review the feedback below and upload the correct and valid documents to resubmit your KYC verification.
                    </p>

                    <!-- Rejection Reason Card -->
                    <div class="mt-4 p-4 rounded-xl bg-white dark:bg-gray-700/60 border-l-4 border-rose-500 shadow-xs">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400 block mb-1">
                            Rejection Reason / Admin Feedback:
                        </span>
                        <p class="text-sm font-semibold text-rose-950 dark:text-rose-100">
                            "{{ $kyc->rejection_reason ?? 'Incorrect document submission' }}"
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. RESUBMISSION FORM -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200/80 dark:border-gray-700 p-6 sm:p-8">
            
            <div class="border-b border-gray-100 dark:border-gray-700 pb-4 mb-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Correct & Update KYC Details</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                    You can replace flagged documents while retaining previously valid files.
                </p>
            </div>

            {{-- Global Validation Errors --}}
            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300">
                    <div class="font-bold text-xs uppercase tracking-wider mb-1 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Please fix the following errors before submitting:
                    </div>
                    <ul class="list-disc pl-5 text-xs space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('vendor.kyc.resubmit.process') }}" 
                  method="POST" 
                  enctype="multipart/form-data" 
                  @submit="isSubmitting = true"
                  class="space-y-6">
                @csrf

                <!-- Basic Organization Info -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Business Name -->
                    <div>
                        <label for="business_name" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">
                            Business / Organization Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="business_name" 
                               id="business_name" 
                               value="{{ old('business_name', $kyc->business_name ?? $user->name) }}"
                               class="w-full px-4 py-3 rounded-xl border @error('business_name') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#8d85ec] text-sm"
                               placeholder="e.g. Acme Event Management"
                               required>
                        @error('business_name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- PAN / VAT Number -->
                    <div>
                        <label for="pan_vat_number" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">
                            PAN / VAT Number <span class="text-gray-400 font-normal">(Optional)</span>
                        </label>
                        <input type="text" 
                               name="pan_vat_number" 
                               id="pan_vat_number" 
                               value="{{ old('pan_vat_number', $kyc->pan_vat_number ?? '') }}"
                               class="w-full px-4 py-3 rounded-xl border @error('pan_vat_number') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#8d85ec] text-sm"
                               placeholder="e.g. 600123456">
                        @error('pan_vat_number')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Document Type Selection -->
                <div>
                    <label for="document_type" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">
                        Identification Document Type <span class="text-red-500">*</span>
                    </label>
                    <select name="document_type" 
                            id="document_type" 
                            class="w-full px-4 py-3 rounded-xl border @error('document_type') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#8d85ec] text-sm"
                            required>
                        @php
                            $currentDocType = old('document_type', $kyc->document_type ?? 'Citizenship');
                        @endphp
                        <option value="Citizenship" {{ $currentDocType === 'Citizenship' ? 'selected' : '' }}>Citizenship Certificate</option>
                        <option value="Passport" {{ $currentDocType === 'Passport' ? 'selected' : '' }}>Passport</option>
                        <option value="Business Registration" {{ $currentDocType === 'Business Registration' ? 'selected' : '' }}>Company / Business Registration Certificate</option>
                        <option value="National ID" {{ $currentDocType === 'National ID' ? 'selected' : '' }}>National Identity Card (NID)</option>
                        <option value="Driving License" {{ $currentDocType === 'Driving License' ? 'selected' : '' }}>Driving License</option>
                    </select>
                    @error('document_type')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 3. DOCUMENT REPLACEMENT CARDS SECTION -->
                <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-1">Attached KYC Documents</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                        Review previously uploaded documents and click "Replace Document" for any file that needs correction.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                        <!-- CARD 1: DOCUMENT FRONT (Required) -->
                        <div class="border rounded-2xl p-4 bg-gray-50/70 dark:bg-gray-700/30 flex flex-col justify-between"
                             :class="newFiles.front ? 'border-purple-300 dark:border-purple-700' : 'border-gray-200 dark:border-gray-700'">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-bold text-gray-800 dark:text-gray-200">1. Document Front <span class="text-red-500">*</span></span>
                                    <template x-if="newFiles.front">
                                        <span class="text-[10px] font-bold text-purple-700 bg-purple-100 dark:bg-purple-900/60 dark:text-purple-300 px-2 py-0.5 rounded-full">New File</span>
                                    </template>
                                    <template x-if="!newFiles.front && existing.front">
                                        <span class="text-[10px] font-bold text-green-700 bg-green-100 dark:bg-green-900/60 dark:text-green-300 px-2 py-0.5 rounded-full">Retained</span>
                                    </template>
                                </div>

                                <!-- Preview Area -->
                                <div class="min-h-[120px] bg-white dark:bg-gray-800 rounded-xl p-3 border border-gray-200 dark:border-gray-700 flex flex-col items-center justify-center text-center">
                                    <!-- New File Selected -->
                                    <template x-if="newFiles.front">
                                        <div>
                                            <template x-if="newFiles.front.is_pdf">
                                                <div class="w-10 h-10 rounded-lg bg-red-100 text-red-600 flex items-center justify-center font-bold text-xs mx-auto mb-1">PDF</div>
                                            </template>
                                            <template x-if="!newFiles.front.is_pdf">
                                                <img :src="newFiles.front.url" class="h-20 mx-auto rounded object-cover shadow-xs mb-1">
                                            </template>
                                            <span class="text-[11px] font-semibold text-gray-800 dark:text-gray-200 block truncate max-w-[170px]" x-text="newFiles.front.name"></span>
                                        </div>
                                    </template>

                                    <!-- Existing File -->
                                    <template x-if="!newFiles.front && existing.front">
                                        <div>
                                            <template x-if="existing.front.is_pdf">
                                                <div class="w-10 h-10 rounded-lg bg-red-100 text-red-600 flex items-center justify-center font-bold text-xs mx-auto mb-1">PDF</div>
                                            </template>
                                            <template x-if="!existing.front.is_pdf">
                                                <img :src="existing.front.url" class="h-20 mx-auto rounded object-cover shadow-xs mb-1">
                                            </template>
                                            <a :href="existing.front.url" target="_blank" class="text-[11px] font-semibold text-[#8d85ec] hover:underline inline-flex items-center gap-1">
                                                View Current File &nearr;
                                            </a>
                                        </div>
                                    </template>

                                    <!-- No File -->
                                    <template x-if="!newFiles.front && !existing.front">
                                        <div class="text-gray-400 text-xs">
                                            <svg class="w-8 h-8 mx-auto mb-1 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                            Upload Front Page
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="mt-3 space-y-1.5">
                                <input type="file" 
                                       name="document_front" 
                                       x-ref="frontInput" 
                                       @change="handleFileSelected($event, 'front')"
                                       accept="image/jpeg,image/png,image/jpg,application/pdf"
                                       class="hidden">
                                
                                <button type="button" 
                                        @click="$refs.frontInput.click()"
                                        class="w-full py-2 px-3 rounded-xl bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 hover:border-[#8d85ec] text-xs font-semibold text-gray-700 dark:text-gray-200 transition shadow-xs">
                                    <span x-text="existing.front || newFiles.front ? 'Replace Document' : 'Upload Front Document'"></span>
                                </button>

                                <template x-if="newFiles.front && existing.front">
                                    <button type="button" 
                                            @click="cancelReplacement('front')"
                                            class="w-full py-1 text-[11px] text-gray-500 hover:text-rose-600 transition text-center font-medium">
                                        Keep Previous File
                                    </button>
                                </template>
                            </div>
                            @error('document_front')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- CARD 2: DOCUMENT BACK (Required) -->
                        <div class="border rounded-2xl p-4 bg-gray-50/70 dark:bg-gray-700/30 flex flex-col justify-between"
                             :class="newFiles.back ? 'border-purple-300 dark:border-purple-700' : 'border-gray-200 dark:border-gray-700'">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-bold text-gray-800 dark:text-gray-200">2. Document Back <span class="text-red-500">*</span></span>
                                    <template x-if="newFiles.back">
                                        <span class="text-[10px] font-bold text-purple-700 bg-purple-100 dark:bg-purple-900/60 dark:text-purple-300 px-2 py-0.5 rounded-full">New File</span>
                                    </template>
                                    <template x-if="!newFiles.back && existing.back">
                                        <span class="text-[10px] font-bold text-green-700 bg-green-100 dark:bg-green-900/60 dark:text-green-300 px-2 py-0.5 rounded-full">Retained</span>
                                    </template>
                                </div>

                                <!-- Preview Area -->
                                <div class="min-h-[120px] bg-white dark:bg-gray-800 rounded-xl p-3 border border-gray-200 dark:border-gray-700 flex flex-col items-center justify-center text-center">
                                    <template x-if="newFiles.back">
                                        <div>
                                            <template x-if="newFiles.back.is_pdf">
                                                <div class="w-10 h-10 rounded-lg bg-red-100 text-red-600 flex items-center justify-center font-bold text-xs mx-auto mb-1">PDF</div>
                                            </template>
                                            <template x-if="!newFiles.back.is_pdf">
                                                <img :src="newFiles.back.url" class="h-20 mx-auto rounded object-cover shadow-xs mb-1">
                                            </template>
                                            <span class="text-[11px] font-semibold text-gray-800 dark:text-gray-200 block truncate max-w-[170px]" x-text="newFiles.back.name"></span>
                                        </div>
                                    </template>

                                    <template x-if="!newFiles.back && existing.back">
                                        <div>
                                            <template x-if="existing.back.is_pdf">
                                                <div class="w-10 h-10 rounded-lg bg-red-100 text-red-600 flex items-center justify-center font-bold text-xs mx-auto mb-1">PDF</div>
                                            </template>
                                            <template x-if="!existing.back.is_pdf">
                                                <img :src="existing.back.url" class="h-20 mx-auto rounded object-cover shadow-xs mb-1">
                                            </template>
                                            <a :href="existing.back.url" target="_blank" class="text-[11px] font-semibold text-[#8d85ec] hover:underline inline-flex items-center gap-1">
                                                View Current File &nearr;
                                            </a>
                                        </div>
                                    </template>

                                    <template x-if="!newFiles.back && !existing.back">
                                        <div class="text-gray-400 text-xs">
                                            <svg class="w-8 h-8 mx-auto mb-1 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                            Upload Back Page
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="mt-3 space-y-1.5">
                                <input type="file" 
                                       name="document_back" 
                                       x-ref="backInput" 
                                       @change="handleFileSelected($event, 'back')"
                                       accept="image/jpeg,image/png,image/jpg,application/pdf"
                                       class="hidden">
                                
                                <button type="button" 
                                        @click="$refs.backInput.click()"
                                        class="w-full py-2 px-3 rounded-xl bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 hover:border-[#8d85ec] text-xs font-semibold text-gray-700 dark:text-gray-200 transition shadow-xs">
                                    <span x-text="existing.back || newFiles.back ? 'Replace Back Document' : 'Upload Back Document'"></span>
                                </button>

                                <template x-if="newFiles.back && existing.back">
                                    <button type="button" 
                                            @click="cancelReplacement('back')"
                                            class="w-full py-1 text-[11px] text-gray-500 hover:text-rose-600 transition text-center font-medium">
                                        Keep Previous File
                                    </button>
                                </template>
                            </div>
                            @error('document_back')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- CARD 3: COMPANY REGISTRATION DOC (Optional) -->
                        <div class="border rounded-2xl p-4 bg-gray-50/70 dark:bg-gray-700/30 flex flex-col justify-between"
                             :class="newFiles.company ? 'border-purple-300 dark:border-purple-700' : 'border-gray-200 dark:border-gray-700'">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-bold text-gray-800 dark:text-gray-200">3. Company Certificate <span class="text-gray-400 font-normal">(Optional)</span></span>
                                    <template x-if="newFiles.company">
                                        <span class="text-[10px] font-bold text-purple-700 bg-purple-100 dark:bg-purple-900/60 dark:text-purple-300 px-2 py-0.5 rounded-full">New File</span>
                                    </template>
                                    <template x-if="!newFiles.company && existing.company && !removed.company">
                                        <span class="text-[10px] font-bold text-green-700 bg-green-100 dark:bg-green-900/60 dark:text-green-300 px-2 py-0.5 rounded-full">Retained</span>
                                    </template>
                                    <template x-if="removed.company">
                                        <span class="text-[10px] font-bold text-rose-700 bg-rose-100 dark:bg-rose-900/60 dark:text-rose-300 px-2 py-0.5 rounded-full">Removed</span>
                                    </template>
                                </div>

                                <!-- Preview Area -->
                                <div class="min-h-[120px] bg-white dark:bg-gray-800 rounded-xl p-3 border border-gray-200 dark:border-gray-700 flex flex-col items-center justify-center text-center">
                                    <template x-if="newFiles.company">
                                        <div>
                                            <template x-if="newFiles.company.is_pdf">
                                                <div class="w-10 h-10 rounded-lg bg-red-100 text-red-600 flex items-center justify-center font-bold text-xs mx-auto mb-1">PDF</div>
                                            </template>
                                            <template x-if="!newFiles.company.is_pdf">
                                                <img :src="newFiles.company.url" class="h-20 mx-auto rounded object-cover shadow-xs mb-1">
                                            </template>
                                            <span class="text-[11px] font-semibold text-gray-800 dark:text-gray-200 block truncate max-w-[170px]" x-text="newFiles.company.name"></span>
                                        </div>
                                    </template>

                                    <template x-if="!newFiles.company && existing.company && !removed.company">
                                        <div>
                                            <template x-if="existing.company.is_pdf">
                                                <div class="w-10 h-10 rounded-lg bg-red-100 text-red-600 flex items-center justify-center font-bold text-xs mx-auto mb-1">PDF</div>
                                            </template>
                                            <template x-if="!existing.company.is_pdf">
                                                <img :src="existing.company.url" class="h-20 mx-auto rounded object-cover shadow-xs mb-1">
                                            </template>
                                            <a :href="existing.company.url" target="_blank" class="text-[11px] font-semibold text-[#8d85ec] hover:underline inline-flex items-center gap-1">
                                                View Current File &nearr;
                                            </a>
                                        </div>
                                    </template>

                                    <template x-if="(!newFiles.company && !existing.company) || removed.company">
                                        <div class="text-gray-400 text-xs">
                                            <svg class="w-8 h-8 mx-auto mb-1 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                            No certificate attached
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="mt-3 space-y-1.5">
                                <input type="hidden" name="remove_company_registration_doc" :value="removed.company ? '1' : '0'">
                                <input type="file" 
                                       name="company_registration_doc" 
                                       x-ref="compInput" 
                                       @change="handleFileSelected($event, 'company')"
                                       accept="image/jpeg,image/png,image/jpg,application/pdf"
                                       class="hidden">
                                
                                <button type="button" 
                                        @click="$refs.compInput.click()"
                                        class="w-full py-2 px-3 rounded-xl bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 hover:border-[#8d85ec] text-xs font-semibold text-gray-700 dark:text-gray-200 transition shadow-xs">
                                    <span x-text="existing.company || newFiles.company ? 'Replace Registration Doc' : 'Upload Registration Doc'"></span>
                                </button>

                                <template x-if="newFiles.company">
                                    <button type="button" 
                                            @click="cancelReplacement('company')"
                                            class="w-full py-1 text-[11px] text-gray-500 hover:text-rose-600 transition text-center font-medium">
                                        Cancel New File
                                    </button>
                                </template>
                            </div>
                            @error('company_registration_doc')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

                <!-- Guidelines Notice Box -->
                <div class="p-4 rounded-xl bg-purple-50/70 dark:bg-purple-950/20 border border-purple-100 dark:border-purple-900/40 text-xs text-gray-600 dark:text-gray-300 flex items-start gap-3">
                    <svg class="w-5 h-5 text-[#8d85ec] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <span class="font-bold text-gray-800 dark:text-gray-200">Resubmission Checklist:</span>
                        <ul class="list-disc pl-4 mt-1 space-y-0.5 text-gray-600 dark:text-gray-400">
                            <li>Check the admin feedback above to confirm which document was flagged.</li>
                            <li>Ensure document scans are high resolution, clearly readable, and not cropped or rotated.</li>
                            <li>Allowed file formats: JPG, PNG, or PDF up to 5MB each.</li>
                        </ul>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <a href="{{ route('vendor.dashboard') }}" class="w-full sm:w-auto px-5 py-3 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-semibold text-xs text-center transition">
                        Cancel & Return
                    </a>

                    <!-- Primary Resubmit CTA -->
                    <button type="submit" 
                            :disabled="isSubmitting"
                            class="w-full sm:w-auto px-8 py-3 rounded-xl bg-[#8d85ec] hover:bg-[#7b76e4] disabled:opacity-60 text-white font-bold text-sm shadow-md hover:shadow-lg transition transform active:scale-98 flex items-center justify-center gap-2">
                        <svg x-show="isSubmitting" class="w-4 h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="isSubmitting ? 'Submitting Documents...' : 'Resubmit for Verification'"></span>
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>

<script>
function kycResubmitManager() {
    return {
        isSubmitting: false,
        existing: {
            front: @json($kyc && $kyc->document_front ? ['url' => $kyc->document_front_url, 'is_pdf' => \App\Models\VendorKyc::isPdf($kyc->document_front)] : null),
            back: @json($kyc && $kyc->document_back ? ['url' => $kyc->document_back_url, 'is_pdf' => \App\Models\VendorKyc::isPdf($kyc->document_back)] : null),
            company: @json($kyc && $kyc->company_registration_doc ? ['url' => $kyc->company_registration_doc_url, 'is_pdf' => \App\Models\VendorKyc::isPdf($kyc->company_registration_doc)] : null),
        },
        newFiles: {
            front: null,
            back: null,
            company: null,
        },
        removed: {
            company: false,
        },
        handleFileSelected(event, type) {
            const file = event.target.files[0];
            if (!file) return;

            this.removed[type] = false;
            const isPdf = file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf');

            if (isPdf) {
                this.newFiles[type] = {
                    is_pdf: true,
                    name: file.name,
                    url: null,
                };
            } else {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.newFiles[type] = {
                        is_pdf: false,
                        name: file.name,
                        url: e.target.result,
                    };
                };
                reader.readAsDataURL(file);
            }
        },
        cancelReplacement(type) {
            this.newFiles[type] = null;
            if (this.$refs[type + 'Input']) {
                this.$refs[type + 'Input'].value = '';
            }
        }
    };
}
</script>
@endsection
