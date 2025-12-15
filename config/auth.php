<?php

return [
    'defaults' => [
        'guard' => env('AUTH_GUARD', 'sanctum'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'api_clients'),
    ],

    'guards' => [
        'sanctum' => [
            'driver' => 'sanctum',
            'provider' => 'api_clients',
        ],
    ],

    'providers' => [
        'api_clients' => [ // Novo Provider
            'driver' => 'eloquent',
            'model' => App\Models\ApiClient::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
        'api_clients' => [
            'provider' => 'api_clients',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),
];
