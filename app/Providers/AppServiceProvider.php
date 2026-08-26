<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;

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
        // 🚀 ল্যারাভেলকে রান-টাইমে 'owner' প্রোভাইডার জোরপূর্বক চেনাচ্ছি
        Config::set('auth.providers.owners', [
            'driver' => 'eloquent',
            'model' => \App\Models\Owner::class,
        ]);

        // 🚀 ল্যারাভেলকে রান-টাইমে 'owner' সেশন গার্ড জোরপূর্বক চেনাচ্ছি
        Config::set('auth.guards.owner', [
            'driver' => 'session',
            'provider' => 'owners',
        ]);
    }
}
