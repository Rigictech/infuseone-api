<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Mail\MailManager;
use App\Mail\Transport\BrevoTransport;


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

        $this->app->make(MailManager::class)->extend('brevo', function () {
        return new BrevoTransport(config('mail.brevo.api_key'));
    });
    }
}
