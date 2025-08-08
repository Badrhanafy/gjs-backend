<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

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
        // Configure rate limiters
        $this->configureRateLimiting();
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        // API rate limiter - 60 requests per minute
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(
                $request->user()?->id ?: $request->ip()
            );
        });

        // Auth rate limiter - stricter for login attempts
        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(5)->by(
                $request->ip()
            );
        });

        // Global rate limiter
        RateLimiter::for('global', function (Request $request) {
            if ($request->user()) {
                return Limit::perMinute(100)->by($request->user()->id);
            }
            
            return Limit::perMinute(20)->by($request->ip());
        });
    }
}