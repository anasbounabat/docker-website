<?php

return [

    'apps' => [
        [
            'id' => env('REVERB_APP_ID'),
            'key' => env('REVERB_APP_KEY'),
            'secret' => env('REVERB_APP_SECRET'),
            'allowed_origins' => ['*'],
            'ping_interval' => 60,
            'max_message_size' => 10_000,
        ],
    ],

    'scaling' => [
        'enabled' => false,
        'channel' => 'reverb_cluster',
        'server' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'port' => env('REDIS_PORT', '6379'),
            'password' => env('REDIS_PASSWORD'),
        ],
    ],

    'cors' => [
        'paths' => ['api/*', 'broadcasting/auth'],
        'allowed_origins' => ['*'],
        'allowed_methods' => ['*'],
        'allowed_headers' => ['*'],
        'exposed_headers' => [],
        'max_age' => 0,
        'supports_credentials' => false,
    ],

    'health' => [
        'enabled' => true,
        'port' => env('REVERB_PORT', 8080),
    ],

];

