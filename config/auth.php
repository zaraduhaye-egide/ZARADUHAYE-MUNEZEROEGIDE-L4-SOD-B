<?php

return [
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'shopkeepers',
        ],
    ],

    'providers' => [
        'shopkeepers' => [
            'driver' => 'eloquent',
            'model' => App\Models\Shopkeeper::class,
        ],
    ],

    'passwords' => [
        'shopkeepers' => [
            'provider' => 'shopkeepers',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
]; 