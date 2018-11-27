<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace XuTL\Aliyun\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use XuTL\Aliyun\Aliyun;

/**
 * Cdn资源预热
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class CdnPushObjectCacheJob
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * 任务可以尝试的最大次数。
     *
     * @var int
     */
    public $tries = 3;

    /**
     * @var array
     */
    public $urls;

    /**
     * @var string 内容区域
     */
    public $area;

    /**
     * Create a new job instance.
     *
     * @param string|array $urls
     * @param string $area
     */
    public function __construct($urls, $area = 'domestic')
    {
        if (is_string($urls)) {
            $this->urls = [$urls];
        } else {
            $this->urls = $urls;
        }
        foreach ($this->urls as $key => $url) {
            $url = $this->parseUrl($url);
            $this->urls[$key] = $url;
        }

        $this->area = $area;
    }

    public function parseUrl($url)
    {
        $u = parse_url($url);
        if ($u) {
            $url = $u['host'];
            if (!isset($u['path'])) {
                $u['path'] = '/';
            }
            $url = $url . $u['path'];
            if (isset($u['query'])) {
                $url = $url . $u['query'];
            }
            return $url;
        }
        return $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        try {
            /** @var \XuTL\Aliyun\Services\Cdn $cdn */
            $cdn = Aliyun::get('cdn');
            $cdn->PushObjectCache([
                'ObjectPath' => implode("\n", $this->urls),
                'Area' => $this->area
            ]);
        } catch (\Exception $exception) {

        }
    }
}