<?php

namespace Audentio\LaravelUserConfirmation\Providers;

use Audentio\LaravelAuth\LaravelAuth;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class UserConfirmationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->registerMigrations();
        }
    }

    protected function registerMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }
}