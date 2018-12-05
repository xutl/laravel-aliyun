<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace XuTL\Aliyun\Services;

use GuzzleHttp\HandlerStack;
use XuTL\Aliyun\BaseService;
use XuTL\Aliyun\RpcStack;

/**
 * Class HttpDns
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class HttpDns extends BaseService
{
    /**
     * @return string
     */
    public function getBaseUri()
    {
        return 'https://httpdns-api.aliyuncs.com';
    }

    /**
     * @return HandlerStack
     */
    public function getHttpStack()
    {
        $stack = HandlerStack::create();
        $middleware = new RpcStack([
            'accessKeyId' => $this->accessId,
            'accessSecret' => $this->accessKey,
            'version' => '2016-02-01',
        ]);
        $stack->push($middleware);
        return $stack;
    }
}