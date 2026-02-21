<?php

namespace App\Providers;

use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ActivityLogService::class);
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);
    }
}