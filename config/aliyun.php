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
            'endpoint' => 'http://132456.mns.cn-hangzhou.aliyuncs.com/',
            'access_id' => env('ALIYUN_ACCESS_ID'),
            'access_key' => env('ALIYUN_ACCESS_KEY'),
            'securityToken' => null
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
    ],

];