<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace XuTL\Aliyun\Services;

use AliyunMNS\AsyncCallback;
use AliyunMNS\Exception\InvalidArgumentException;
use AliyunMNS\Exception\MnsException;
use AliyunMNS\Exception\QueueAlreadyExistException;
use AliyunMNS\Exception\TopicAlreadyExistException;
use AliyunMNS\Http\HttpClient;
use AliyunMNS\Model\AccountAttributes;
use AliyunMNS\Queue;
use AliyunMNS\Requests\CreateQueueRequest;
use AliyunMNS\Requests\CreateTopicRequest;
use AliyunMNS\Requests\DeleteQueueRequest;
use AliyunMNS\Requests\DeleteTopicRequest;
use AliyunMNS\Requests\GetAccountAttributesRequest;
use AliyunMNS\Requests\ListQueueRequest;
use AliyunMNS\Requests\ListTopicRequest;
use AliyunMNS\Requests\SetAccountAttributesRequest;
use AliyunMNS\Responses\CreateQueueResponse;
use AliyunMNS\Responses\CreateTopicResponse;
use AliyunMNS\Responses\DeleteQueueResponse;
use AliyunMNS\Responses\DeleteTopicResponse;
use AliyunMNS\Responses\GetAccountAttributesResponse;
use AliyunMNS\Responses\ListQueueResponse;
use AliyunMNS\Responses\ListTopicResponse;
use AliyunMNS\Responses\MnsPromise;
use AliyunMNS\Responses\SetAccountAttributesResponse;
use AliyunMNS\Topic;
use XuTL\Aliyun\AliyunInterface;

/**
 * Class Mns
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Mns implements AliyunInterface
{
    /** @var HttpClient */
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
     * @param string $queueName :  the queue name
     * @param bool $base64 : whether the message in queue will be base64 encoded
     * @return Queue $queue: the Queue instance
     */
    public function getQueueRef($queueName = null, $base64 = true)
    {
        return new Queue($this->getClient(), $queueName != null ? $queueName : $this->defaultQueue, $base64);
    }

    /**
     * Create Queue and Returns the Queue reference
     *
     * @param CreateQueueRequest $request :  the QueueName and QueueAttributes
     * @return \AliyunMNS\Responses\BaseResponse|CreateQueueResponse
     * @throws QueueAlreadyExistException if queue already exists
     * @throws InvalidArgumentException if any argument value is invalid
     * @throws MnsException if any other exception happends
     */
    public function createQueue(CreateQueueRequest $request)
    {
        $response = new CreateQueueResponse($request->getQueueName());
        return $this->getClient()->sendRequest($request, $response);
    }

    /**
     * Create Queue and Returns the Queue reference
     * The request will not be sent until calling MnsPromise->wait();
     *
     * @param CreateQueueRequest $request :  the QueueName and QueueAttributes
     * @param AsyncCallback $callback :  the Callback when the request finishes
     * @return MnsPromise $promise: the MnsPromise instance
     */
    public function createQueueAsync(CreateQueueRequest $request, AsyncCallback $callback = null)
    {
        $response = new CreateQueueResponse($request->getQueueName());
        return $this->getClient()->sendRequestAsync($request, $response, $callback);
    }

    /**
     * Query the queues created by current account
     *
     * @param ListQueueRequest $request : define filters for quering queues
     * @return \AliyunMNS\Responses\BaseResponse|ListQueueResponse
     */
    public function listQueue(ListQueueRequest $request)
    {
        $response = new ListQueueResponse();
        return $this->getClient()->sendRequest($request, $response);
    }

    /**
     * Query the queues created by current account
     * @param ListQueueRequest $request
     * @param AsyncCallback|null $callback
     * @return MnsPromise
     */
    public function listQueueAsync(ListQueueRequest $request, AsyncCallback $callback = null)
    {
        $response = new ListQueueResponse();
        return $this->getClient()->sendRequestAsync($request, $response, $callback);
    }

    /**
     * Delete the specified queue
     * the request will succeed even when the queue does not exist
     * @param $queueName : the queueName
     * @return \AliyunMNS\Responses\BaseResponse|DeleteQueueResponse
     */
    public function deleteQueue($queueName)
    {
        $request = new DeleteQueueRequest($queueName);
        $response = new DeleteQueueResponse();
        return $this->getClient()->sendRequest($request, $response);
    }

    /**
     * Delete the specified queue
     * @param string $queueName
     * @param AsyncCallback|null $callback
     * @return MnsPromise
     */
    public function deleteQueueAsync($queueName, AsyncCallback $callback = null)
    {
        $request = new DeleteQueueRequest($queueName);
        $response = new DeleteQueueResponse();
        return $this->getClient()->sendRequestAsync($request, $response, $callback);
    }

    // API for Topic

    /**
     * Returns a topic reference for operating on the topic
     * this function does not create the topic automatically.
     * @param string $topicName :  the topic name
     * @return Topic $topic: the Topic instance
     */
    public function getTopicRef($topicName = null)
    {
        return new Topic($this->getClient(), $topicName != null ? $topicName : $this->defaultTopic);
    }

    /**
     * Create Topic and Returns the Topic reference
     *
     * @param CreateTopicRequest $request :  the TopicName and TopicAttributes
     * @return \AliyunMNS\Responses\BaseResponse|CreateTopicResponse
     * @throws TopicAlreadyExistException if topic already exists
     * @throws InvalidArgumentException if any argument value is invalid
     * @throws MnsException if any other exception happends
     */
    public function createTopic(CreateTopicRequest $request)
    {
        $response = new CreateTopicResponse($request->getTopicName());
        return $this->getClient()->sendRequest($request, $response);
    }

    /**
     * Delete the specified topic
     * the request will succeed even when the topic does not exist
     * @param $topicName : the topicName
     * @return \AliyunMNS\Responses\BaseResponse|DeleteTopicResponse
     */
    public function deleteTopic($topicName)
    {
        $request = new DeleteTopicRequest($topicName);
        $response = new DeleteTopicResponse();
        return $this->getClient()->sendRequest($request, $response);
    }

    /**
     * Query the topics created by current account
     * @param ListTopicRequest $request : define filters for quering topics
     * @return \AliyunMNS\Responses\BaseResponse|ListTopicResponse
     */
    public function listTopic(ListTopicRequest $request)
    {
        $response = new ListTopicResponse();
        return $this->getClient()->sendRequest($request, $response);
    }

    /**
     * Query the AccountAttributes
     *
     * @return \AliyunMNS\Responses\BaseResponse|GetAccountAttributesResponse
     * @throws MnsException if any exception happends
     */
    public function getAccountAttributes()
    {
        $request = new GetAccountAttributesRequest();
        $response = new GetAccountAttributesResponse();
        return $this->getClient()->sendRequest($request, $response);
    }

    /**
     * Query the AccountAttributes
     * @param AsyncCallback|null $callback
     * @return MnsPromise
     */
    public function getAccountAttributesAsync(AsyncCallback $callback = null)
    {
        $request = new GetAccountAttributesRequest();
        $response = new GetAccountAttributesResponse();
        return $this->getClient()->sendRequestAsync($request, $response, $callback);
    }

    /**
     * Set the AccountAttributes
     *
     * @param AccountAttributes $attributes : the AccountAttributes to set
     * @return \AliyunMNS\Responses\BaseResponse|SetAccountAttributesResponse
     * @throws MnsException if any exception happends
     */
    public function setAccountAttributes(AccountAttributes $attributes)
    {
        $request = new SetAccountAttributesRequest($attributes);
        $response = new SetAccountAttributesResponse();
        return $this->getClient()->sendRequest($request, $response);
    }

    /**
     * Set the AccountAttributes
     * @param AccountAttributes $attributes
     * @param AsyncCallback|NULL $callback
     * @return MnsPromise
     */
    public function setAccountAttributesAsync(AccountAttributes $attributes, AsyncCallback $callback = null)
    {
        $request = new SetAccountAttributesRequest($attributes);
        $response = new SetAccountAttributesResponse();
        return $this->getClient()->sendRequestAsync($request, $response, $callback);
    }

    /**
     * 获取 HTTPClient 实例
     * @return HttpClient
     */
    public function getClient()
    {
        if (!$this->client) {
            $this->client = new HttpClient($this->endpoint, $this->accessId, $this->accessKey, $this->securityToken);
        }
        return $this->client;
    }
}