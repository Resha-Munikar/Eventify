<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class HomepageEventsSeeder extends Seeder
{
    public function run(): void
    {
        // Get or create a vendor user
        $vendor = User::where('role', 'vendor')->first();
        if (!$vendor) {
            $vendor = User::where('role', 'admin')->first();
        }
        if (!$vendor) {
            $vendor = User::first();
        }

        $vendorId = $vendor ? $vendor->id : null;

        $eventsData = [
            [
                'event_name' => 'Nepathya Live in Kirtipur',
                'category' => 'Concert',
                'venue' => 'Lab School Ground, Kirtipur',
                'event_date' => Carbon::create(2026, 10, 10, 18, 15),
                'description' => 'Iconic folk-rock band Nepathya performs live in Kirtipur. Gates open at 4:15 PM with the main show starting at 6:15 PM sharp.',
                'image' => 'nepathya_live.jpg',
                'price' => 1500.00,
                'available_seats' => 500,
                'tickets' => [
                    ['name' => 'Regular Entry', 'price' => 1500.00, 'quantity' => 500],
                ],
            ],
            [
                'event_name' => 'Dashain Cultural Festival 2026',
                'category' => 'Festival',
                'venue' => 'Basantapur Durbar Square, Kathmandu',
                'event_date' => Carbon::create(2026, 10, 17, 10, 0),
                'description' => 'Experience traditional Phulpati processions, traditional music performances, and community celebrations in historic Kathmandu Durbar Square.',
                'image' => 'dashain_festival.jpg',
                'price' => 200.00,
                'available_seats' => 1000,
                'tickets' => [
                    ['name' => 'Festival Pass', 'price' => 200.00, 'quantity' => 800],
                    ['name' => 'VIP Cultural Pass', 'price' => 500.00, 'quantity' => 200],
                ],
            ],
            [
                'event_name' => 'Jazzmandu 2026 Finale',
                'category' => 'Concert',
                'venue' => 'The Malla Hotel, Thamel, Kathmandu',
                'event_date' => Carbon::create(2026, 11, 4, 18, 30),
                'description' => 'The grand finale of the Kathmandu Jazz Festival featuring all international and national artists together on one stage.',
                'image' => 'jazzmandu.jpg',
                'price' => 1800.00,
                'available_seats' => 300,
                'tickets' => [
                    ['name' => 'General Ticket', 'price' => 1800.00, 'quantity' => 250],
                    ['name' => 'VIP Table Pass', 'price' => 3500.00, 'quantity' => 50],
                ],
            ],
            [
                'event_name' => 'Tihar Festival of Lights 2026',
                'category' => 'Festival',
                'venue' => 'Patan Durbar Square, Lalitpur',
                'event_date' => Carbon::create(2026, 11, 6, 17, 0),
                'description' => 'Grand Deepawali lighting, colorful Rangoli competitions, cultural folk dances, and traditional Deusi Bhailo performances.',
                'image' => 'tihar_lights.jpg',
                'price' => 150.00,
                'available_seats' => 800,
                'tickets' => [
                    ['name' => 'Visitor Pass', 'price' => 150.00, 'quantity' => 800],
                ],
            ],
            [
                'event_name' => 'Echoes of Bollywood ft. Monali Thakur',
                'category' => 'Concert',
                'venue' => 'Club NOVA, Thamel, Kathmandu',
                'event_date' => Carbon::create(2026, 11, 14, 20, 0),
                'description' => 'Bollywood playback singer Monali Thakur performing live in Kathmandu along with top supporting DJs and live acts.',
                'image' => 'monali_thakur.jpg',
                'price' => 2000.00,
                'available_seats' => 400,
                'tickets' => [
                    ['name' => 'General Pass', 'price' => 2000.00, 'quantity' => 300],
                    ['name' => 'VIP Front Stage', 'price' => 5000.00, 'quantity' => 100],
                ],
            ],
            [
                'event_name' => 'Kathmandu Street Food & Craft Beer Fest 2026',
                'category' => 'Food & Drink',
                'venue' => 'Bhrikutimandap Garden, Kathmandu',
                'event_date' => Carbon::create(2026, 11, 20, 12, 0),
                'description' => 'Taste over 50+ local Newari delicacies, street food stalls, craft brews, and enjoy live acoustic performances in central Kathmandu.',
                'image' => 'food_fest.jpg',
                'price' => 300.00,
                'available_seats' => 700,
                'tickets' => [
                    ['name' => 'Tasting Entry Pass', 'price' => 300.00, 'quantity' => 500],
                    ['name' => 'VIP Feast Pass', 'price' => 1000.00, 'quantity' => 200],
                ],
            ],
            [
                'event_name' => 'EVTECH Nepal Expo 2026',
                'category' => 'Technology',
                'venue' => 'Bhrikutimandap Exhibition Hall, Kathmandu',
                'event_date' => Carbon::create(2026, 11, 26, 10, 0),
                'description' => 'Nepal’s premier international expo showcasing electric vehicles, battery technologies, charging infrastructure, and green tech.',
                'image' => 'evtech_expo.jpg',
                'price' => 300.00,
                'available_seats' => 1500,
                'tickets' => [
                    ['name' => 'Visitor Pass', 'price' => 300.00, 'quantity' => 1200],
                    ['name' => 'Delegate Pass', 'price' => 1500.00, 'quantity' => 300],
                ],
            ],
            [
                'event_name' => 'Kathmandu Marathon & Sprint Championship',
                'category' => 'Sports',
                'venue' => 'Dasharath Stadium, Tripreshwar, Kathmandu',
                'event_date' => Carbon::create(2026, 11, 28, 7, 0),
                'description' => 'Annual premier long-distance road race and national track & field sprint championship bringing top runners from across Nepal.',
                'image' => 'marathon.jpg',
                'price' => 500.00,
                'available_seats' => 800,
                'tickets' => [
                    ['name' => 'Marathon Runner Ticket', 'price' => 500.00, 'quantity' => 600],
                    ['name' => 'Grandstand Spectator Pass', 'price' => 300.00, 'quantity' => 200],
                ],
            ],
            [
                'event_name' => 'Nepal Contemporary Art & Sculpture Exhibition',
                'category' => 'Art',
                'venue' => 'Nepal Art Council, Babar Mahal, Kathmandu',
                'event_date' => Carbon::create(2026, 12, 5, 10, 30),
                'description' => 'Showcasing contemporary Nepalese paintings, traditional Paubha artwork, and modern sculptures from acclaimed local artists.',
                'image' => 'art_exhibition.jpg',
                'price' => 250.00,
                'available_seats' => 400,
                'tickets' => [
                    ['name' => 'General Exhibition Pass', 'price' => 250.00, 'quantity' => 400],
                ],
            ],
            [
                'event_name' => 'Himalayan Sunrise Yoga & Mindfulness Retreat',
                'category' => 'Wellness',
                'venue' => 'Garden of Dreams, Keshar Mahal, Thamel',
                'event_date' => Carbon::create(2026, 12, 12, 6, 30),
                'description' => 'Invigorating morning yoga session led by certified Himalayan instructors, guided meditation, and organic herbal tea tasting.',
                'image' => 'yoga_retreat.jpg',
                'price' => 800.00,
                'available_seats' => 150,
                'tickets' => [
                    ['name' => 'Workshop Entry', 'price' => 800.00, 'quantity' => 150],
                ],
            ],
            [
                'event_name' => 'Holi Festival of Colors 2027',
                'category' => 'Festival',
                'venue' => 'Basantapur Durbar Square, Kathmandu',
                'event_date' => Carbon::create(2027, 3, 22, 10, 0),
                'description' => 'The iconic celebration of colors, water showers, live DJ music, and traditional Newari folk dance performances in the heart of Kathmandu.',
                'image' => 'holi_festival.jpg',
                'price' => 150.00,
                'available_seats' => 1200,
                'tickets' => [
                    ['name' => 'General Festival Ticket', 'price' => 150.00, 'quantity' => 1000],
                    ['name' => 'Color & Merchandise Pass', 'price' => 600.00, 'quantity' => 200],
                ],
            ],
        ];

        foreach ($eventsData as $data) {
            $tickets = $data['tickets'];
            unset($data['tickets']);
            
            $data['vendor_id'] = $vendorId;

            $event = Event::updateOrCreate(
                ['event_name' => $data['event_name']],
                $data
            );

            // Create ticket types for this event
            foreach ($tickets as $tData) {
                TicketType::updateOrCreate(
                    [
                        'event_id' => $event->id,
                        'name' => $tData['name'],
                    ],
                    [
                        'description' => 'Includes access to ' . $event->event_name,
                        'price' => $tData['price'],
                        'quantity' => $tData['quantity'],
                        'sold_quantity' => 0,
                        'status' => 'active',
                    ]
                );
            }
        }
    }
}
