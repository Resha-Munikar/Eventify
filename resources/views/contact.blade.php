@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-r from-[#8D85EC]/90 to-[#d9d4f7]/90 text-white py-20 px-6">
  <div class="max-w-4xl mx-auto text-center">
    <h1 class="text-5xl font-bold mb-4">Contact Eventify</h1>
    <p class="text-lg text-gray-100">We’d love to hear from you! Whether you have a question about our services, pricing, or anything else, our team is ready to help.</p>
  </div>
</section>

<!-- Contact Section -->
<section class="py-20 px-6 bg-gray-50">
  <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-12">
    
    <!-- Contact Form -->
    <div class="bg-white p-8 rounded-2xl shadow-lg">
      <h2 class="text-3xl font-semibold text-[#8D85EC] mb-6">Send us a Message</h2>
      <form action="{{ route('contact') }}" method="POST" class="space-y-5">
        @csrf
        <div>
          <label class="block text-gray-700 font-medium mb-2">Full Name</label>
          <input type="text" name="name" required
            class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#8D85EC]">
        </div>
        <div>
          <label class="block text-gray-700 font-medium mb-2">Email Address</label>
          <input type="email" name="email" required
            class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#8D85EC]">
        </div>
        <div>
          <label class="block text-gray-700 font-medium mb-2">Subject</label>
          <input type="text" name="subject" required
            class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#8D85EC]">
        </div>
        <div>
          <label class="block text-gray-700 font-medium mb-2">Message</label>
          <textarea name="message" rows="5" required
            class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#8D85EC]"></textarea>
        </div>
        <button type="submit"
          class="w-full bg-[#8D85EC] text-white font-semibold px-6 py-3 rounded-lg shadow-md hover:bg-[#7a73d9] hover:shadow-xl hover:-translate-y-1 transition-all duration-300 transform">
          Send Message
        </button>
      </form>
    </div>

    <!-- Contact Information -->
    <div class="flex flex-col justify-center space-y-8">
      <div>
        <h3 class="text-2xl font-semibold text-[#8D85EC] mb-2">Our Office</h3>
        <p class="text-gray-700">123 Eventify Street,<br> Kathmandu, Nepal</p>
      </div>
      <div>
        <h3 class="text-2xl font-semibold text-[#8D85EC] mb-2">Call Us</h3>
        <p class="text-gray-700">+977 9800000000</p>
      </div>
      <div>
        <h3 class="text-2xl font-semibold text-[#8D85EC] mb-2">Email</h3>
        <p class="text-gray-700">support@eventify.com</p>
      </div>
    </div>
  </div>
</section>
@endsection
