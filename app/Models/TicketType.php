<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
    use HasFactory;

    protected $table = 'ticket_types';

    protected $fillable = [
        'event_id',
        'name',
        'description',
        'price',
        'quantity',
        'sold_quantity',
        'sale_start',
        'sale_end',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'sold_quantity' => 'integer',
        'sale_start' => 'datetime',
        'sale_end' => 'datetime',
    ];

    protected $appends = [
        'remaining_quantity',
        'is_sold_out',
        'is_available',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getRemainingQuantityAttribute(): int
    {
        return max(0, (int)$this->quantity - (int)$this->sold_quantity);
    }

    public function getIsSoldOutAttribute(): bool
    {
        return $this->remaining_quantity <= 0;
    }

    public function getIsAvailableAttribute(): bool
    {
        if ($this->status !== 'active' || $this->is_sold_out) {
            return false;
        }

        $now = now();
        if ($this->sale_start && $now->lt($this->sale_start)) {
            return false;
        }
        if ($this->sale_end && $now->gt($this->sale_end)) {
            return false;
        }

        return true;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
