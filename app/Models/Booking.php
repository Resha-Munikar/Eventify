<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';

    protected $fillable = [
        'user_id',
        'event_id',
        'ticket_type_id',
        'tickets',
        'price_per_ticket',
        'subtotal',
        'service_charge',
        'amount',
        'total_amount',
        'booking_status',
        'payment_status',
        'booking_date',
        'payment_id',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'price_per_ticket' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'service_charge' => 'decimal:2',
        'amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function ticketType()
    {
        return $this->belongsTo(TicketType::class);
    }
}