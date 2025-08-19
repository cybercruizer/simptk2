<?php

namespace App\Providers;

use Filament\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentTimezone;
use App\Listeners\SendRoleNotificationOnRegister;

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
        FilamentTimezone::set('Asia/Jakarta');
        //Event::listen(Registered::class, [SendRoleNotificationOnRegister::class, 'handle']);
    }
}
