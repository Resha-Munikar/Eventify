<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ActivityLogger
{
    /**
     * System-wide predefined activity action types and their human-readable labels.
     */
    public const ACTIONS = [
        'logged_in'               => 'Logged in',
        'logged_out'              => 'Logged out',
        'registered'              => 'Registered',
        'email_verified'          => 'Email Verified',
        'password_changed'        => 'Password Changed',
        'event_created'           => 'Created event',
        'event_updated'           => 'Updated event',
        'event_deleted'           => 'Deleted event',
        'event_booked'            => 'Booked event',
        'event_booking_cancelled' => 'Cancelled event booking',
        'event_saved'             => 'Saved event',
        'venue_added'             => 'Added venue',
        'venue_updated'           => 'Updated venue',
        'venue_deleted'           => 'Deleted venue',
        'venue_booked'            => 'Booked venue',
        'venue_booking_paid'      => 'Paid venue booking',
        'venue_booking_cancelled' => 'Cancelled venue booking',
        'review_submitted'        => 'Submitted review',
        'review_deleted'          => 'Deleted review',
        'inquiry_sent'            => 'Sent inquiry',
        'inquiry_viewed'          => 'Viewed inquiry',
        'inquiry_status_updated'  => 'Updated inquiry status',
        'profile_updated'         => 'Updated profile',
        'profile_photo_deleted'   => 'Deleted profile photo',
        'user_updated'            => 'Admin updated user',
        'user_deleted'            => 'Admin deleted user',
        'event_viewed'            => 'Viewed event',
        'kyc_submitted'           => 'Submitted KYC verification',
        'kyc_resubmitted'         => 'Resubmitted KYC verification',
        'kyc_approved'            => 'Approved KYC verification',
        'kyc_rejected'            => 'Rejected KYC verification',
    ];

    /**
     * Log a user activity cleanly and safely into the activity_logs table.
     *
     * @param string $action Key matching system action (e.g. 'logged_in', 'event_created')
     * @param string $description Clear human-readable description (e.g. 'Booked event "Kathmandu Jazz Fest"')
     * @param Model|null $subject The model object related to the event (Event, Venue, Booking, etc.)
     * @param User|int|null $user The user performing the action (defaults to authenticated user)
     * @return ActivityLog|null
     */
    public static function log(string $action, string $description, ?Model $subject = null, $user = null): ?ActivityLog
    {
        try {
            $userId = null;

            if ($user instanceof User) {
                $userId = $user->id;
            } elseif (is_numeric($user)) {
                $userId = (int) $user;
            } elseif (Auth::check()) {
                $userId = Auth::id();
            }

            if (!$userId) {
                return null;
            }

            $subjectType = null;
            $subjectId = null;

            if ($subject && $subject instanceof Model) {
                $subjectType = get_class($subject);
                $subjectId = $subject->getKey();
            }

            return ActivityLog::create([
                'user_id'      => $userId,
                'action'       => $action,
                'description'  => $description,
                'subject_type' => $subjectType,
                'subject_id'   => $subjectId,
            ]);
        } catch (\Throwable $e) {
            Log::warning('ActivityLogger error: ' . $e->getMessage(), [
                'action'      => $action,
                'description' => $description,
            ]);
            return null;
        }
    }

    /**
     * Get all available action keys and their human-readable labels.
     *
     * @return array<string, string>
     */
    public static function getAvailableActions(): array
    {
        return self::ACTIONS;
    }
}
