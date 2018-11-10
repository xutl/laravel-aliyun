<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace XuTL\Aliyun\Services;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use XuTL\Aliyun\AliyunInterface;
use XuTL\Aliyun\Subscriber\Rpc;

/**
 * Class CDN
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Cdn implements AliyunInterface
{
    private $accessId;
    private $accessSecret;

    /**
     * Cdn constructor.
     */
    public function __construct($config)
    {
        $stack = HandlerStack::create();
        $middleware = new Rpc([
            'accessKeyId' => $config,
            'accessSecret' => $config,
        ]);
        $stack->push($middleware);
    }

    public function getClient()
    {

    }

}