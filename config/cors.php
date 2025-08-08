<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        // Add your frontend URLs here
        'http://localhost:3000',      // React default
        'http://localhost:8080',      // Vue default
        'http://localhost:5173',      // Angular default
        'https://yourdomain.com',     // Production domain
        'https://www.yourdomain.com', // Production domain with www
    ],

    'allowed_origins_patterns' => [
        // For dynamic subdomains (use with caution in production)
        // '/^https:\/\/.*\.yourdomain\.com$/',
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => [
        'X-Total-Count',
        'X-Page-Count',
        'Authorization',
    ],

    'max_age' => 0,

    'supports_credentials' => true,

];