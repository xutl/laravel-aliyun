<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace XuTL\Aliyun;

use Illuminate\Support\ServiceProvider;

/**
 * Class AliyunServiceProvider
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class AliyunServiceProvider extends ServiceProvider
{
    public function boot()
    {
    }

    public function register()
    {
        $this->registerConfig();
    }

    /**
     * Register the configuration.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $source = realpath($raw = __DIR__ . '/../config/aliyun.php') ?: $raw;

        if ($this->app->runningInConsole()) {
            $this->publishes([
                $source => config_path('aliyun.php'),
            ], 'aliyun-config');
        }
    }
}