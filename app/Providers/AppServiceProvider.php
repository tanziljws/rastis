<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // Force HTTPS in production (Railway, etc.)
        if (config('app.env') === 'production' || env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
        
        // Also force HTTPS if APP_URL is HTTPS
        $appUrl = config('app.url');
        if ($appUrl && str_starts_with($appUrl, 'https://')) {
            URL::forceScheme('https');
        }
        
        // Increase upload limits for file uploads (fix 413 error)
        // Note: These settings only work if PHP allows ini_set
        // For Railway, set these in environment variables or php.ini
        if (function_exists('ini_set')) {
            @ini_set('upload_max_filesize', '10M');
            @ini_set('post_max_size', '220M'); // 20 files * 10MB + overhead
            @ini_set('max_file_uploads', '20');
            @ini_set('memory_limit', '256M');
            @ini_set('max_execution_time', '300'); // 5 minutes for multiple uploads
        }
    }
}
