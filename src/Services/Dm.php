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
 * 邮件推送
 *
 * @method SingleSendMail(array $params) 单一发信接口，支持发送触发和批量邮件
 * @method BatchSendMail(array $params) 批量发信接口，支持通过调用模板的方式发送批量邮件
 *
 * @see https://help.aliyun.com/document_detail/29434.html
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Dm extends BaseService
{
    /**
     * @return string
     */
    public function getBaseUri()
    {
        return 'https://dm.aliyuncs.com';
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
            'version' => '2015-11-23',
        ]);
        $stack->push($middleware);
        return $stack;
    }
}