<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SecurityServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Add security headers
        $this->app['router']->pushMiddlewareToGroup('web', function ($request, $next) {
            $response = $next($request);

            // HSTS
            if (config('security.hsts_enabled')) {
                $hsts = 'max-age=' . config('security.hsts_max_age');
                if (config('security.hsts_include_subdomains')) {
                    $hsts .= '; includeSubDomains';
                }
                if (config('security.hsts_preload')) {
                    $hsts .= '; preload';
                }
                $response->header('Strict-Transport-Security', $hsts);
            }

            // Frame Options
            $response->header('X-Frame-Options', config('security.frame_options'));

            // Content Type Options
            if (config('security.content_type_nosniff')) {
                $response->header('X-Content-Type-Options', 'nosniff');
            }

            // XSS Protection
            $response->header('X-XSS-Protection', config('security.xss_protection'));

            // Content Security Policy
            $response->header('Content-Security-Policy', "default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; img-src 'self' data: https:");

            // Referrer Policy
            $response->header('Referrer-Policy', 'strict-origin-when-cross-origin');

            return $response;
        });
    }
}
