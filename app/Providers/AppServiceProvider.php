<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Midtrans\Config;
use Illuminate\Support\Facades\View;
use App\Models\Setting;

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
        /**
         * MIDTRANS GLOBAL CONFIG
         * Ini otomatis berlaku di seluruh aplikasi.
         */
        Config::$serverKey    = config('midtrans.server_key');
        Config::$clientKey    = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = config('midtrans.is_sanitized');
        Config::$is3ds        = config('midtrans.is_3ds');

        /**
         * GLOBAL $setting UNTUK SEMUA VIEW
         * Agar app.blade, header, footer, dan semua page bisa akses:
         * {{ $setting->whatsapp }}
         * {{ $setting->logo }}
         * dll.
         */
        View::composer('*', function ($view) {
            $view->with('setting', Setting::first());
        });
    }
}
