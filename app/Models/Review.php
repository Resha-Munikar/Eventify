<?php

namespace App\Models;

use App\Services\EventifyCacheService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::saved(function (Review $review) {
            EventifyCacheService::clearReviewCaches();
        });

        static::deleted(function (Review $review) {
            EventifyCacheService::clearReviewCaches();
        });
    }

    protected $fillable = [
        'user_id',          
        'venue_id',
        'booking_id', 
        'rating',
        'comment',
    ];

    public function user() { 
        return $this->belongsTo(User::class); 
    }
    public function venue() { 
        return $this->belongsTo(Venue::class); 
    }
    public function booking()
    {
        return $this->belongsTo(VenueBooking::class, 'booking_id');
    }
    public function venueBooking()
    {
        return $this->hasOne(VenueBooking::class, 'venue_id', 'venue_id');
    }


}
