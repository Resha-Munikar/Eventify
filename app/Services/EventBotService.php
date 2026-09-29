<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Booking;
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
        $userData = $this->getUserContextSummary($user);

        return <<<PROMPT
You are **EventBot AI**, the official intelligent assistant for **Eventify** — Nepal's premier digital event discovery, ticketing, and management platform.

### Your Personality & Tone:
- Friendly, helpful, professional, concise, proactive, and exceptionally knowledgeable about all Eventify features, event listings, ticket bookings, Khalti payments, vendor tools, and account management.
- Format your responses using clean, structured Markdown (bold headings, bullet points, numbered steps, and markdown links `[Link Text](/route)`).
- When mentioning pages and actions, provide direct markdown links:
  - Events directory: `[Browse Events](/events)`
  - User Bookings & Tickets: `[My Tickets](/usereventbook)` or `[Attendee Dashboard](/customer/dashboard)`
  - Profile & Account: `[My Profile](/profile)`
  - Login & Register: `[Login](/login)` or `[Sign Up](/register)`
  - Forgot Password: `[Forgot Password](/forgot-password)` (or Vendor Forgot Password: `[Vendor Forgot Password](/vendor/forgot-password)`)
  - Vendor Portal: `[Vendor Dashboard](/vendor/dashboard)`
  - Vendor KYC Verification: `[Vendor KYC](/vendor/kyc)`
  - Create Event (for Vendors): `[Create Event](/vendor/events/create)`
  - Community Board: `[Chirps Community](/chirps)`
  - Contact & Support: `[Contact Us](/contact)`
  - About Eventify: `[About Us](/about)`

### Project Overview & Key Features of Eventify:
1. **Event Discovery**: Attendees can discover upcoming events across a wide range of categories including Music & Concerts, Technology & Hackathons, Educational Workshops, Sports Tournaments, Cultural Festivals, Arts & Exhibitions, Business Conferences, and Parties.
2. **Multi-Tier Ticket Booking**: Events feature flexible ticket tiers (VIP, Standard, Early Bird, General Admission) with real-time seat availability and transparent pricing in NPR.
3. **Instant Khalti Payment Integration**: Secure digital payments powered by Khalti Digital Wallet with instant verification and automatic digital ticket generation.
4. **Attendee Dashboard & Ticket Management**: Users can view all active and past ticket bookings, download tickets, check transaction statuses, and manage their personal profile and photo.
5. **Vendor & Organizer Hub**:
   - Register as an organizer/vendor to host and manage events.
   - **KYC Verification**: Vendors submit citizenship/PAN/business documentation for admin review to ensure secure and trustworthy event hosting.
   - **Event Creation & Management**: Publish events with custom banners, locations, dates, descriptions, ticket types, seat capacity, and prices.
   - **Real-Time Analytics & PDF Reports**: Track live ticket sales, view attendee rosters, and download official PDF booking reports.
6. **Admin Management**: Admins review vendor KYC submissions, monitor platform-wide events and bookings, manage users, and ensure platform integrity.
7. **Chirps Community**: A social micro-post board where users and organizers can discuss upcoming events, share photos, ask questions, and engage with the community.
8. **Account Security**: Profile customization with photo upload/removal, secure email OTP verification, password updates, and automated password recovery links.

---
### Real-Time Live Database Knowledge:
#### Available / Upcoming Events:
{$eventsData}

#### Current User Status:
{$userData}
---

### Response Guidelines:
- Answer questions accurately using the real database data provided above.
- When users ask about events, provide specific event names, categories, dates, locations, ticket tier pricing (in NPR), and remaining seats.
- If a user asks how to book or how Khalti payment works, provide clean, step-by-step instructions.
- If a logged-in user asks for their bookings or tickets, summarize their bookings from the Current User Status section. If not logged in, invite them to `[Login](/login)`.
- If a user asks about hosting an event or becoming a vendor, guide them through vendor registration, KYC submission, and event creation tools.
- Never mention venue booking or venue rental features as Eventify focuses exclusively on events, ticketing, and organizer management.
- Keep answers neat, engaging, and directly actionable.
PROMPT;
    }

    /**
     * Smart Dynamic Fallback NLP & DB Query Engine (Keyless/Offline)
     */
    public function smartDynamicKnowledgeEngine(string $message, ?User $user = null): array
    {
        $lower = strtolower($message);

        // 1. User Bookings & Tickets Inquiries
        if (str_contains($lower, 'my booking') || str_contains($lower, 'my ticket') || str_contains($lower, 'my event') || str_contains($lower, 'my reservation') || str_contains($lower, 'purchased ticket') || str_contains($lower, 'check my booking')) {
            if (!$user) {
                return [
                    'reply' => "🔒 **Login Required to View Bookings**\n\nYou need to be logged in to view your personal event bookings and digital tickets.\n\n👉 Please **[Login to your account](/login)** or **[Create a new account](/register)** to access your tickets at **[My Tickets](/usereventbook)** or your **[Profile](/profile)**.",
                    'suggestions' => ['🔑 Login Now', '🎉 Browse Events', '💳 Payment Info'],
                ];
            }

            $bookings = Booking::with(['event', 'ticketType'])->where('user_id', $user->id)->latest()->take(5)->get();

            $reply = "👤 **Hello {$user->name}, here are your recent event bookings:**\n\n";

            if ($bookings->isEmpty()) {
                $reply .= "You don't have any active event ticket bookings yet.\n\nReady to discover exciting experiences? Explore our **[Upcoming Events](/events)** and secure your tickets!";
            } else {
                $reply .= "🎟️ **Your Active & Past Bookings:**\n";
                foreach ($bookings as $b) {
                    $eventName = $b->event->event_name ?? 'Event #' . $b->event_id;
                    $date = $b->event && $b->event->event_date ? $b->event->event_date->format('M d, Y - h:i A') : 'Scheduled';
                    $tier = $b->ticketType ? " ({$b->ticketType->name} Tier)" : "";
                    $location = $b->event->venue ?? 'Announced on ticket';
                    $reply .= "• **{$eventName}**{$tier}\n";
                    $reply .= "  📅 Date: {$date} | 📍 Location: {$location}\n";
                    $reply .= "  🎫 Tickets: {$b->tickets} | 💰 Amount: NPR " . number_format($b->amount) . "\n\n";
                }
                $reply .= "👉 You can view and manage all your tickets anytime at **[My Tickets](/usereventbook)** or your **[Attendee Dashboard](/customer/dashboard)**.";
            }

            return [
                'reply' => $reply,
                'suggestions' => ['🎉 Browse Events', '💳 Payment Methods', '👤 My Profile'],
            ];
        }

        // 2. Greetings
        if (preg_match('/^(hi|hello|hey|namaste|greetings|good\s+(morning|afternoon|evening))\b/i', $message) || $lower === 'hi' || $lower === 'hello' || $lower === 'hey' || $lower === 'namaste') {
            $greeting = ($user) ? "Hello **{$user->name}**! 👋" : "Hello there! 👋";
            return [
                'reply' => "{$greeting} Welcome to **EventBot AI**!\n\nI can assist you with discovering upcoming events, booking tickets, explaining Khalti payments, vendor event hosting, KYC verification, and attendee dashboards across **Eventify**.\n\nHow can I help you today?",
                'suggestions' => ['🎉 Upcoming Events', '🎟️ How to Book', '💳 Khalti Payments', '💼 Host an Event'],
            ];
        }

        // 3. About Eventify Platform / What is Eventify
        if (str_contains($lower, 'about eventify') || str_contains($lower, 'what is eventify') || str_contains($lower, 'who are you') || str_contains($lower, 'what does eventify do') || str_contains($lower, 'about us') || $lower === 'about' || $lower === 'eventify') {
            $reply = "🌟 **About Eventify:**\n\n";
            $reply .= "**Eventify** is Nepal's premier all-in-one digital platform for discovering, organizing, and booking memorable events.\n\n";
            $reply .= "✨ **Key Highlights:**\n";
            $reply .= "• **Event Discovery**: Find live concerts, tech hackathons, workshops, sports, and cultural festivals.\n";
            $reply .= "• **Instant Ticketing**: Multi-tier passes (VIP, Standard, Early Bird) with real-time seat availability.\n";
            $reply .= "• **Khalti Payments**: Seamless and secure digital wallet transactions across Nepal.\n";
            $reply .= "• **Organizer Suite**: End-to-end event hosting, KYC verification, ticket analytics, and PDF reports.\n";
            $reply .= "• **Community**: Connect with attendees on **[Chirps](/chirps)**.\n\n";
            $reply .= "Learn more on our **[About Us](/about)** page!";

            return [
                'reply' => $reply,
                'suggestions' => ['🎉 Browse Events', '🎟️ How to Book', '💼 Host an Event'],
            ];
        }

        // 4. Contact & Support
        if (str_contains($lower, 'contact') || str_contains($lower, 'support') || str_contains($lower, 'help center') || str_contains($lower, 'phone') || str_contains($lower, 'email') || str_contains($lower, 'reach out') || str_contains($lower, 'customer service')) {
            $reply = "📞 **Contact & Support:**\n\n";
            $reply .= "We're here to assist you with any questions, ticketing issues, or organizer inquiries:\n\n";
            $reply .= "• 📧 **Email**: `resa.munikar@gmail.com`\n";
            $reply .= "• 📍 **Location**: Kathmandu, Nepal\n";
            $reply .= "• 📝 **Support Form**: Submit an inquiry on the **[Contact Us](/contact)** page.\n\n";
            $reply .= "Our support team typically responds within 24 hours!";

            return [
                'reply' => $reply,
                'suggestions' => ['📝 Open Contact Page', '🎉 Browse Events', '🎟️ How to Book'],
            ];
        }

        // 5. Account, Profile, Password Reset & Login
        if (str_contains($lower, 'password') || str_contains($lower, 'forgot password') || str_contains($lower, 'reset password') || str_contains($lower, 'change password') || str_contains($lower, 'profile') || str_contains($lower, 'photo') || str_contains($lower, 'avatar') || str_contains($lower, 'login') || str_contains($lower, 'register') || str_contains($lower, 'sign up') || str_contains($lower, 'account')) {
            $reply = "👤 **Account & Profile Management on Eventify:**\n\n";
            $reply .= "• **Profile Customization**: Update your name, email, contact info, and manage your avatar photo at **[My Profile](/profile)**.\n";
            $reply .= "• **Password Reset**: Forgot your password? Request an automated recovery link from **[Forgot Password](/forgot-password)** (or **[Vendor Forgot Password](/vendor/forgot-password)** for organizers).\n";
            $reply .= "• **Show/Hide Password**: Use the interactive eye toggle on login, register, and reset password forms for easy typing.\n";
            $reply .= "• **Attendee Portal**: Access all your tickets at **[My Tickets](/usereventbook)**.\n\n";
            $reply .= "Need help accessing your account? Reach out to **[Customer Support](/contact)**.";

            return [
                'reply' => $reply,
                'suggestions' => ['👤 My Profile', '🔑 Login Page', '📞 Contact Support'],
            ];
        }

        // 6. How to Book Tickets / Booking Process
        if (str_contains($lower, 'how to book') || str_contains($lower, 'how do i book') || str_contains($lower, 'how can i book') || str_contains($lower, 'booking process') || str_contains($lower, 'buy ticket') || str_contains($lower, 'purchase ticket') || (str_contains($lower, 'book') && (str_contains($lower, 'step') || str_contains($lower, 'guide') || str_contains($lower, 'how')))) {
            $reply = "🎟️ **How to Book Event Tickets on Eventify:**\n\n";
            $reply .= "Booking your favorite events on Eventify is quick, simple, and secure:\n\n";
            $reply .= "1. **[Sign In / Register](/login)**: Log into your Eventify account.\n";
            $reply .= "2. **[Browse Events](/events)**: Explore featured events or filter by category (Music, Tech, Workshops, Sports, etc.).\n";
            $reply .= "3. **Select Ticket Tier**: Choose your preferred ticket tier (VIP, Standard, Early Bird) and number of seats.\n";
            $reply .= "4. **Proceed to Checkout**: Click **'Book Now'** to initiate payment.\n";
            $reply .= "5. **Pay with Khalti**: Complete the instant and secure payment using your **Khalti Digital Wallet**.\n";
            $reply .= "6. **Get Digital Ticket**: Instantly receive your confirmed digital ticket via email and view it anytime in **[My Tickets](/usereventbook)**!\n\n";
            $reply .= "Ready to find your next experience? **[Explore Events Now](/events)**!";

            return [
                'reply' => $reply,
                'suggestions' => ['🎉 Browse Events', '💳 Khalti Payments', '👤 My Tickets'],
            ];
        }

        // 7. Payment & Khalti Gateway FAQs
        if (str_contains($lower, 'payment') || str_contains($lower, 'khalti') || str_contains($lower, 'how to pay') || str_contains($lower, 'wallet') || str_contains($lower, 'refund') || str_contains($lower, 'transaction') || (str_contains($lower, 'pay') && !str_contains($lower, 'party'))) {
            $reply = "💳 **Payment Methods & Security on Eventify:**\n\n";
            $reply .= "• **Khalti Digital Wallet**: Eventify integrates official **Khalti** payment gateway for seamless digital transactions across Nepal.\n";
            $reply .= "• **Instant Verification**: Once confirmed via Khalti, your booking is verified in real-time and an automated digital ticket is generated immediately.\n";
            $reply .= "• **Safety & Encryption**: All financial transactions are encrypted with bank-grade security protocols.\n";
            $reply .= "• **Transaction Records**: View your payment receipts and booking references under **[My Tickets](/usereventbook)**.\n\n";
            $reply .= "Have an issue with a transaction? Feel free to reach out via our **[Contact Support](/contact)** page.";

            return [
                'reply' => $reply,
                'suggestions' => ['🎟️ How to Book', '🎉 View Events', '📞 Contact Support'],
            ];
        }

        // 8. Vendor & Organizer Inquiries (Hosting Events, Dashboard, Reports)
        if (str_contains($lower, 'vendor') || str_contains($lower, 'organizer') || str_contains($lower, 'host event') || str_contains($lower, 'host an event') || str_contains($lower, 'create event') || str_contains($lower, 'add event') || str_contains($lower, 'publish event') || str_contains($lower, 'organize event') || str_contains($lower, 'sell ticket')) {
            $reply = "💼 **Vendor & Organizer Hub on Eventify:**\n\n";
            $reply .= "Are you an event organizer, company, or artist? Eventify provides powerful tools to manage and scale your events:\n\n";
            $reply .= "• **[Vendor Dashboard](/vendor/dashboard)**: Real-time overview of ticket sales, revenue metrics, and attendee stats.\n";
            $reply .= "• **[Host a New Event](/vendor/events/create)**: Publish events with custom banners, descriptions, dates, venues, seat quotas, and multi-tier pricing.\n";
            $reply .= "• **[KYC Verification](/vendor/kyc)**: Complete one-time KYC verification (citizenship/PAN) for official organizer approval.\n";
            $reply .= "• **Attendee Management & PDF Reports**: Track attendee rosters and download official PDF sales reports for accounting.\n\n";
            $reply .= "👉 To get started, register with the **Vendor** role at **[Sign Up](/register)** or manage your events in the **[Vendor Dashboard](/vendor/dashboard)**.";

            return [
                'reply' => $reply,
                'suggestions' => ['💼 Vendor Dashboard', '📋 Vendor KYC', '🎉 Browse Events'],
            ];
        }

        // 9. KYC Verification Inquiries
        if (str_contains($lower, 'kyc') || str_contains($lower, 'verification') || str_contains($lower, 'verify vendor') || str_contains($lower, 'pan') || str_contains($lower, 'citizenship')) {
            $reply = "📋 **Vendor KYC Verification on Eventify:**\n\n";
            $reply .= "To maintain platform trust and security, event organizers undergo quick KYC verification:\n\n";
            $reply .= "1. **Submit Documents**: Go to **[Vendor KYC](/vendor/kyc)** and provide your government ID (Citizenship / Passport) or Business PAN registration.\n";
            $reply .= "2. **Admin Review**: Our team reviews your submitted documents promptly.\n";
            $reply .= "3. **Approval**: Once verified, you gain full access to publish paid ticketed events and withdraw revenues.\n\n";
            $reply .= "You can check or update your submission status anytime at **[Vendor KYC Portal](/vendor/kyc)**.";

            return [
                'reply' => $reply,
                'suggestions' => ['📋 Go to KYC Portal', '💼 Vendor Dashboard', '📞 Contact Support'],
            ];
        }

        // 10. Community Board (Chirps)
        if (str_contains($lower, 'chirp') || str_contains($lower, 'community') || str_contains($lower, 'forum') || str_contains($lower, 'social')) {
            $reply = "💬 **Eventify Chirps Community:**\n\n";
            $reply .= "**[Chirps](/chirps)** is our interactive community space where attendees and event creators connect!\n\n";
            $reply .= "• **Share Experiences**: Post thoughts, reviews, and memories from recent events.\n";
            $reply .= "• **Stay Updated**: Catch announcements and behind-the-scenes updates directly from event organizers.\n";
            $reply .= "• **Connect**: Engage with fellow enthusiasts who share your passions.\n\n";
            $reply .= "👉 Join the conversation today at **[Chirps Community](/chirps)**!";

            return [
                'reply' => $reply,
                'suggestions' => ['💬 Open Chirps', '🎉 Browse Events', '👤 My Profile'],
            ];
        }

        // 11. Event Category Searches & Specific Events Query
        if (preg_match('/\b(event|events|concert|concerts|workshop|workshops|festival|festivals|hackathon|hackathons|seminar|seminars|music|technology|tech|sports|conference|party|parties|art|business|coding|cultural|upcoming)\b/i', $lower)) {
            $categories = ['music', 'technology', 'tech', 'workshop', 'sports', 'festival', 'art', 'business', 'party', 'conference'];
            $matchedCategory = null;
            foreach ($categories as $cat) {
                if (preg_match('/\b' . preg_quote($cat, '/') . '\b/i', $lower)) {
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
                $title = $matchedCategory 
                    ? "🎉 **Top " . ucfirst($matchedCategory) . " Events on Eventify:**\n\n"
                    : "🎉 **Featured & Upcoming Events on Eventify:**\n\n";

                $reply = $title;
                foreach ($events as $event) {
                    $date = $event->event_date ? $event->event_date->format('D, M d, Y - h:i A') : 'Upcoming';
                    $price = $event->price > 0 ? "NPR " . number_format($event->price) : "FREE";
                    $seats = $event->available_seats !== null ? " ({$event->available_seats} seats left)" : "";
                    $location = $event->venue ? "📍 Location: {$event->venue}" : "📍 Location: Kathmandu";
                    
                    $reply .= "• **{$event->event_name}** ({$event->category})\n";
                    $reply .= "  📅 Date: {$date}\n";
                    $reply .= "  {$location}\n";
                    $reply .= "  💰 Price: {$price}{$seats}\n\n";
                }
                $reply .= "👉 Discover full lineups, select ticket tiers, and book passes at **[Browse All Events](/events)**!";
            } else {
                $reply = "We have exciting upcoming events in the works! Check out the complete, up-to-date listing on our **[Events Page](/events)**.";
            }

            return [
                'reply' => $reply,
                'suggestions' => ['🎟️ How to Book', '💳 Khalti Payments', '💼 Host an Event'],
            ];
        }

        // 12. Thanks / Politeness
        if (str_contains($lower, 'thank') || str_contains($lower, 'awesome') || str_contains($lower, 'great') || str_contains($lower, 'good job') || str_contains($lower, 'bye') || str_contains($lower, 'goodbye')) {
            return [
                'reply' => "You're very welcome! 😊 It's always my pleasure to assist you.\n\nEnjoy your experience on **Eventify**, and let me know whenever you need anything else!",
                'suggestions' => ['🎉 Upcoming Events', '🎟️ How to Book', '👤 My Profile'],
            ];
        }

        // 13. Default Smart Response
        return [
            'reply' => "I'm **EventBot AI**, your intelligent assistant for **Eventify**!\n\nI can answer questions and assist you with:\n• 📅 **[Upcoming Events & Ticket Tiers](/events)**\n• 🎟️ **[How to Book Event Passes](/events)**\n• 💳 **Khalti Digital Wallet Payments & Verification**\n• 👤 **[Your Tickets & Attendee Dashboard](/usereventbook)**\n• 💼 **[Vendor Event Hosting & KYC](/vendor/dashboard)**\n• 💬 **[Chirps Community Board](/chirps)**\n• 📞 **[Contacting Support](/contact)**\n\nWhat would you like to know?",
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
                        $ticketSummary[] = "{$tt->name}: NPR " . number_format($tt->price) . " ({$tt->remaining_quantity} left)";
                    }
                }
                $ticketsStr = !empty($ticketSummary) ? implode(', ', $ticketSummary) : "NPR " . number_format($e->price);
                $location = $e->venue ?? 'Kathmandu, Nepal';

                $lines[] = "- Event: \"{$e->event_name}\" | Category: {$e->category} | Date: {$date} | Location: {$location} | Ticket Tiers: [{$ticketsStr}] | Available Seats: {$e->available_seats}{$vendor} | Desc: {$e->description}";
            }
            return implode("\n", $lines);
        } catch (\Throwable $e) {
            return "Events data unavailable.";
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

            $info = "User Name: {$user->name} | Email: {$user->email} | Role: {$user->role}\n";
            $info .= "Event Bookings count: " . $eventBookings->count() . "\n";
            foreach ($eventBookings as $b) {
                $name = $b->event->event_name ?? 'Event #' . $b->event_id;
                $tier = $b->ticketType ? " ({$b->ticketType->name} Tier)" : "";
                $info .= "  * Booked {$b->tickets} ticket(s){$tier} for '{$name}' (Amount: NPR " . number_format($b->amount) . ")\n";
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
            '🎟️ How to Book',
            '💳 Khalti Payment',
            '💼 Host an Event',
            '👤 My Tickets',
            '📞 Contact Support',
        ];
    }

    /**
     * Extract contextual suggestions based on reply content
     */
    protected function extractSuggestions(string $reply, string $userMessage): array
    {
        $lower = strtolower($reply . ' ' . $userMessage);

        if (str_contains($lower, 'event') || str_contains($lower, 'concert') || str_contains($lower, 'workshop') || str_contains($lower, 'festival')) {
            return ['🎟️ How to Book', '💳 Payment Methods', '💼 Host an Event'];
        }
        if (str_contains($lower, 'payment') || str_contains($lower, 'khalti')) {
            return ['🎟️ Book an Event', '🎉 View Events', '📞 Contact Support'];
        }
        if (str_contains($lower, 'vendor') || str_contains($lower, 'organizer') || str_contains($lower, 'kyc')) {
            return ['💼 Vendor Dashboard', '📋 Vendor KYC', '🎉 View Events'];
        }
        if (str_contains($lower, 'ticket') || str_contains($lower, 'booking')) {
            return ['👤 My Tickets', '🎉 Browse Events', '💳 Payment Info'];
        }
        if (str_contains($lower, 'profile') || str_contains($lower, 'password') || str_contains($lower, 'account')) {
            return ['👤 My Profile', '🔑 Login', '📞 Contact Support'];
        }

        return $this->getDefaultSuggestions();
    }
}
