<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'subject_type',
        'subject_id',
    ];

    /**
     * The user who performed this activity.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Deleted User',
            'role' => 'user',
            'email' => 'N/A',
        ]);
    }

    /**
     * Polymorphic relation to the entity associated with the activity (Event, Venue, Booking, Review, etc.).
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }
}
