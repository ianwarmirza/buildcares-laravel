<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;

if (!function_exists('site_setting')) {
    function site_setting(string $key, mixed $default = null): mixed
    {
        try {
            if (Schema::hasTable('settings')) {
                return Setting::get($key, $default);
            }
        } catch (\Throwable $e) {
            // Fallback before migrations are run
        }
        return $default;
    }
}

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
        //
    }
}
