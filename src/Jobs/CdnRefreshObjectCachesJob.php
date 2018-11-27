<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace XuTL\Aliyun\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use XuTL\Aliyun\Aliyun;

/**
 * Class CdnRefreshObjectCachesJob
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class CdnRefreshObjectCachesJob implements ShouldQueue
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

    public $objectType;

    /**
     * Create a new job instance.
     *
     * @param string|array $urls
     * @param $objectType
     */
    public function __construct($urls, $objectType = 'File')
    {
        if (is_string($urls)) {
            $this->urls = [$urls];
        } else {
            $this->urls = $urls;
        }
        $this->objectType = $objectType;
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
            $cdn->RefreshObjectCaches([
                'ObjectPath' => implode('\n', $this->urls)
            ]);
        } catch (\Exception $exception) {

        }
    }
}