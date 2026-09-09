<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Booking;
use App\Models\Event;
use App\Models\TicketType;
use App\Mail\TicketMail;
use Illuminate\Support\Str;

class KhaltiController extends Controller
{
    /**
     * Save booking and update inventory after successful payment verification.
     */
    public function saveBooking(Request $request)
    {
        // Decode JSON data if submitted as raw JSON
        if ($request->isJson()) {
            $data = $request->json()->all();
            $request->merge($data);
        }

        // Validation
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'ticket_type_id' => 'nullable|exists:ticket_types,id',
            'tickets' => 'required|integer|min:1',
        ]);

        $eventId = (int)$request->event_id;
        $ticketTypeId = $request->filled('ticket_type_id') ? (int)$request->ticket_type_id : null;
        $requestedTickets = (int)$request->tickets;

        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'You must be logged in to book an event.'], 401);
        }

        try {
            $booking = DB::transaction(function () use ($eventId, $ticketTypeId, $requestedTickets, $user, $request) {
                // Lock event record
                $event = Event::lockForUpdate()->findOrFail($eventId);

                // Find ticket type (or default to first active ticket type of this event if none provided)
                if ($ticketTypeId) {
                    $ticketType = TicketType::where('id', $ticketTypeId)
                        ->where('event_id', $eventId)
                        ->lockForUpdate()
                        ->first();
                } else {
                    $ticketType = TicketType::where('event_id', $eventId)
                        ->where('status', 'active')
                        ->lockForUpdate()
                        ->first();
                }

                if (!$ticketType) {
                    throw new \Exception('Selected ticket type is not available for this event.');
                }

                if ($ticketType->status !== 'active') {
                    throw new \Exception('The selected ticket type is currently inactive.');
                }

                // Check remaining quantity on the ticket type
                $remainingQuantity = $ticketType->quantity - $ticketType->sold_quantity;
                if ($requestedTickets > $remainingQuantity) {
                    if ($remainingQuantity <= 0) {
                        throw new \Exception('Selected ticket type is sold out.');
                    }
                    throw new \Exception("Only {$remainingQuantity} ticket(s) remaining for {$ticketType->name}.");
                }

                // Authoritative Server-side Price Calculation (Never trust frontend amounts)
                $pricePerTicket = (float)$ticketType->price;
                $subtotal = $pricePerTicket * $requestedTickets;
                $serviceCharge = 5.65; // Standard service charge
                $totalAmount = $subtotal + $serviceCharge;

                // Create the booking record with complete historical snapshot
                $newBooking = Booking::create([
                    'user_id' => $user->id,
                    'event_id' => $event->id,
                    'ticket_type_id' => $ticketType->id,
                    'tickets' => $requestedTickets,
                    'price_per_ticket' => $pricePerTicket,
                    'subtotal' => $subtotal,
                    'service_charge' => $serviceCharge,
                    'amount' => $totalAmount,
                    'total_amount' => $totalAmount,
                    'booking_status' => 'confirmed',
                    'payment_status' => 'paid',
                    'booking_date' => now()->toDateString(),
                    'payment_id' => $request->input('payment_id') ?? ('khalti_' . Str::uuid()->toString()),
                ]);

                // Atomically update ticket type sold quantity
                $ticketType->increment('sold_quantity', $requestedTickets);

                // Atomically update aggregate event available seats
                $event->available_seats = max(0, (int)$event->available_seats - $requestedTickets);
                $event->save();

                return [
                    'booking' => $newBooking,
                    'ticketType' => $ticketType,
                    'event' => $event,
                ];
            });

            // Send confirmation email
            try {
                Mail::to($user->email)->send(new TicketMail(
                    $user,
                    $booking['event'],
                    $requestedTickets,
                    $booking['ticketType'],
                    $booking['booking']
                ));
            } catch (\Throwable $e) {
                Log::error('Failed to send TicketMail: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Booking saved successfully and ticket emailed!',
                'booking_id' => $booking['booking']->id,
            ]);

        } catch (\Throwable $e) {
            Log::error('Booking failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}