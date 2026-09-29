<aside id="vendor-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
    <div class="h-full px-6 py-6 overflow-y-auto bg-gray-200 dark:bg-gray-800">

        <!-- Profile Section -->
        <div class="flex flex-col items-center mb-4">
            <div class="relative">
                @if(Auth::user()->profile_photo_url)
                    <img class="w-20 h-20 rounded-full border-4 border-[#8d85ec] object-cover shadow-sm" 
                         src="{{ Auth::user()->profile_photo_url }}" 
                         alt="{{ Auth::user()->name }}">
                @else
                    <div class="w-20 h-20 rounded-full border-4 border-[#8d85ec] bg-[#8D85EC] text-white font-bold flex items-center justify-center text-2xl shadow-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif

                <span class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></span>
            </div>
            <h2 class="mt-3 text-lg font-semibold text-gray-900 dark:text-white">
                {{ Auth::user()->name ?? 'User Name' }}
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 truncate max-w-[180px] text-center">
                {{ Auth::user()->email ?? 'user@example.com' }}
            </p>

            <a href="{{ route('profile.show') }}" 
            class="mt-3 inline-block text-sm text-[#8d85ec] hover:underline">
                View Profile
            </a>
        </div>

        <ul class="space-y-2 font-medium">

            <!-- Dashboard -->
            <li>
                <a href="{{ route('vendor.dashboard') }}" 
                   class="flex items-center p-2 rounded-lg group transition-colors duration-200 ease-in-out
                          {{ request()->routeIs('vendor.dashboard') ? 'bg-gray-300 dark:bg-gray-700 text-[#8d85ec]' : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 text-[#8d85ec]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M3 9.75L12 3l9 6.75V21a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1V9.75z"/>
                    </svg>
                    <span class="ms-3">Dashboard</span>
                </a>
            </li>

            <!-- My Events -->
            <li>
                <a href="{{ route('vendor.events.index') }}" 
                   class="flex items-center p-2 rounded-lg group transition-colors duration-200 ease-in-out
                          {{ request()->routeIs('vendor.events.*') ? 'bg-gray-300 dark:bg-gray-700 text-[#8d85ec]' : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 text-[#8d85ec]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M7 10h5v5H7zM3 5h18v2H3zm0 4h18v13H3z"/>
                    </svg>
                    <span class="ms-3 flex-1 whitespace-nowrap">My Events</span>
                </a>
            </li>

          

            <!-- My Event Booking -->
            <li>
                <a href="{{ route('vendor.eventbooking') }}"
                class="flex items-center p-2 rounded-lg group transition-colors duration-200 ease-in-out
                        {{ request()->routeIs('vendor.eventbooking') ? 'bg-gray-300 dark:bg-gray-700 text-[#8d85ec]' : 'text-gray-900 dark:text-white' }}">
                    <svg class="w-5 h-5 text-[#8d85ec]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 2h6v2h3a1 1 0 011 1v16a1 1 0 01-1 1H6a1 1 0 01-1-1V5a1 1 0 011-1h3V2zm2 2v2h2V4h-2zm-2 6h8v2H9v-2zm0 4h8v2H9v-2z"/>
                    </svg>
                    <span class="ms-3 flex-1 whitespace-nowrap">My Event Booking</span>
                </a>
            </li>

          

        <!-- Event Booking Report -->
<li>
    <a href="{{ route('vendor.reports.eventbooking') }}"
       class="flex items-center p-2 rounded-lg group transition-colors duration-200 ease-in-out
              {{ request()->routeIs('vendor.reports.eventbooking')
                  ? 'bg-gray-300 dark:bg-gray-700 text-[#8d85ec]'
                  : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">

        <svg class="w-5 h-5 text-[#8d85ec]" fill="none" stroke="currentColor"
             viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 17v-2m3 2v-4m3 4v-6m2 10H5a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v12a2 2 0 01-2 2z"/>
        </svg>

        <span class="ms-3 flex-1 whitespace-nowrap">Event Booking Report</span>
    </a>
</li>


            <!-- Inquiries -->
            @php
                $vendorUnreadInquiries = \App\Models\Inquiry::where('vendor_id', Auth::id())->where('status', 'unread')->count();
            @endphp
            <li>
                <a href="{{ route('vendor.inquiries.index') }}" 
                   class="flex items-center p-2 rounded-lg group transition-colors duration-200 ease-in-out
                          {{ request()->routeIs('vendor.inquiries.*') ? 'bg-gray-300 dark:bg-gray-700 text-[#8d85ec]' : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 text-[#8d85ec]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                    <span class="ms-3 flex-1 whitespace-nowrap">Inquiries</span>
                    @if($vendorUnreadInquiries > 0)
                        <span class="inline-flex items-center justify-center px-2 py-0.5 ms-2 text-[10px] font-bold text-white bg-[#8d85ec] rounded-full">
                            {{ $vendorUnreadInquiries }}
                        </span>
                    @endif
                </a>
            </li>

            <!-- Reviews -->
            <li>
                <a href="{{ route('vendor.venue-reviews') }}" 
                   class="flex items-center p-2 rounded-lg group transition-colors duration-200 ease-in-out
                          {{ request()->routeIs('vendor.venue-reviews') ? 'bg-gray-300 dark:bg-gray-700 text-[#8d85ec]' : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 text-[#8d85ec]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M3 21V3h18v18H3zm2-16v14h14V5H5zm2 2h2v2H7V7zm0 4h2v2H7v-2zm0 4h2v2H7v-2zm4-8h2v2h-2V7zm0 4h2v2h-2v-2zm0 4h2v2h-2v-2zm4-8h2v2h-2V7zm0 4h2v2h-2v-2zm0 4h2v2h-2v-2z"/>
                    </svg>
                    <span class="ms-3 flex-1 whitespace-nowrap">Reviews</span>
                </a>
            </li>

         <!-- KYC Verification -->
@php
    $sidebarKyc = Auth::user()->kyc;
    $sidebarKycStatus = $sidebarKyc ? $sidebarKyc->status : 'not_submitted';
@endphp

<li>
    <a href="{{ route('vendor.kyc.index') }}" 
       class="flex items-center p-2 rounded-lg group transition-colors duration-200 ease-in-out
              {{ request()->routeIs('vendor.kyc.*') 
                  ? 'bg-gray-300 dark:bg-gray-700 text-[#8d85ec]' 
                  : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">

        <!-- KYC Shield Icon -->
        <svg class="w-5 h-5 flex-shrink-0 text-[#8d85ec]"
             fill="none"
             stroke="currentColor"
             stroke-width="2"
             viewBox="0 0 24 24"
             xmlns="http://www.w3.org/2000/svg">

            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M12 3l7 4v5c0 4.5-3 7.8-7 9-4-1.2-7-4.5-7-9V7l7-4z"/>

            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M9 12l2 2 4-4"/>
        </svg>

        <span class="ms-3 flex-1 whitespace-nowrap">
            KYC Verification
        </span>

        @if($sidebarKycStatus === 'approved')

            <!-- Approved -->
            <span class="inline-flex items-center justify-center px-2 py-0.5 ms-2
                         text-[10px] font-bold
                         text-green-700 bg-green-100
                         dark:bg-green-900/60 dark:text-green-300
                         rounded-full">
                Verified
            </span>

      @elseif($sidebarKycStatus === 'pending')
    <span class="inline-flex items-center justify-center px-2.5 py-1 ms-2
                 text-[10px] font-bold
                 text-amber-700 bg-amber-100
                 rounded-full whitespace-nowrap">
        Pending
    </span>

        @elseif($sidebarKycStatus === 'rejected')

            <!-- Rejected -->
            <span class="inline-flex items-center justify-center px-2 py-0.5 ms-2
                         text-[10px] font-bold
                         text-rose-700 bg-rose-100
                         dark:bg-rose-900/60 dark:text-rose-300
                         rounded-full">
                Action
            </span>

        @else

            <!-- Not Submitted -->
            <span class="inline-flex items-center justify-center px-2 py-0.5 ms-2
                         text-[10px] font-bold
                         text-amber-700 bg-amber-100
                         dark:bg-amber-900/60 dark:text-amber-300
                         rounded-full">
                Required
            </span>

        @endif

    </a>
</li>
            <!-- Logout -->
            <li>
                <form action="{{ route('vendor.vendorLogout') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="flex items-center w-full p-2 rounded-lg group transition-colors duration-200 ease-in-out
                                   text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 text-left">
                        <svg class="w-5 h-5 text-[#8d85ec]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor">
                            <g transform="scale(-1,1) translate(-512,0)">
                                <path d="M497 273L329 441c-15 15-41 4.5-41-17v-96H192c-17.7 0-32-14.3-32-32V216c0-17.7 14.3-32 32-32h96v-96c0-21.5 26-32 41-17l168 168c9.4 9.4 9.4 24.6 0 34zM160 64H96c-17.7 0-32 14.3-32 32v320c0 17.7 14.3 32 32 32h64c17.7 0 32 14.3 32 32s-14.3 32-32 32H96c-53 0-96-43-96-96V96c0-53 43-96 96-96h64c17.7 0 32 14.3 32 32s-14.3 32-32 32z"/>
                            </g>
                        </svg>
                        <span class="ms-3 flex-1 whitespace-nowrap">Sign Out</span>
                    </button>
                </form>
            </li>

        </ul>
    </div>
</aside>

<script src="//unpkg.com/alpinejs" defer></script>