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
        'mns' => [
            'driver' => 'mns',
            'endpoint' => env('ALIYUN_MNS_ENDPOINT', ''),
            'access_id' => env('ALIYUN_ACCESS_ID'),
            'access_key' => env('ALIYUN_ACCESS_KEY'),
            'securityToken' => null,
            'default_topic' => 'test',
            'default_queue' => 'test'
        ],
        'cloudpush' => [
            'driver' => 'cloudPush',
            'access_id' => env('ALIYUN_ACCESS_ID'),
            'access_key' => env('ALIYUN_ACCESS_KEY'),
        ],
        'dns' => [
            'driver' => 'dns',
            'access_id' => env('ALIYUN_ACCESS_ID'),
            'access_key' => env('ALIYUN_ACCESS_KEY'),
        ],
        'domain' => [
            'driver' => 'domain',
            'access_id' => env('ALIYUN_ACCESS_ID'),
            'access_key' => env('ALIYUN_ACCESS_KEY')
        ],
        'httpdns' => [
            'driver' => 'httpDns',
            'access_id' => env('ALIYUN_ACCESS_ID'),
            'access_key' => env('ALIYUN_ACCESS_KEY')
        ],
        'dm' => [
            'driver' => 'dm',
            'access_id' => env('ALIYUN_ACCESS_ID'),
            'access_key' => env('ALIYUN_ACCESS_KEY')
        ],
    ],

];