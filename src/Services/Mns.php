<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace XuTL\Aliyun\Services;

use AliyunMNS\Client;
use AliyunMNS\Queue;
use AliyunMNS\Topic;
use XuTL\Aliyun\AliyunInterface;

/**
 * Class Mns
 *
 * @mixin Client
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Mns implements AliyunInterface
{
    /** @var Client */
    private $client;

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
    protected $securityToken = null;

    /**
     * @var string
     */
    protected $defaultQueue;

    /**
     * @var string
     */
    protected $defaultTopic;

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
     * Returns a queue reference for operating on the queue
     * this function does not create the queue automatically.
     *
     * @param bool $base64 : whether the message in queue will be base64 encoded
     * @return Queue $queue: the Queue instance
     */
    public function queue($base64 = true)
    {
        return $this->getClient()->getQueueRef($this->defaultQueue, $base64);
    }

    /**
     * Returns a topic reference for operating on the topic
     * this function does not create the topic automatically.
     * @return Topic $topic: the Topic instance
     */
    public function topic()
    {
        return $this->getClient()->getTopicRef($this->defaultTopic);
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
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->getClient()->$method(...$parameters);
    }
}