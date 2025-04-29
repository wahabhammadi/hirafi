<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Support\Facades\Event;

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
        // تسجيل مستمع لإرسال بريد التحقق عند التسجيل
        Event::listen(
            Registered::class,
            SendEmailVerificationNotification::class
        );
        
        // Registrar el componente de selección de provincias
        Blade::component('province-select', \App\View\Components\ProvinceSelect::class);
    }
}
