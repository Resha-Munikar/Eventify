<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'type',
        'vendor_id',
        'event_id',
        'venue_id',
        'subject',
        'message',
        'status',
        'admin_notes',
    ];

    /**
     * The customer user who sent the inquiry (if logged in).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The vendor to whom the inquiry is directed (null for general inquiries).
     */
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    /**
     * The associated event if inquiry is for an event.
     */
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    /**
     * The associated venue if inquiry is for a venue.
     */
    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }

    /**
     * Scope for unread inquiries.
     */
    public function scopeUnread($query)
    {
        return $query->where('status', 'unread');
    }

    /**
     * Scope for inquiries belonging to a specific vendor.
     */
    public function scopeForVendor($query, int $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    /**
     * Get a formatted inquiry type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'event'   => 'Event Inquiry',
            'venue'   => 'Venue Inquiry',
            'vendor'  => 'Vendor Inquiry',
            default   => 'General Contact',
        };
    }
}
