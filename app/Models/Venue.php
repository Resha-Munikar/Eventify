<?php

namespace App\Models;

use App\Services\EventifyCacheService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::saved(function (Venue $venue) {
            EventifyCacheService::clearVenueCaches();
        });

        static::deleted(function (Venue $venue) {
            EventifyCacheService::clearVenueCaches();
        });
    }

    protected $fillable = [
        'venue_name',
        'location',
        'image',
        'description',
        'base_price',
        'price_type',
        'package_price',
        'package_details',
        'has_catering',
        'catering_price_per_person',
        'catering_menu',
        'vendor_id',
        'user_id',
    ];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
    public function reviews()
    {
        return $this->hasMany(\App\Models\Review::class);
    }
    public function inquiries()
    {
        return $this->hasMany(\App\Models\Inquiry::class);
    }
}
