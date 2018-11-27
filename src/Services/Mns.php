<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace XuTL\Aliyun\Services;

use AliyunMNS\Client;
use XuTL\Aliyun\AliyunInterface;

/**
 * Class Mns
 *
 * @mixin Client
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Mns implements AliyunInterface
{
    /**
     * @var string 阿里云AccessKey ID
     */
    protected $accessId;

    /**
     * @var string AccessKey
     */
    protected $accessKey;

    /**
     * @var string
     */
    protected $endpoint;

    /**
     * @var string
     */
    protected $securityToken;

    /** @var Client */
    protected $client;

    /**
     * BaseService constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        if (!empty($config)) {
            foreach ($config as $name => $value) {
                $this->$name = $value;
            }
        }
    }

    /**
     * 获取MNS实例
     * @return Client
     */
    public function getClient()
    {
        if (!$this->client) {
            $this->client = new Client($this->endpoint, $this->accessId, $this->accessKey, $this->securityToken);
        }
        return $this->client;
    }

    /**
     * Dynamically call the default driver instance.
     *
     * @param  string $method
     * @param  array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->getClient()->$method(...$parameters);
    }
}