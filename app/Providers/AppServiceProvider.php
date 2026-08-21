<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Require helpers file to guarantee availability even before composer autoload is dumped
if (file_exists(__DIR__ . '/../helpers.php')) {
    require_once __DIR__ . '/../helpers.php';
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
