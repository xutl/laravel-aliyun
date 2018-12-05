<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace XuTL\Aliyun;

use Closure;
use InvalidArgumentException;
use XuTL\Aliyun\Services\Cdn;
use XuTL\Aliyun\Services\CloudPush;
use XuTL\Aliyun\Services\Dns;
use XuTL\Aliyun\Services\Domain;
use XuTL\Aliyun\Services\Mns;

/**
 * Class Aliyun
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class AliyunManage
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * @var string 阿里云AccessKey ID
     */
    protected $accessId;

    /**
     * @var string AccessKey
     */
    protected $accessKey;

    /**
     * The array of resolved services drivers.
     *
     * @var AliyunInterface[]
     */
    protected $services = [];

    /**
     * The registered custom driver creators.
     *
     * @var array
     */
    protected $customCreators = [];

    /**
     * Create a new filesystem manager instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
        $this->accessId = $this->app['config']["aliyun.access_id"];
        $this->accessKey = $this->app['config']["aliyun.access_key"];
    }

    /**
     * Get the aliyun service configuration.
     *
     * @param  string $name
     * @return array
     */
    protected function getConfig($name)
    {
        $config = $this->app['config']["aliyun.services.{$name}"];
        if (empty ($config['access_id'])) {
            $config['access_id'] = $this->accessId;
        }
        if (empty ($config['access_key'])) {
            $config['access_key'] = $this->accessKey;
        }
        return $config;
    }

    /**
     * Attempt to get the disk from the local cache.
     *
     * @param  string $name
     * @return AliyunInterface
     */
    public function get($name)
    {
        return $this->services[$name] ?? $this->resolve($name);
    }

    /**
     * Set the given service instance.
     *
     * @param  string $name
     * @param  mixed $service
     * @return $this
     */
    public function set($name, $service)
    {
        $this->services[$name] = $service;
        return $this;
    }

    /**
     * Resolve the given disk.
     *
     * @param  string $name
     * @return AliyunInterface
     *
     * @throws \InvalidArgumentException
     */
    protected function resolve($name)
    {
        $config = $this->getConfig($name);

        if (isset($this->customCreators[$config['driver']])) {
            return $this->callCustomCreator($config);
        }

        $driverMethod = 'create' . ucfirst($config['driver']) . 'Service';

        if (method_exists($this, $driverMethod)) {
            return $this->{$driverMethod}($config);
        } else {
            throw new InvalidArgumentException("Driver [{$config['driver']}] is not supported.");
        }
    }

    /**
     * 创建CDN服务
     * @param array $config
     * @return AliyunInterface
     */
    public function createCdnService(array $config)
    {
        return new Cdn(['accessId' => $config['access_id'], 'accessKey' => $config['access_key']]);
    }

    /**
     * 创建 CloudPush 服务
     * @param array $config
     * @return CloudPush
     */
    public function createCloudPushService(array $config)
    {
        return new CloudPush([
            'accessId' => $config['access_id'],
            'accessKey' => $config['access_key'],
            'regionId' => $config['region_id'] ?? 'cn-hangzhou',
        ]);
    }

    /**
     * 创建 MNS 服务
     * @param array $config
     * @return Mns
     */
    public function createMnsService(array $config)
    {
        return new Mns([
            'endpoint' => $config['endpoint'],
            'accessId' => $config['access_id'],
            'accessKey' => $config['access_key'],
            'securityToken' => $config['securityToken'] ?? null,
        ]);
    }

    /**
     * 创建 DNS 服务
     * @param array $config
     * @return Dns
     */
    public function createDnsService(array $config)
    {
        return new Dns([
            'accessId' => $config['access_id'],
            'accessKey' => $config['access_key'],
        ]);
    }

    /**
     * 创建 Domain 服务
     * @param array $config
     * @return Domain
     */
    public function createDomainService(array $config)
    {
        return new Domain([
            'accessId' => $config['access_id'],
            'accessKey' => $config['access_key'],
        ]);
    }

    /**
     * Register a custom driver creator Closure.
     *
     * @param  string $driver
     * @param  \Closure $callback
     * @return $this
     */
    public function extend($driver, Closure $callback)
    {
        $this->customCreators[$driver] = $callback;
        return $this;
    }

    /**
     * Call a custom driver creator.
     *
     * @param  array $config
     * @return AliyunInterface
     */
    protected function callCustomCreator(array $config)
    {
        $driver = $this->customCreators[$config['driver']]($this->app, $config);
        return $driver;
    }
}