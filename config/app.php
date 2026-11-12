<?php

use Illuminate\Support\Facades\Facade;

return [

    'name' => env('APP_NAME', 'Sistem Perpustakaan'),
    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'asset_url' => env('ASSET_URL'),
    'timezone' => env('APP_TIMEZONE', 'Asia/Jakarta'),
    'locale' => env('APP_LOCALE', 'id'),
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),
    'faker_locale' => env('APP_FAKER_LOCALE', 'id_ID'),
    'cipher' => 'AES-256-CBC',
    'key' => env('APP_KEY'),
    'previous_keys' => array_filter(
        array_map('trim', explode(',', env('APP_PREVIOUS_KEYS', '')))
    ),
    'phpredis' => false,
    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'cache' => env('APP_MAINTENANCE_CACHE_STORE', 'database'),
    ],
    'aliases' => Facade::defaultAliases()->merge([
        // 'CustomClass' => App\Support\CustomClass,
    ])->toArray(),
];
