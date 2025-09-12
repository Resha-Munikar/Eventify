<button data-drawer-target="sidebar-multi-level-sidebar" data-drawer-toggle="sidebar-multi-level-sidebar" aria-controls="sidebar-multi-level-sidebar" type="button" class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
   <span class="sr-only">Open sidebar</span>
   <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
   <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
   </svg>
</button>

<aside id="sidebar-multi-level-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
   <div class="h-full px-6 py-10 overflow-y-auto bg-gray-200 dark:bg-gray-800">
      <ul class="space-y-2 font-medium">
         <li>
            <a href="{{route('chirps.adminIndex')}}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
               <svg class="w-5 h-5 text-[#8d85ec] transition duration-75 dark:text-gray-400 group-hover:text-[#8d85ec] dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                  <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                  <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
               </svg>
               <span class="ms-3">Admin Panel</span>
            </a>
         </li>
         <li>
            <a href="{{route('chirps.user')}}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
               <svg class="shrink-0 w-5 h-5 text-[#8d85ec] transition duration-75 dark:text-gray-400 group-hover:text-[#8d85ec] dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                  <path d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z"/>
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Users</span>
            </a>
         </li>
         <!-- Review -->
         <li>
            <a href="#" 
               class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
               <svg class="shrink-0 w-5 h-5 text-[#8d85ec] transition duration-75 
                           dark:text-gray-400 group-hover:text-[#8d85ec] dark:group-hover:text-white" 
                     xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                     <path d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5z"/>
               </svg>
               <span class="ms-3 flex-1 whitespace-nowrap">Review</span>
            </a>
         </li>

         <!-- Report Dropdown -->
         <li x-data="{ open: false }" class="relative">
            <button @click="open = !open" 
                  class="flex items-center w-full p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                  <svg class="shrink-0 w-5 h-5 text-[#8d85ec] transition duration-75 
                              dark:text-gray-400 group-hover:text-[#8d85ec] dark:group-hover:text-white" 
                     xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                     <path d="M2 4h16v2H2V4zm0 5h16v2H2V9zm0 5h16v2H2v-2z"/>
                  </svg>
                  <span class="ms-3 flex-1 text-left whitespace-nowrap">Report</span>
                  <svg class="w-4 h-4 ms-auto transition-transform duration-200" 
                     :class="{'rotate-90': open}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                  </svg>
            </button>

            <!-- Dropdown items -->
            <ul x-show="open" x-transition class="mt-2 space-y-2 pl-8">
                  <li>
                     <a href="#" 
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        Event Report
                     </a>
                  </li>
                  <li>
                     <a href="#" 
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        Venue Report
                     </a>
                  </li>
            </ul>
         </li>
         <li>
            <form action="{{ route('chirps.adminLogout') }}" method="POST">
               @csrf
               <button type="submit" 
                     class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                     <svg class="shrink-0 w-5 h-5 text-[#8d85ec] transition duration-75 
                                 dark:text-gray-400 group-hover:text-[#8d85ec] dark:group-hover:text-white"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor">
                        <g transform="scale(-1,1) translate(-512,0)">
                           <path d="M497 273L329 441c-15 15-41 4.5-41-17v-96H192c-17.7 0-32-14.3-32-32V216c0-17.7 14.3-32 32-32h96v-96c0-21.5 26-32 41-17l168 168c9.4 9.4 9.4 24.6 0 34zM160 64H96c-17.7 0-32 14.3-32 32v320c0 17.7 14.3 32 32 32h64c17.7 0 32 14.3 32 32s-14.3 32-32 32H96c-53 0-96-43-96-96V96c0-53 43-96 96-96h64c17.7 0 32 14.3 32 32s-14.3 32-32 32z"/>
                        </g>
                     </svg>
                     
                     <span class="flex-1 ms-3 whitespace-nowrap">Sign Out</span>
               </button>
            </form>
         </li>

      </ul>
   </div>
</aside>
<script src="//unpkg.com/alpinejs" defer></script>
