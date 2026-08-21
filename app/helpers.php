<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;

if (!function_exists('site_setting')) {
    /**
     * Get a site setting by key with a default fallback.
     */
    function site_setting(string $key, mixed $default = null): mixed
    {
        try {
            if (class_exists(Setting::class) && Schema::hasTable('settings')) {
                return Setting::get($key, $default);
            }
        } catch (\Throwable $e) {
            // Fallback if DB table does not exist yet or connection fails
        }
        return $default;
    }
}
