<?php

namespace App\Providers;

use App\Contracts\NotifyInterface;
use App\Services\EmailNotifyServer;
use App\Services\SlackNotifyService;
use App\Services\SmsNotifyService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(NotifyInterface::class, function ($app) {
            return new EmailNotifyServer;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
