<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class EventifyCacheService
{
    public const KEY_WELCOME_REVIEWS = 'welcome_reviews';
    public const KEY_WELCOME_UPCOMING = 'welcome_upcoming_events';
    public const KEY_WELCOME_TRENDING = 'welcome_trending_events';
    public const KEY_WELCOME_CATEGORY_COUNTS = 'welcome_category_counts';
    public const KEY_EVENTS_LIST_DEFAULT = 'events_list_default';
    public const KEY_VENUES_ALL = 'venues_all';

    public const TTL_SHORT = 900;       // 15 minutes
    public const TTL_MEDIUM = 1800;     // 30 minutes
    public const TTL_LONG = 3600;       // 1 hour

    /**
     * Clear all caches related to events (homepage, listing, and single event caches).
     */
    public static function clearEventCaches(?int $eventId = null, ?string $slug = null, ?int $vendorId = null): void
    {
        Cache::forget(self::KEY_WELCOME_UPCOMING);
        Cache::forget(self::KEY_WELCOME_TRENDING);
        Cache::forget(self::KEY_WELCOME_CATEGORY_COUNTS);
        Cache::forget(self::KEY_EVENTS_LIST_DEFAULT);

        if ($eventId) {
            Cache::forget("event_show_{$eventId}");
        }
        if ($slug) {
            Cache::forget("event_show_{$slug}");
        }
        if ($vendorId) {
            Cache::forget("vendor_categories_{$vendorId}");
        }
    }

    /**
     * Clear all caches related to reviews.
     */
    public static function clearReviewCaches(): void
    {
        Cache::forget(self::KEY_WELCOME_REVIEWS);
    }

    /**
     * Clear all caches related to venues.
     */
    public static function clearVenueCaches(): void
    {
        Cache::forget(self::KEY_VENUES_ALL);
    }

    /**
     * Clear all public Eventify caches.
     */
    public static function clearAll(): void
    {
        Cache::forget(self::KEY_WELCOME_REVIEWS);
        Cache::forget(self::KEY_WELCOME_UPCOMING);
        Cache::forget(self::KEY_WELCOME_TRENDING);
        Cache::forget(self::KEY_WELCOME_CATEGORY_COUNTS);
        Cache::forget(self::KEY_EVENTS_LIST_DEFAULT);
        Cache::forget(self::KEY_VENUES_ALL);
    }
}
