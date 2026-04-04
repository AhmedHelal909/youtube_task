<?php

namespace App\Providers;

use App\Services\AnthropicService;
use App\Services\CourseFetcherService;
use App\Services\YouTubeService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind services as singletons so they share a single instance
        // per request (avoids re-reading config on every instantiation).
        $this->app->singleton(AnthropicService::class);
        $this->app->singleton(YouTubeService::class);
        $this->app->singleton(CourseFetcherService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Use our custom pagination view instead of Bootstrap's default
        Schema::defaultStringLength(191);

        Paginator::defaultView('courses._pagination');

    }
}
