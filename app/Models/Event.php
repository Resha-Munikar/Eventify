<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'event_name',
        'event_date',
        'venue',
        'description',
        'image',
        'price',
        'available_seats',
        'category',
    ];

    protected $casts = [
        'event_date' => 'datetime',
    ];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function ticketTypes()
    {
        return $this->hasMany(TicketType::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function savedByUsers()
    {
        return $this->belongsToMany(User::class, 'saved_events', 'event_id', 'user_id')->withTimestamps();
    }

    public function isSavedBy(?User $user): bool
    {
        if (!$user) return false;
        return $this->savedByUsers()->where('users.id', $user->id)->exists();
    }

    public function getMinPriceAttribute(): float
    {
        $min = $this->ticketTypes->where('status', 'active')->min('price');
        return $min !== null ? (float)$min : (float)$this->price;
    }

    public function getMaxPriceAttribute(): float
    {
        $max = $this->ticketTypes->where('status', 'active')->max('price');
        return $max !== null ? (float)$max : (float)$this->price;
    }

    public function getTotalAvailableSeatsAttribute(): int
    {
        if ($this->ticketTypes->isNotEmpty()) {
            return (int)$this->ticketTypes->where('status', 'active')->sum(function ($t) {
                return $t->remaining_quantity;
            });
        }
        return (int)$this->available_seats;
    }

    public function getTotalSoldTicketsAttribute(): int
    {
        return (int)$this->ticketTypes->sum('sold_quantity');
    }

    protected $appends = [
        'slug',
    ];

    public function getSlugAttribute(): string
    {
        return \Illuminate\Support\Str::slug($this->event_name);
    }
}

