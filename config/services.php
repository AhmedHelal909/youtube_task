<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    'mailgun' => [
        'domain'   => env('MAILGUN_DOMAIN'),
        'secret'   => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme'   => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // ─── Anthropic ────────────────────────────────────────────────────────
    'anthropic' => [
        'key' => env('ANTHROPIC_API_KEY'),
    ],

    // ─── YouTube Data API v3 ──────────────────────────────────────────────
    'youtube' => [
        'key' => env('YOUTUBE_API_KEY'),
    ],

    'gemini' => [
        'key' => env('GEMINI_API_KEY'),
    ],

];
