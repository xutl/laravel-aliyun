<?php

return [
    'access_id' => env('ALIYUN_ACCESS_ID'),
    'access_key' => env('ALIYUN_ACCESS_KEY'),

    'services' => [
        'cdn' => [
            'driver' => 'cdn',
            'access_id' => env('ALIYUN_ACCESS_ID'),
            'access_key' => env('ALIYUN_ACCESS_KEY'),
        ],
    ],

];