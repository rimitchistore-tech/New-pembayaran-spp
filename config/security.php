<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Security Headers
    |--------------------------------------------------------------------------
    */

    'hsts_enabled' => env('HSTS_ENABLED', true),
    'hsts_max_age' => env('HSTS_MAX_AGE', 31536000),
    'hsts_include_subdomains' => env('HSTS_INCLUDE_SUBDOMAINS', true),
    'hsts_preload' => env('HSTS_PRELOAD', false),

    'frame_options' => env('FRAME_OPTIONS', 'SAMEORIGIN'),
    'content_type_nosniff' => env('CONTENT_TYPE_NOSNIFF', true),
    'xss_protection' => env('XSS_PROTECTION', '1; mode=block'),
];
