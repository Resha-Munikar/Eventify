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
                'event_name' => 'Nightwish Live - World Summer Tour',
                'category' => 'Concert',
                'venue' => 'Wembley Stadium Grounds, London',
                'event_date' => Carbon::now()->addDays(12)->setTime(19, 0),
                'description' => 'An extraordinary symphonic metal night featuring full orchestral accompaniment and world-class stage production.',
                'image' => '15053a8352d651a3d3eff09fa304fbe20af55c0c.png',
                'price' => 1500.00,
                'available_seats' => 250,
                'tickets' => [
                    ['name' => 'General Admission', 'price' => 1500.00, 'quantity' => 150],
                    ['name' => 'VIP Front Stage', 'price' => 3500.00, 'quantity' => 100],
                ],
            ],
            [
                'event_name' => 'Trevor Noah Live - Stand-Up Comedy Night',
                'category' => 'Comedy',
                'venue' => 'The Laugh Club, Downtown',
                'event_date' => Carbon::now()->addDays(18)->setTime(20, 0),
                'description' => 'An unforgettable evening of sharp wit, global satire, and unfiltered stand-up comedy with Trevor Noah.',
                'image' => 'fccc9509eb03af85b48aae86dbf69aaf9cd58c68.png',
                'price' => 800.00,
                'available_seats' => 180,
                'tickets' => [
                    ['name' => 'Standard Pass', 'price' => 800.00, 'quantity' => 120],
                    ['name' => 'Front Row VIP', 'price' => 1800.00, 'quantity' => 60],
                ],
            ],
            [
                'event_name' => 'Premier League - Championship Clash',
                'category' => 'Sports',
                'venue' => 'National Stadium Grounds',
                'event_date' => Carbon::now()->addDays(24)->setTime(16, 0),
                'description' => 'The biggest football rivalry clash of the season with intense action and electrifying stadium atmosphere.',
                'image' => 'ae8dcc6a0384dadbd627016ea66532c0b3399cad.png',
                'price' => 500.00,
                'available_seats' => 500,
                'tickets' => [
                    ['name' => 'Stadium Bleachers', 'price' => 500.00, 'quantity' => 400],
                    ['name' => 'VIP Club Box', 'price' => 2000.00, 'quantity' => 100],
                ],
            ],
            [
                'event_name' => 'Neon Beats & Rock Night',
                'category' => 'Concert',
                'venue' => 'Club Zenith, Downtown',
                'event_date' => Carbon::now()->addDays(28)->setTime(21, 0),
                'description' => 'High-voltage live band rock performances and electronic fusion sets under state-of-the-art laser rigs.',
                'image' => 'the edge band.jpg',
                'price' => 600.00,
                'available_seats' => 150,
                'tickets' => [
                    ['name' => 'Entry Pass', 'price' => 600.00, 'quantity' => 100],
                    ['name' => 'VIP Lounge Access', 'price' => 1500.00, 'quantity' => 50],
                ],
            ],
            [
                'event_name' => 'Late Night Comedy Showcase',
                'category' => 'Comedy',
                'venue' => 'The Laugh Lounge',
                'event_date' => Carbon::now()->addDays(32)->setTime(21, 30),
                'description' => 'Top rising comedians gathering for an intimate, laugh-a-minute late-night showcase.',
                'image' => '64674fee6c67e31feddd33dcea34912f9214acec.png',
                'price' => 450.00,
                'available_seats' => 90,
                'tickets' => [
                    ['name' => 'General Seating', 'price' => 450.00, 'quantity' => 60],
                    ['name' => 'Table for Two', 'price' => 1200.00, 'quantity' => 30],
                ],
            ],
            [
                'event_name' => 'Acoustic Sunset Sessions',
                'category' => 'Concert',
                'venue' => 'Botanical Amphitheater',
                'event_date' => Carbon::now()->addDays(38)->setTime(17, 30),
                'description' => 'Relax under the golden sunset with soulful unplugged acoustic guitar melodies and warm harmonies.',
                'image' => '00fa7ad18f34779d1ae0a1c2605280eb14af10bf.png',
                'price' => 750.00,
                'available_seats' => 120,
                'tickets' => [
                    ['name' => 'Lawn Seating', 'price' => 750.00, 'quantity' => 80],
                    ['name' => 'Golden Circle', 'price' => 1600.00, 'quantity' => 40],
                ],
            ],
            [
                'event_name' => 'Street Food & Night Market Carnival',
                'category' => 'Festival',
                'venue' => 'City Square Park',
                'event_date' => Carbon::now()->addDays(44)->setTime(18, 0),
                'description' => 'Taste over 50+ local and international street delicacies, craft beers, and live buskers.',
                'image' => 'e49d9f97ece86b34edcd240969adff6a25a1ab15.png',
                'price' => 300.00,
                'available_seats' => 300,
                'tickets' => [
                    ['name' => 'Tasting Pass', 'price' => 300.00, 'quantity' => 200],
                    ['name' => 'VIP Feast Pass', 'price' => 1200.00, 'quantity' => 100],
                ],
            ],
            [
                'event_name' => 'The Royal Opera & Classical Gala',
                'category' => 'Theatre',
                'venue' => 'National Grand Theatre',
                'event_date' => Carbon::now()->addDays(50)->setTime(18, 30),
                'description' => 'A magnificent stage production featuring classical drama, opulent costumes, and orchestral overtures.',
                'image' => '5d6d3c39ed125e58205d5ca13d839919649a3e0c.png',
                'price' => 1200.00,
                'available_seats' => 180,
                'tickets' => [
                    ['name' => 'Balcony Seat', 'price' => 1200.00, 'quantity' => 120],
                    ['name' => 'Royal Box', 'price' => 3000.00, 'quantity' => 60],
                ],
            ],
            [
                'event_name' => 'National Track & Field Sprint Championship',
                'category' => 'Sports',
                'venue' => 'Dasharath Stadium',
                'event_date' => Carbon::now()->addDays(56)->setTime(9, 0),
                'description' => 'Top athletes competing in 100m, 400m hurdles, and relay championships.',
                'image' => '27d39cfb3009d08541a1b429a677df002ffec2cd.png',
                'price' => 350.00,
                'available_seats' => 400,
                'tickets' => [
                    ['name' => 'Grandstand', 'price' => 350.00, 'quantity' => 300],
                    ['name' => 'Trackside VIP', 'price' => 1000.00, 'quantity' => 100],
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
