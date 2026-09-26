<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Venue;
use App\Models\Booking;
use App\Models\VenueBooking;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class EventBotService
{
    /**
     * Process a user message and return an intelligent reply with suggestions.
     */
    public function handleMessage(string $message, ?User $user = null): array
    {
        $message = trim($message);
        if (empty($message)) {
            return [
                'reply' => "Hi there! 👋 How can I help you with Eventify today?",
                'suggestions' => $this->getDefaultSuggestions(),
            ];
        }

        // Get or initialize conversation history from session
        $history = Session::get('eventbot_history', []);

        $reply = null;
        $suggestions = [];

        // 1. Check if Gemini or OpenAI API Key is configured
        $geminiKey = config('services.gemini.api_key');
        $openaiKey = config('services.openai.api_key');

        if (!empty($geminiKey)) {
            try {
                $response = $this->callGemini($message, $history, $user, $geminiKey);
                if ($response) {
                    $reply = $response['reply'];
                    $suggestions = $response['suggestions'] ?? $this->extractSuggestions($reply, $message);
                }
            } catch (\Throwable $e) {
                Log::warning('EventBot Gemini API error, falling back to local engine: ' . $e->getMessage());
            }
        } elseif (!empty($openaiKey)) {
            try {
                $response = $this->callOpenAI($message, $history, $user, $openaiKey);
                if ($response) {
                    $reply = $response['reply'];
                    $suggestions = $response['suggestions'] ?? $this->extractSuggestions($reply, $message);
                }
            } catch (\Throwable $e) {
                Log::warning('EventBot OpenAI API error, falling back to local engine: ' . $e->getMessage());
            }
        }

        // 2. If no AI API key or call failed, use the Smart Dynamic Database & Knowledge Engine
        if (!$reply) {
            $fallbackResult = $this->smartDynamicKnowledgeEngine($message, $user);
            $reply = $fallbackResult['reply'];
            $suggestions = $fallbackResult['suggestions'];
        }

        // Update history (keep last 6 turns)
        $history[] = ['role' => 'user', 'content' => $message];
        $history[] = ['role' => 'model', 'content' => $reply];
        if (count($history) > 12) {
            $history = array_slice($history, -12);
        }
        Session::put('eventbot_history', $history);

        return [
            'reply' => $reply,
            'suggestions' => $suggestions,
        ];
    }

    /**
     * Call Google Gemini API
     */
    protected function callGemini(string $message, array $history, ?User $user, string $apiKey): ?array
    {
        $model = config('services.gemini.model', 'gemini-1.5-flash');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

        $systemPrompt = $this->buildSystemKnowledgePrompt($user);

        $contents = [];
        foreach ($history as $item) {
            $role = ($item['role'] === 'user') ? 'user' : 'model';
            $contents[] = [
                'role' => $role,
                'parts' => [['text' => $item['content']]]
            ];
        }
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $message]]
        ];

        $payload = [
            'system_instruction' => [
                'parts' => [
                    ['text' => $systemPrompt]
                ]
            ],
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => 0.6,
                'maxOutputTokens' => 800,
            ]
        ];

        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->timeout(12)
            ->post($url, $payload);

        if ($response->successful()) {
            $data = $response->json();
            $replyText = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
            if ($replyText) {
                return [
                    'reply' => $replyText,
                    'suggestions' => $this->extractSuggestions($replyText, $message),
                ];
            }
        }

        return null;
    }

    /**
     * Call OpenAI API
     */
    protected function callOpenAI(string $message, array $history, ?User $user, string $apiKey): ?array
    {
        $model = config('services.openai.model', 'gpt-4o-mini');
        $url = 'https://api.openai.com/v1/chat/completions';

        $systemPrompt = $this->buildSystemKnowledgePrompt($user);

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt]
        ];
        foreach ($history as $item) {
            $role = ($item['role'] === 'user') ? 'user' : 'assistant';
            $messages[] = ['role' => $role, 'content' => $item['content']];
        }
        $messages[] = ['role' => 'user', 'content' => $message];

        $payload = [
            'model' => $model,
            'messages' => $messages,
            'temperature' => 0.6,
            'max_tokens' => 800,
        ];

        $response = Http::withToken($apiKey)
            ->timeout(12)
            ->post($url, $payload);

        if ($response->successful()) {
            $data = $response->json();
            $replyText = $data['choices'][0]['message']['content'] ?? null;
            if ($replyText) {
                return [
                    'reply' => $replyText,
                    'suggestions' => $this->extractSuggestions($replyText, $message),
                ];
            }
        }

        return null;
    }

    /**
     * Build comprehensive system context prompt with dynamic database data.
     */
    public function buildSystemKnowledgePrompt(?User $user = null): string
    {
        $eventsData = $this->getDynamicEventsSummary();
        $venuesData = $this->getDynamicVenuesSummary();
        $userData = $this->getUserContextSummary($user);

        return <<<PROMPT
You are **EventBot AI**, the official intelligent assistant for **Eventify** — a premier event and venue management & booking platform in Nepal.

### Your Personality & Tone:
- Friendly, professional, concise, proactive, and exceptionally knowledgeable about all Eventify features and real-time database data.
- Format your responses using clean Markdown (bold headings, bullet points, numbered lists, links formatted as Markdown `[Link Text](/route)`).
- When mentioning pages, provide direct markdown links:
  - Events page: `[Browse Events](/events)`
  - Venues page: `[Explore Venues](/venues)`
  - Contact Us: `[Contact Us](/contact)`
  - About Eventify: `[About Us](/about)`
  - User Profile & Bookings: `[Profile](/profile)`
  - User Bookings: `[User Bookings](/userbooking)` or `[Event Bookings](/usereventbook)`
  - Login / Sign Up: `[Login](/login)` or `[Register](/register)`
  - Vendor Dashboard: `[Vendor Dashboard](/vendor/dashboard)`
  - Chirps Community: `[Chirps](/chirps)`

### Project Overview & Key Features:
1. **Event Booking**: Users can explore upcoming events across categories (Music, Tech, Workshop, Sports, Festivals, etc.), view event dates, venue details, pricing (in NPR), available tickets, and book tickets instantly.
2. **Venue Booking**: Users can book premier venues with base pricing, guest count calculation, customized packages, and catering menu options (per person pricing).
3. **Payment**: Seamlessly integrated with **Khalti Digital Wallet** for secure and instant verification in Nepal.
4. **Vendor Portal**: Vendors can register, list venues & events, track bookings, analyze revenue, download PDF booking reports, and manage customer reviews.
5. **Admin Portal**: Admins have complete control to manage users, monitor all event & venue bookings, generate admin PDF reports, and view platform statistics.
6. **Reviews & Ratings**: Real user reviews and star ratings for venues.
7. **Ticket & Invoice Downloads**: Automated email confirmations and downloadable PDF reports.

---
### Real-Time Live Database Knowledge:
#### Available / Upcoming Events:
{$eventsData}

#### Available Venues & Pricing:
{$venuesData}

#### Current User Status:
{$userData}
---

### Response Guidelines:
- Answer questions accurately using the real database data provided above.
- If a user asks about events, list the relevant ones with name, date, venue, category, and price in NPR.
- If a user asks about venues, provide details on location, pricing, packages, and catering options.
- If a user asks about how booking or Khalti payment works, give clear step-by-step instructions.
- If a user asks for their bookings and they are logged in, summarize their personal bookings using the Current User Status section. If not logged in, kindly invite them to `[Login](/login)`.
- Keep answers informative yet neat. Avoid unnecessary fluff.
PROMPT;
    }

    /**
     * Smart Dynamic Fallback NLP & DB Query Engine (Keyless/Offline)
     */
    public function smartDynamicKnowledgeEngine(string $message, ?User $user = null): array
    {
        $lower = strtolower($message);

        // 1. User Bookings Inquiries
        if (str_contains($lower, 'my booking') || str_contains($lower, 'my ticket') || str_contains($lower, 'my event') || str_contains($lower, 'my reservation')) {
            if (!$user) {
                return [
                    'reply' => "🔒 **Login Required**\n\nYou need to be logged in to view your personal bookings and tickets.\n\n👉 Please **[Login to your account](/login)** or **[Create a new account](/register)** to access your bookings in your **[Profile](/profile)**.",
                    'suggestions' => ['🔑 Login Now', '📅 Browse Events', '🏰 Explore Venues'],
                ];
            }

            $eventBookings = Booking::with('event')->where('user_id', $user->id)->latest()->take(5)->get();
            $venueBookings = VenueBooking::with('venue')->where('user_id', $user->id)->latest()->take(5)->get();

            $reply = "👤 **Hello {$user->name}, here are your recent bookings:**\n\n";

            if ($eventBookings->isEmpty() && $venueBookings->isEmpty()) {
                $reply .= "You don't have any active event or venue bookings yet.\n\nReady to book? Explore our **[Upcoming Events](/events)** or **[Available Venues](/venues)**!";
            } else {
                if ($eventBookings->isNotEmpty()) {
                    $reply .= "🎟️ **Event Bookings:**\n";
                    foreach ($eventBookings as $b) {
                        $eventName = $b->event->event_name ?? 'Event #' . $b->event_id;
                        $date = $b->event && $b->event->event_date ? $b->event->event_date->format('M d, Y') : 'Scheduled';
                        $reply .= "- **{$eventName}** — {$b->tickets} ticket(s) | NPR {$b->amount} | Date: {$date}\n";
                    }
                    $reply .= "\n";
                }

                if ($venueBookings->isNotEmpty()) {
                    $reply .= "🏰 **Venue Bookings:**\n";
                    foreach ($venueBookings as $vb) {
                        $venueName = $vb->venue->venue_name ?? 'Venue #' . $vb->venue_id;
                        $status = ucfirst($vb->status ?? 'Confirmed');
                        $date = $vb->event_date ?? 'N/A';
                        $reply .= "- **{$venueName}** — Date: {$date} | Guests: {$vb->guests} | NPR {$vb->total_price} ({$status})\n";
                    }
                }
                $reply .= "\nYou can view full details and tickets on your **[Profile Bookings](/profile)** page.";
            }

            return [
                'reply' => $reply,
                'suggestions' => ['📅 Browse Events', '🏰 Explore Venues', '💳 Payment Methods'],
            ];
        }

        // 2. Greetings
        if (preg_match('/^(hi|hello|hey|namaste|greetings|good\s+(morning|afternoon|evening))\b/i', $message) || $lower === 'hi' || $lower === 'hello') {
            $greeting = ($user) ? "Hello **{$user->name}**! 👋" : "Hello there! 👋";
            return [
                'reply' => "{$greeting} Welcome to **EventBot AI**!\n\nI can help you discover exciting events, find & book top venues, explain Khalti payments, check your bookings, or guide you on vendor tools.\n\nHow can I help you today?",
                'suggestions' => ['🎉 Upcoming Events', '🏰 Find Venues', '💳 How Payment Works', '👤 My Bookings'],
            ];
        }

        // 3. About Eventify Platform
        if (str_contains($lower, 'what is eventify') || str_contains($lower, 'about eventify') || str_contains($lower, 'who are you') || str_contains($lower, 'about us') || str_contains($lower, 'what does eventify do') || $lower === 'about') {
            $reply = "🌟 **About Eventify:**\n\n";
            $reply .= "**Eventify** is Nepal's all-in-one digital platform for discovering, organizing, and booking memorable events and premium venues.\n\n";
            $reply .= "✨ **What you can do:**\n";
            $reply .= "• Discover live concerts, workshops, technology summits, and cultural festivals.\n";
            $reply .= "• Book banquet halls, party palaces, and conference spaces with full catering.\n";
            $reply .= "• Pay securely with **Khalti**.\n";
            $reply .= "• Share updates and thoughts with the community in **[Chirps](/chirps)**.\n\n";
            $reply .= "Learn more on our **[About Us](/about)** page!";

            return [
                'reply' => $reply,
                'suggestions' => ['📅 Browse Events', '🏰 Find Venues', '💳 Payment Info'],
            ];
        }

        // 4. Contact & Support
        if (str_contains($lower, 'contact') || str_contains($lower, 'support') || str_contains($lower, 'help center') || str_contains($lower, 'phone number') || str_contains($lower, 'email') || str_contains($lower, 'reach out')) {
            $reply = "📞 **Contact & Support:**\n\n";
            $reply .= "We're here to assist you with any questions or issues:\n\n";
            $reply .= "• 📧 **Email**: `resa.munikar@gmail.com`\n";
            $reply .= "• 📍 **Location**: Kathmandu, Nepal\n";
            $reply .= "• 📝 **Support Form**: Fill out our quick form on the **[Contact Us](/contact)** page.\n\n";
            $reply .= "Our team typically responds within 24 hours!";

            return [
                'reply' => $reply,
                'suggestions' => ['📝 Open Contact Page', '📅 Browse Events', '🏰 Explore Venues'],
            ];
        }

        // 5. Booking Guide (Event or Venue)
        if (str_contains($lower, 'how to book') || str_contains($lower, 'how do i book') || str_contains($lower, 'how can i book') || str_contains($lower, 'booking process') || (str_contains($lower, 'book') && (str_contains($lower, 'how') || str_contains($lower, 'step') || str_contains($lower, 'guide')))) {
            $reply = "📝 **How to Book on Eventify:**\n\n";
            $reply .= "### 🎟️ For Events:\n";
            $reply .= "1. **[Sign In](/login)** to your Eventify account.\n";
            $reply .= "2. Navigate to **[Browse Events](/events)** and select your desired event.\n";
            $reply .= "3. Click **'Book Now'**, choose the number of tickets.\n";
            $reply .= "4. Complete instant payment securely via **Khalti**.\n";
            $reply .= "5. Receive immediate booking confirmation and ticket via email!\n\n";

            $reply .= "### 🏰 For Venues:\n";
            $reply .= "1. Head to **[Explore Venues](/venues)** and pick a venue.\n";
            $reply .= "2. Select your event date, number of guests, and optional catering package.\n";
            $reply .= "3. Confirm booking and proceed with payment.\n\n";
            $reply .= "You can view and manage all your tickets anytime in your **[Profile](/profile)**.";

            return [
                'reply' => $reply,
                'suggestions' => ['📅 Browse Events', '🏰 Explore Venues', '💳 Payment Methods'],
            ];
        }

        // 6. Payment & Khalti
        if (str_contains($lower, 'payment') || str_contains($lower, 'khalti') || str_contains($lower, 'how to pay') || str_contains($lower, 'refund') || (str_contains($lower, 'pay') && !str_contains($lower, 'party') && !str_contains($lower, 'palace'))) {
            $reply = "💳 **Payment Methods on Eventify:**\n\n";
            $reply .= "• **Khalti Digital Wallet**: We integrate official Khalti digital wallet payments for seamless transactions across Nepal.\n";
            $reply .= "• **Instant Verification**: Once verified through Khalti, your booking is automatically secured and an instant digital ticket is sent to your registered email.\n";
            $reply .= "• **Security**: Transactions are encrypted and processed safely.\n\n";
            $reply .= "Need help with a payment transaction? **[Contact Support](/contact)** anytime.";

            return [
                'reply' => $reply,
                'suggestions' => ['🎟️ How to Book', '📅 View Events', '📞 Contact Support'],
            ];
        }

        // 7. Vendor & Organizer Inquiries
        if (str_contains($lower, 'vendor') || str_contains($lower, 'organizer') || str_contains($lower, 'host event') || str_contains($lower, 'list venue') || str_contains($lower, 'create event')) {
            $reply = "💼 **Vendor & Organizer Hub on Eventify:**\n\n";
            $reply .= "Are you an event organizer or venue owner? Eventify gives you powerful tools:\n\n";
            $reply .= "• **[Vendor Dashboard](/vendor/dashboard)**: Real-time analytics, revenue tracking, and booking reports.\n";
            $reply .= "• **Create & Manage Venues**: List your halls, customize base prices, packages, and catering menus.\n";
            $reply .= "• **Host Events**: Publish upcoming workshops, concerts, and parties with custom ticket counts.\n";
            $reply .= "• **Download PDF Reports**: Generate official PDF invoices and attendee booking reports.\n\n";
            $reply .= "👉 To become a vendor, select the **Vendor** role during **[Sign Up](/register)** or log in to your **[Vendor Portal](/vendor/dashboard)**.";

            return [
                'reply' => $reply,
                'suggestions' => ['📝 Vendor Sign Up', '📅 View Events', '🏰 View Venues'],
            ];
        }

        // 8. Specific Venue Search or General Venues Query
        if (str_contains($lower, 'venue') || str_contains($lower, 'hall') || str_contains($lower, 'catering') || str_contains($lower, 'party palace') || str_contains($lower, 'banquet')) {
            $venues = Venue::with('reviews')->take(5)->get();

            if ($venues->isNotEmpty()) {
                $reply = "🏰 **Premier Venues Available on Eventify:**\n\n";
                foreach ($venues as $venue) {
                    $price = $venue->base_price ? "NPR " . number_format($venue->base_price) : "Contact for Price";
                    $catering = $venue->has_catering ? " | 🍽️ Catering Available (NPR {$venue->catering_price_per_person}/person)" : "";
                    $rating = $venue->reviews->count() > 0 ? " ⭐ " . round($venue->reviews->avg('rating'), 1) . "/5" : "";

                    $reply .= "• **{$venue->venue_name}**{$rating}\n";
                    $reply .= "  📍 Location: {$venue->location}\n";
                    $reply .= "  💵 Base Price: {$price}{$catering}\n";
                    if ($venue->package_price) {
                        $reply .= "  📦 Special Package: NPR " . number_format($venue->package_price) . "\n";
                    }
                    $reply .= "\n";
                }
                $reply .= "👉 Explore photos, check date availability, and reserve directly at **[Explore All Venues](/venues)**!";
            } else {
                $reply = "Discover stunning venues for weddings, conferences, and parties on our **[Venues Page](/venues)**.";
            }

            return [
                'reply' => $reply,
                'suggestions' => ['🎟️ How to Book a Venue', '📅 Upcoming Events', '💳 Payment Details'],
            ];
        }

        // 9. Specific Event Search or General Events Query
        if (str_contains($lower, 'event') || str_contains($lower, 'concert') || str_contains($lower, 'workshop') || str_contains($lower, 'festival') || preg_match('/\b(music|technology|tech|sports|conference)\b/i', $lower)) {
            // Check for specific category filter
            $categories = ['music', 'technology', 'tech', 'workshop', 'sports', 'festival', 'art', 'business', 'party'];
            $matchedCategory = null;
            foreach ($categories as $cat) {
                if (str_contains($lower, $cat)) {
                    $matchedCategory = ($cat === 'tech') ? 'technology' : $cat;
                    break;
                }
            }

            $query = Event::with('vendor');
            if ($matchedCategory) {
                $query->where('category', 'LIKE', "%{$matchedCategory}%");
            }
            $events = $query->orderBy('event_date', 'asc')->take(5)->get();

            if ($events->isNotEmpty()) {
                $reply = $matchedCategory 
                    ? "🎉 **Top " . ucfirst($matchedCategory) . " Events on Eventify:**\n\n"
                    : "🎉 **Featured & Upcoming Events on Eventify:**\n\n";

                foreach ($events as $event) {
                    $date = $event->event_date ? $event->event_date->format('D, M d, Y - h:i A') : 'Upcoming';
                    $price = $event->price > 0 ? "NPR " . number_format($event->price) : "FREE";
                    $seats = $event->available_seats !== null ? " ({$event->available_seats} seats left)" : "";
                    $reply .= "• **{$event->event_name}** ({$event->category})\n";
                    $reply .= "  📅 Date: {$date}\n";
                    $reply .= "  📍 Venue: {$event->venue}\n";
                    $reply .= "  💰 Price: {$price}{$seats}\n\n";
                }
                $reply .= "👉 View all events and book your passes at **[Browse All Events](/events)**!";
            } else {
                $reply = "We currently have exciting events coming soon! Check out the complete listing on our **[Events Page](/events)**.";
            }

            return [
                'reply' => $reply,
                'suggestions' => ['🎟️ How to Book an Event', '🏰 Find Venues', '💳 Payment Options'],
            ];
        }

        // 5. Payment & Khalti
        if (str_contains($lower, 'payment') || str_contains($lower, 'khalti') || str_contains($lower, 'pay') || str_contains($lower, 'cost') || str_contains($lower, 'refund')) {
            $reply = "💳 **Payment Methods on Eventify:**\n\n";
            $reply .= "• **Khalti Digital Wallet**: We integrate official Khalti digital wallet payments for seamless transactions across Nepal.\n";
            $reply .= "• **Instant Verification**: Once verified through Khalti, your booking is automatically secured and an instant digital ticket is sent to your registered email.\n";
            $reply .= "• **Security**: Transactions are encrypted and processed safely.\n\n";
            $reply .= "Need help with a payment transaction? **[Contact Support](/contact)** anytime.";

            return [
                'reply' => $reply,
                'suggestions' => ['🎟️ How to Book', '📅 View Events', '📞 Contact Support'],
            ];
        }

        // 6. Vendor & Organizer Inquiries
        if (str_contains($lower, 'vendor') || str_contains($lower, 'organizer') || str_contains($lower, 'host') || str_contains($lower, 'list venue') || str_contains($lower, 'create event')) {
            $reply = "💼 **Vendor & Organizer Hub on Eventify:**\n\n";
            $reply .= "Are you an event organizer or venue owner? Eventify gives you powerful tools:\n\n";
            $reply .= "• **[Vendor Dashboard](/vendor/dashboard)**: Real-time analytics, revenue tracking, and booking reports.\n";
            $reply .= "• **Create & Manage Venues**: List your halls, customize base prices, packages, and catering menus.\n";
            $reply .= "• **Host Events**: Publish upcoming workshops, concerts, and parties with custom ticket counts.\n";
            $reply .= "• **Download PDF Reports**: Generate official PDF invoices and attendee booking reports.\n\n";
            $reply .= "👉 To become a vendor, select the **Vendor** role during **[Sign Up](/register)** or log in to your **[Vendor Portal](/vendor/dashboard)**.";

            return [
                'reply' => $reply,
                'suggestions' => ['📝 Vendor Sign Up', '📅 View Events', '🏰 View Venues'],
            ];
        }

        // 7. Contact & Support
        if (str_contains($lower, 'contact') || str_contains($lower, 'support') || str_contains($lower, 'help') || str_contains($lower, 'phone') || str_contains($lower, 'email') || str_contains($lower, 'reach')) {
            $reply = "📞 **Contact & Support:**\n\n";
            $reply .= "We're here to assist you with any questions or issues:\n\n";
            $reply .= "• 📧 **Email**: `resa.munikar@gmail.com`\n";
            $reply .= "• 📍 **Location**: Kathmandu, Nepal\n";
            $reply .= "• 📝 **Support Form**: Fill out our quick form on the **[Contact Us](/contact)** page.\n\n";
            $reply .= "Our team typically responds within 24 hours!";

            return [
                'reply' => $reply,
                'suggestions' => ['📝 Open Contact Page', '📅 Browse Events', '🏰 Explore Venues'],
            ];
        }

        // 8. About Eventify
        if (str_contains($lower, 'about') || str_contains($lower, 'what is eventify') || str_contains($lower, 'who are you')) {
            $reply = "🌟 **About Eventify:**\n\n";
            $reply .= "**Eventify** is Nepal's all-in-one digital platform for discovering, organizing, and booking memorable events and premium venues.\n\n";
            $reply .= "✨ **What you can do:**\n";
            $reply .= "• Discover live concerts, workshops, technology summits, and cultural festivals.\n";
            $reply .= "• Book banquet halls, party palaces, and conference spaces with full catering.\n";
            $reply .= "• Pay securely with **Khalti**.\n";
            $reply .= "• Share updates and thoughts with the community in **[Chirps](/chirps)**.\n\n";
            $reply .= "Learn more on our **[About Us](/about)** page!";

            return [
                'reply' => $reply,
                'suggestions' => ['📅 Browse Events', '🏰 Find Venues', '💳 Payment Info'],
            ];
        }

        // 9. Greetings
        if (str_contains($lower, 'hello') || str_contains($lower, 'hi') || str_contains($lower, 'hey') || str_contains($lower, 'namaste') || str_contains($lower, 'good morning') || str_contains($lower, 'good evening')) {
            $greeting = ($user) ? "Hello **{$user->name}**! 👋" : "Hello there! 👋";
            return [
                'reply' => "{$greeting} Welcome to **EventBot AI**!\n\nI can help you discover exciting events, find & book top venues, explain Khalti payments, check your bookings, or guide you on vendor tools.\n\nHow can I help you today?",
                'suggestions' => ['🎉 Upcoming Events', '🏰 Find Venues', '💳 How Payment Works', '👤 My Bookings'],
            ];
        }

        // 10. Default Smart Response
        return [
            'reply' => "I'm **EventBot AI**, your assistant for **Eventify**!\n\nI can answer anything about:\n• 📅 **[Upcoming Events & Tickets](/events)**\n• 🏰 **[Venues, Pricing & Catering](/venues)**\n• 💳 **Khalti Payment Gateway & Verification**\n• 👤 **Your Bookings & Profile Management**\n• 💼 **Vendor Registration & Event Hosting**\n• 📞 **[Contacting Support](/contact)**\n\nWhat would you like to know?",
            'suggestions' => $this->getDefaultSuggestions(),
        ];
    }

    /**
     * Summarize Events from Database
     */
    protected function getDynamicEventsSummary(): string
    {
        try {
            $events = Event::with(['vendor', 'ticketTypes'])->orderBy('event_date', 'asc')->take(15)->get();
            if ($events->isEmpty()) {
                return "No events currently published in database.";
            }

            $lines = [];
            foreach ($events as $e) {
                $date = $e->event_date ? $e->event_date->format('Y-m-d H:i') : 'TBD';
                $vendor = $e->vendor ? " (Hosted by {$e->vendor->name})" : "";
                
                $ticketSummary = [];
                if ($e->ticketTypes && $e->ticketTypes->isNotEmpty()) {
                    foreach ($e->ticketTypes->where('status', 'active') as $tt) {
                        $ticketSummary[] = "{$tt->name}: NPR {$tt->price} ({$tt->remaining_quantity} left)";
                    }
                }
                $ticketsStr = !empty($ticketSummary) ? implode(', ', $ticketSummary) : "NPR {$e->price}";

                $lines[] = "- Event: \"{$e->event_name}\" | Category: {$e->category} | Date: {$date} | Venue: {$e->venue} | Ticket Tiers: [{$ticketsStr}] | Available Seats: {$e->available_seats}{$vendor} | Desc: {$e->description}";
            }
            return implode("\n", $lines);
        } catch (\Throwable $e) {
            return "Events data unavailable.";
        }
    }

    /**
     * Summarize Venues from Database
     */
    protected function getDynamicVenuesSummary(): string
    {
        try {
            $venues = Venue::with(['vendor', 'reviews'])->take(15)->get();
            if ($venues->isEmpty()) {
                return "No venues currently registered in database.";
            }

            $lines = [];
            foreach ($venues as $v) {
                $basePrice = $v->base_price ? "NPR {$v->base_price}" : "N/A";
                $pkg = $v->package_price ? "Package: NPR {$v->package_price} ({$v->package_details})" : "No package";
                $catering = $v->has_catering ? "Catering: NPR {$v->catering_price_per_person}/person (Menu: {$v->catering_menu})" : "No catering";
                $avgRating = $v->reviews->count() > 0 ? round($v->reviews->avg('rating'), 1) . "/5" : "No reviews yet";
                $lines[] = "- Venue: \"{$v->venue_name}\" | Location: {$v->location} | Base Price: {$basePrice} | {$pkg} | {$catering} | Rating: {$avgRating} | Desc: {$v->description}";
            }
            return implode("\n", $lines);
        } catch (\Throwable $e) {
            return "Venues data unavailable.";
        }
    }

    /**
     * Summarize User Context
     */
    protected function getUserContextSummary(?User $user): string
    {
        if (!$user) {
            return "User is currently a Guest (Not logged in).";
        }

        try {
            $eventBookings = Booking::with(['event', 'ticketType'])->where('user_id', $user->id)->take(5)->get();
            $venueBookings = VenueBooking::with('venue')->where('user_id', $user->id)->take(5)->get();

            $info = "User Name: {$user->name} | Email: {$user->email} | Role: {$user->role}\n";
            $info .= "Event Bookings count: " . $eventBookings->count() . "\n";
            foreach ($eventBookings as $b) {
                $name = $b->event->event_name ?? 'Event #' . $b->event_id;
                $tier = $b->ticketType ? " ({$b->ticketType->name} Tier)" : "";
                $info .= "  * Booked {$b->tickets} ticket(s){$tier} for '{$name}' (Amount: NPR {$b->amount})\n";
            }
            $info .= "Venue Bookings count: " . $venueBookings->count() . "\n";
            foreach ($venueBookings as $vb) {
                $name = $vb->venue->venue_name ?? 'Venue #' . $vb->venue_id;
                $info .= "  * Booked venue '{$name}' on date {$vb->event_date} for {$vb->guests} guests (Total: NPR {$vb->total_price}, Status: {$vb->status})\n";
            }
            return $info;
        } catch (\Throwable $e) {
            return "Logged in as: {$user->name} ({$user->email}), Role: {$user->role}";
        }
    }

    /**
     * Default starter suggestions
     */
    public function getDefaultSuggestions(): array
    {
        return [
            '🎉 Upcoming Events',
            '🏰 Find Venues',
            '💳 How Payment Works',
            '🎟️ How to Book',
            '👤 My Bookings',
            '💼 Vendor Guide',
        ];
    }

    /**
     * Extract contextual suggestions based on reply content
     */
    protected function extractSuggestions(string $reply, string $userMessage): array
    {
        $lower = strtolower($reply . ' ' . $userMessage);

        if (str_contains($lower, 'event')) {
            return ['🎟️ How to Book an Event', '🏰 Explore Venues', '💳 Payment Methods'];
        }
        if (str_contains($lower, 'venue')) {
            return ['🍽️ Catering Options', '📅 Upcoming Events', '🎟️ How to Book a Venue'];
        }
        if (str_contains($lower, 'payment') || str_contains($lower, 'khalti')) {
            return ['🎟️ Book an Event Now', '🏰 Find Venues', '📞 Contact Support'];
        }
        if (str_contains($lower, 'vendor')) {
            return ['💼 Vendor Dashboard', '📅 Create Event', '🏰 Add Venue'];
        }

        return $this->getDefaultSuggestions();
    }
}
