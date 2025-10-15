<?php

namespace App\Providers;

use App\Http\Controllers\ChirpController;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
{
    // Attach composer to 'welcome' view (used for both '/' and '/welcome')
    View::composer(['welcome'], function ($view) {
        $controller = new ChirpController();
        $upcomingEvents = $controller->getUpcomingEvents(10);
        $view->with('upcomingEvents', $upcomingEvents);
    });
}
}
