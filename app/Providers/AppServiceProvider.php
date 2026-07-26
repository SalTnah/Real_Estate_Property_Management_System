<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
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
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        View::composer('components.agent.topbar', function ($view) {
            $agent = auth()->user()?->agent;
            $view->with('unreadNotifications', $agent ? $agent->notifications()->unread()->count() : 0);
        });
    }
}