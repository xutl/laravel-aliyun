<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace XuTL\Aliyun;

use Closure;
use Illuminate\Support\Arr;
use InvalidArgumentException;

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
     * The array of resolved services drivers.
     *
     * @var array
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
    }

    /**
     * Get the aliyun service configuration.
     *
     * @param  string $name
     * @return array
     */
    protected function getConfig($name)
    {
        return $this->app['config']["aliyun.services.{$name}"];
    }

    /**
     * Attempt to get the disk from the local cache.
     *
     * @param  string $name
     * @return AliyunInterface
     */
    protected function get($name)
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
        $client = new Cdn($config['endpoint'], [
            'username' => $config['username'], 'apiKey' => $config['key'],
        ], $config['options'] ?? []);

        return $client;
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