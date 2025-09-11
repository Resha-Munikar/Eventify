<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Event Management Landing Page</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Flowbite CSS -->
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css"
      rel="stylesheet"
    />
    
    <style>
      .brand-color {
        background-color: #8d85ec;
      }
      .brand-text {
        color: #8d85ec;
      }
     /* Hide scrollbar for all modern browsers */
.scrollbar-hide::-webkit-scrollbar {
  display: none; /* Chrome, Safari, Edge */
}
.scrollbar-hide {
  -ms-overflow-style: none; /* IE 10+ */
  scrollbar-width: none; /* Firefox */
}
    </style>
  </head>
  <body class="font-sans text-gray-800 bg-gray-50">

<!-- Navbar -->
<header class="w-full bg-[#8D85EC] shadow-md">
  <div class="max-w-7xl mx-auto flex justify-between items-center px-8 py-4">
    <!-- Logo + Title -->
    <div class="flex items-center space-x-4">
      <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center overflow-hidden">
        <img src="uploads/eventicon.png" alt="E Icon" class="w-8 h-8 object-contain" />
      </div>
      <span class="text-white font-semibold italic text-2xl font-serif">Eventify</span>
    </div>

   <nav class="hidden md:flex bg-white rounded-full px-8 py-3 shadow-md space-x-10 w-1/2 justify-center">
  <a href="#" class="text-[#8D85EC] font-semibold hover:underline">Home</a>
  <a href="#" class="text-gray-700 hover:text-[#8D85EC]">Services</a>
  <a href="#" class="text-gray-700 hover:text-[#8D85EC]">Events</a>
  <a href="#" class="text-gray-700 hover:text-[#8D85EC]">Contact</a>
</nav>

    <!-- Buttons -->
    <div class="hidden md:flex items-center space-x-4">
      <a href="#" class="bg-white text-[#8D85EC] font-semibold px-5 py-2 rounded-full hover:bg-gray-100 transition">Login</a>
      <a href="#" class="bg-[#7b76e4] text-white font-semibold px-5 py-2 rounded-full hover:bg-[#6f69d9] transition">Sign Up</a>
    </div>
  </div>
</header>

<!-- Hero Section -->
<section class="relative bg-gray-50 flex flex-col md:flex-row items-center justify-between px-6 md:px-12 py-20 max-w-7xl mx-auto">
  <!-- Left Content -->
  <div class="max-w-lg text-center md:text-left mb-10 md:mb-0">
    <h1 class="text-4xl md:text-6xl font-bold leading-tight mb-6">
      Organize Memorable <br />
      <span class="text-[#8D85EC]">Events with Ease</span>
    </h1>
    <p class="text-lg text-gray-600 mb-8">
      From weddings to corporate events, we provide end-to-end event management solutions tailored to your needs.
    </p>
    <a href="#" class="bg-[#8D85EC] text-white px-6 py-3 rounded-lg text-lg font-semibold hover:opacity-90 transition">Get Started</a>
  </div>

  <!-- Right Shapes -->
  <div class="w-full md:w-1/2 flex justify-center items-center">
    <div class="relative">
      <div class="w-64 h-80 rounded-t-full overflow-hidden shadow-lg">
        <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80" alt="Arch Image" class="w-full h-full object-cover" />
      </div>
      <div class="absolute -bottom-6 -left-6 w-28 h-28 rounded-full overflow-hidden border-4 border-white shadow-lg z-10">
        <img src="uploads/circle.avif" alt="Circle Image" class="w-full h-full object-cover" />
      </div>
    </div>
  </div>
</section>

<!-- Features -->
<section class="py-16 px-4  max-w-7xl mx-auto">
  <h2 class="text-3xl font-bold mb-12 text-center">Our Services</h2>
  <div class="grid md:grid-cols-3 gap-8 text-center">
    <div class="p-8 bg-white rounded-2xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2">
      <div class="w-16 h-16 mx-auto mb-6 flex items-center justify-center rounded-full bg-[#8D85EC]/10 text-[#8D85EC] text-3xl shadow-md">🎉</div>
      <h3 class="text-xl font-bold mb-3">Event Planning</h3>
      <p class="text-gray-600">Complete planning services for weddings, birthdays, and corporate events.</p>
    </div>
    <div class="p-8 bg-white rounded-2xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2">
      <div class="w-16 h-16 mx-auto mb-6 flex items-center justify-center rounded-full bg-[#8D85EC]/10 text-[#8D85EC] text-3xl shadow-md">🍽</div>
      <h3 class="text-xl font-bold mb-3">Catering & Decor</h3>
      <p class="text-gray-600">Beautiful decor themes and catering customized for your event.</p>
    </div>
    <div class="p-8 bg-white rounded-2xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2">
      <div class="w-16 h-16 mx-auto mb-6 flex items-center justify-center rounded-full bg-[#8D85EC]/10 text-[#8D85EC] text-3xl shadow-md">🎶</div>
      <h3 class="text-xl font-bold mb-3">Entertainment</h3>
      <p class="text-gray-600">Live music, DJs, and entertainment tailored to your audience.</p>
    </div>
  </div>
</section>
<!-- Upcoming Events Section -->
<section class="py-16 px-4 bg-gray-50 max-w-7xl mx-auto relative">
  <h2 class="text-3xl font-bold mb-12 text-center text-gray-800">Upcoming Events</h2>

  <!-- Wrapper for arrows and scrollable container -->
  <div class="relative">
    <!-- Scrollable container -->
    <div id="upcoming-container" class="flex space-x-6 overflow-x-auto scrollbar-hide snap-x snap-mandatory py-4">
      <!-- Card 1 -->
      <div class="flex-shrink-0 w-[calc(33.333%-16px)] bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 snap-start">
        <a href="#">
          <img class="rounded-t-lg w-full h-48 object-cover" src="uploads/upevent.jpg" alt="Gala Dinner" />
        </a>
        <div class="p-5">
          <a href="#"><h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Gala Dinner</h5></a>
          <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">An elegant evening with fine dining, entertainment, and networking opportunities.</p>
          <a href="#" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-[#8d85ec] rounded-lg hover:bg-[#746fd6] transition">
            Read more
            <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
            </svg>
          </a>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="flex-shrink-0 w-[calc(33.333%-16px)] bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 snap-start">
        <a href="#">
          <img class="rounded-t-lg w-full h-48 object-cover" src="uploads/upevent2.jpg" alt="Wedding" />
        </a>
        <div class="p-5">
          <a href="#"><h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Wedding</h5></a>
          <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Join us in celebrating love and togetherness at a beautiful wedding ceremony and reception.</p>
          <a href="#" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-[#8d85ec] rounded-lg hover:bg-[#746fd6] transition">
            Read more
            <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
            </svg>
          </a>
        </div>
      </div>

      <!-- Card 3 -->
      <div class="flex-shrink-0 w-[calc(33.333%-16px)] bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 snap-start">
        <a href="#">
          <img class="rounded-t-lg w-full h-48 object-cover" src="uploads/birthday.jpg" alt="Birthday Party" />
        </a>
        <div class="p-5">
          <a href="#"><h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Birthday Party</h5></a>
          <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Celebrate a special birthday with fun, laughter, and unforgettable memories with friends and family.</p>
          <a href="#" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-[#8d85ec] rounded-lg hover:bg-[#746fd6] transition">
            Read more
            <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
            </svg>
          </a>
        </div>
      </div>

      <!-- Card 4 -->
      <div class="flex-shrink-0 w-[calc(33.333%-16px)] bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 snap-start">
        <a href="#">
          <img class="rounded-t-lg w-full h-48 object-cover" src="uploads/concert.jpg" alt="Concert" />
        </a>
        <div class="p-5">
          <a href="#"><h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Concert</h5></a>
          <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Experience live music like never before with amazing performances from top artists.</p>
          <a href="#" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-[#8d85ec] rounded-lg hover:bg-[#746fd6] transition">
            Read more
            <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
            </svg>
          </a>
        </div>
      </div>
    </div>

    <!-- Left Arrow -->
    <button id="up-left-arrow" class="absolute top-1/2 left-0 transform -translate-y-1/2 -translate-x-8 bg-white text-[#8d85ec] rounded-full shadow-lg p-2 hover:bg-gray-100 transition">&lt;</button>

    <!-- Right Arrow -->
    <button id="up-right-arrow" class="absolute top-1/2 right-0 transform -translate-y-1/2 translate-x-8 bg-white text-[#8d85ec] rounded-full shadow-lg p-2 hover:bg-gray-100 transition">&gt;</button>
  </div>
</section>

<!-- Scrollbar hide CSS -->
<style>
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<!-- JS for scrolling -->
<script>
const upcomingContainer = document.getElementById('upcoming-container');
const leftArrow = document.getElementById('up-left-arrow');
const rightArrow = document.getElementById('up-right-arrow');

const cardWidth = upcomingContainer.querySelector('div').offsetWidth + 24; // width + gap

leftArrow.addEventListener('click', () => {
  upcomingContainer.scrollBy({ left: -cardWidth, behavior: 'smooth' });
});

rightArrow.addEventListener('click', () => {
  upcomingContainer.scrollBy({ left: cardWidth, behavior: 'smooth' });
});
</script>



<!-- Why Choose Us -->
<section class="relative py-20 px-4 bg-gradient-to-r from-purple-50 via-white to-purple-50 max-w-7xl mx-auto rounded-xl shadow-lg">
  <div class="grid md:grid-cols-2 gap-12 items-center">
    <div>
      <h2 class="text-3xl font-bold mb-4 relative inline-block text-gray-900">
        Why Choose Us
        <span class="absolute left-0 -bottom-1 w-20 h-1 bg-[#8D85EC] rounded-full"></span>
      </h2>
      <p class="text-gray-700 mb-8">Our experienced team ensures every event is stress-free and memorable, offering customized services to fit your vision and budget.</p>
      <a href="#" class="bg-[#8D85EC] text-white px-6 py-3 rounded-lg font-semibold hover:opacity-90 transition shadow-md hover:shadow-lg transform hover:-translate-y-1">Learn More</a>
    </div>
    <img src="uploads/event.jpg" alt="event planning" class="rounded-lg shadow-xl transform hover:scale-105 transition duration-500" />
  </div>
</section>


<!-- Events Organized -->
<section class="py-16 px-4 max-w-7xl mx-auto">
  <h2 class="text-3xl font-bold mb-12 text-center">Events We Have Organized</h2>
  <div class="space-y-16">
    <!-- Card 1 -->
    <div class="grid md:grid-cols-2 gap-8 items-center">
      <div class="overflow-hidden rounded-xl shadow-lg">
        <img class="w-full h-72 object-cover transform transition duration-500 hover:scale-110" src="uploads/wedding.jpg" alt="Wedding Event" />
      </div>
      <div>
        <h3 class="text-xl font-bold mb-3">Dream Weddings</h3>
        <p class="text-gray-600 mb-6">Elegant wedding ceremonies with beautiful decor, catering, and entertainment tailored to create lifelong memories.</p>
        <a href="#" class="inline-block px-6 py-2 bg-[#8D85EC] text-white rounded-lg shadow hover:opacity-90">Explore</a>
      </div>
    </div>
    <!-- Card 2 -->
    <div class="grid md:grid-cols-2 gap-8 items-center md:[&>*:first-child]:order-2">
      <div class="overflow-hidden rounded-xl shadow-lg">
        <img class="w-full h-72 object-cover transform transition duration-500 hover:scale-110" src="https://images.unsplash.com/photo-1507874457470-272b3c8d8ee2?auto=format&fit=crop&w=800&q=80" alt="Concert Event" />
      </div>
      <div>
        <h3 class="text-xl font-bold mb-3">Live Concerts</h3>
        <p class="text-gray-600 mb-6">High-energy concerts and music festivals, complete with stage design, sound systems, and crowd management.</p>
        <a href="#" class="inline-block px-6 py-2 bg-[#8D85EC] text-white rounded-lg shadow hover:opacity-90">Explore</a>
      </div>
    </div>
    <!-- Card 3 -->
    <div class="grid md:grid-cols-2 gap-8 items-center">
      <div class="overflow-hidden rounded-xl shadow-lg">
        <img class="w-full h-72 object-cover transform transition duration-500 hover:scale-110" src="uploads/corporate.jpg" alt="Corporate Event" />
      </div>
      <div>
        <h3 class="text-xl font-bold mb-3">Corporate Events</h3>
        <p class="text-gray-600 mb-6">Seamless corporate events, including conferences, product launches, and gala dinners, designed for maximum impact.</p>
        <a href="#" class="inline-block px-6 py-2 bg-[#8D85EC] text-white rounded-lg shadow hover:opacity-90">Explore</a>
      </div>
    </div>
  </div>
</section>
<!-- Testimonials -->
<section class="py-16 px-4 bg-gray-50 max-w-7xl mx-auto relative">
  <h2 class="text-3xl font-bold mb-12 text-center text-gray-800">Happy Clients</h2>

  <!-- Wrapper for arrows and scrollable container -->
  <div class="relative">
    <!-- Scrollable container -->
    <div id="testimonial-container" class="flex space-x-6 overflow-x-auto scrollbar-hide snap-x snap-mandatory py-4">
      <!-- Card 1 -->
      <div class="flex-shrink-0 w-[calc(33.333%-16px)] p-6 bg-[#8D85EC] text-white rounded-xl shadow-lg flex flex-col items-center text-center transform transition duration-300 hover:scale-105 hover:bg-[#9a8ff0] snap-start">
        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Client Photo" class="w-20 h-20 rounded-full object-cover shadow-md mb-4 border-2 border-white">
        <p class="italic mb-4">"They made our wedding day absolutely magical!"</p>
        <h4 class="font-semibold">Aarav & Priya</h4>
      </div>

      <!-- Card 2 -->
      <div class="flex-shrink-0 w-[calc(33.333%-16px)] p-6 bg-[#8D85EC] text-white rounded-xl shadow-lg flex flex-col items-center text-center transform transition duration-300 hover:scale-105 hover:bg-[#9a8ff0] snap-start">
        <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Client Photo" class="w-20 h-20 rounded-full object-cover shadow-md mb-4 border-2 border-white">
        <p class="italic mb-4">"Our corporate gala was seamless, thanks to their team."</p>
        <h4 class="font-semibold">TechCorp Ltd.</h4>
      </div>

      <!-- Card 3 -->
      <div class="flex-shrink-0 w-[calc(33.333%-16px)] p-6 bg-[#8D85EC] text-white rounded-xl shadow-lg flex flex-col items-center text-center transform transition duration-300 hover:scale-105 hover:bg-[#9a8ff0] snap-start">
        <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Client Photo" class="w-20 h-20 rounded-full object-cover shadow-md mb-4 border-2 border-white">
        <p class="italic mb-4">"Best event planners! Stress-free and professional."</p>
        <h4 class="font-semibold">Maya Sharma</h4>
      </div>

      <!-- Card 4 -->
      <div class="flex-shrink-0 w-[calc(33.333%-16px)] p-6 bg-[#8D85EC] text-white rounded-xl shadow-lg flex flex-col items-center text-center transform transition duration-300 hover:scale-105 hover:bg-[#9a8ff0] snap-start">
        <img src="https://randomuser.me/api/portraits/men/45.jpg" alt="Client Photo" class="w-20 h-20 rounded-full object-cover shadow-md mb-4 border-2 border-white">
        <p class="italic mb-4">"Absolutely loved their attention to detail!"</p>
        <h4 class="font-semibold">Rohan & Simran</h4>
      </div>

      <!-- Card 5 -->
      <div class="flex-shrink-0 w-[calc(33.333%-16px)] p-6 bg-[#8D85EC] text-white rounded-xl shadow-lg flex flex-col items-center text-center transform transition duration-300 hover:scale-105 hover:bg-[#9a8ff0] snap-start">
        <img src="https://randomuser.me/api/portraits/women/50.jpg" alt="Client Photo" class="w-20 h-20 rounded-full object-cover shadow-md mb-4 border-2 border-white">
        <p class="italic mb-4">"Professional, creative, and reliable team!"</p>
        <h4 class="font-semibold">Sita & Ram</h4>
      </div>
    </div>

    <!-- Left Arrow -->
    <button id="left-arrow" class="absolute top-1/2 left-0 transform -translate-y-1/2 -translate-x-8 bg-white text-[#8D85EC] rounded-full shadow-lg p-2 hover:bg-gray-100 transition">
      &lt;
    </button>

    <!-- Right Arrow -->
    <button id="right-arrow" class="absolute top-1/2 right-0 transform -translate-y-1/2 translate-x-8 bg-white text-[#8D85EC] rounded-full shadow-lg p-2 hover:bg-gray-100 transition">
      &gt;
    </button>
  </div>
</section>

<!-- Script for arrow scrolling -->
<script>
  const container = document.getElementById('testimonial-container');
  const leftArrow = document.getElementById('left-arrow');
  const rightArrow = document.getElementById('right-arrow');

  const scrollAmount = container.offsetWidth; // scroll by visible container width (3 cards)

  leftArrow.addEventListener('click', () => {
    container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
  });

  rightArrow.addEventListener('click', () => {
    container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
  });
</script>

<!-- Hide scrollbar CSS -->
<style>
  .scrollbar-hide::-webkit-scrollbar { display: none; }
  .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>


<script>
  const container = document.getElementById('testimonial-container');
  const leftArrow = document.getElementById('left-arrow');
  const rightArrow = document.getElementById('right-arrow');

  const scrollAmount = container.offsetWidth/4; // scroll by container width (3 cards visible)

  leftArrow.addEventListener('click', () => {
    container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
  });

  rightArrow.addEventListener('click', () => {
    container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
  });
</script>

</div>
  

<!-- Footer -->
<footer class="bg-gray-900 text-gray-300 py-16 px-4">
  <div class="max-w-7xl mx-auto grid md:grid-cols-4 gap-8 text-left">
    <!-- Logo & About -->
    <div>
      <h2 class="text-2xl font-bold text-white mb-4">Eventify</h2>
      <p class="text-gray-400 mb-4">
        Eventify is your trusted partner for creating unforgettable weddings, corporate events, and live concerts with personalized planning, catering, and entertainment.
      </p>
      <div class="flex gap-4 mt-4">
        <a href="#" class="hover:text-[#8D85EC]"><i class="fab fa-facebook-f"></i>Facebook</a>
        <a href="#" class="hover:text-[#8D85EC]"><i class="fab fa-instagram"></i>Instagram</a>
        <a href="#" class="hover:text-[#8D85EC]"><i class="fab fa-linkedin-in"></i>LinkedIn</a>
      </div>
    </div>

    <!-- Quick Links -->
    <div>
      <h3 class="text-xl font-semibold text-white mb-4">Quick Links</h3>
      <ul class="space-y-2">
        <li><a href="#" class="hover:text-[#8D85EC]">Home</a></li>
        <li><a href="#" class="hover:text-[#8D85EC]">Services</a></li>
        <li><a href="#" class="hover:text-[#8D85EC]">Events</a></li>
        <li><a href="#" class="hover:text-[#8D85EC]">Contact</a></li>
      </ul>
    </div>

    <!-- Services -->
    <div>
      <h3 class="text-xl font-semibold text-white mb-4">Our Services</h3>
      <ul class="space-y-2">
        <li><a href="#" class="hover:text-[#8D85EC]">Event Planning</a></li>
        <li><a href="#" class="hover:text-[#8D85EC]">Catering & Decor</a></li>
        <li><a href="#" class="hover:text-[#8D85EC]">Entertainment</a></li>
        <li><a href="#" class="hover:text-[#8D85EC]">Corporate Events</a></li>
      </ul>
    </div>

    <!-- Contact & Newsletter -->
    <div>
      <h3 class="text-xl font-semibold text-white mb-4">Contact Us</h3>
      <p class="text-gray-400 mb-2">123 Event Street, Kathmandu, Nepal</p>
      <p class="text-gray-400 mb-2">Email: info@eventify.com</p>
      <p class="text-gray-400 mb-4">Phone: +977 9812345678</p>
      <h3 class="text-xl font-semibold text-white mb-2">Subscribe</h3>
      <form class="flex flex-col sm:flex-row gap-2">
        <input type="email" placeholder="Your email" class="px-4 py-2 rounded-md text-gray-900 focus:outline-none flex-1" />
        <button type="submit" class="bg-[#8D85EC] text-white px-4 py-2 rounded-md hover:opacity-90 transition">Subscribe</button>
      </form>
    </div>
  </div>

  <!-- Bottom Footer -->
  <div class="mt-12 border-t border-gray-700 pt-6 text-center text-gray-500 text-sm">
    © 2025 Eventify. All rights reserved. Designed with ❤️ by Eventify Team.
  </div>
</footer>


<!-- Flowbite JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>
</html>
