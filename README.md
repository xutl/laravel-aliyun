# laravel-aliyun

This is a aliyun expansion for the laravel

[![License](https://poser.pugx.org/xutl/laravel-aliyun/license.svg)](https://packagist.org/packages/xutl/laravel-aliyun)
[![Latest Stable Version](https://poser.pugx.org/xutl/laravel-aliyun/v/stable.png)](https://packagist.org/packages/xutl/laravel-aliyun)
[![Total Downloads](https://poser.pugx.org/xutl/laravel-aliyun/downloads.png)](https://packagist.org/packages/xutl/laravel-aliyun)

## 接口支持
- CDN
- 移动推送
- 邮件推送
- DNS
- 域名
- HTTPDNS
- MNS

## 环境需求

- PHP >= 5.6

## Installation

```bash
composer require xutl/laravel-aliyun
```

## for Laravel

This service provider must be registered.

```php
// config/app.php

'providers' => [
    '...',
    XuTL\Aliyun\AliyunServiceProvider::class,
];
```


## Use

```php
try {
	$aliyun = Aliyun::get('cdn');
	$cdn->RefreshObjectCaches([
		'ObjectPath' => [
			'http://www.baidu.com',
		],
		'ObjectType' => 'File'
	]);
} catch (\Exception $e) {
	print_r($e->getMessage());
}
```