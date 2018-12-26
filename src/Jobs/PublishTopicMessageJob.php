<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace XuTL\Aliyun\Jobs;

use AliyunMNS\Requests\PublishMessageRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use XuTL\Aliyun\Aliyun;

/**
 * 推送阿里云主题消息
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class PublishTopicMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * 任务可以尝试的最大次数。
     *
     * @var int
     */
    public $tries = 3;

    /**
     * @var string 消息Tag
     */
    protected $tag;

    protected $topic;

    /**
     * @var array 消息体
     */
    protected $messageBody;

    /**
     * Create a new job instance.
     *
     * @param array $messageBody 消息内容
     * @param string $tag 消息标签
     * @param string $topic 消息主题
     */
    public function __construct($messageBody, $tag = null, $topic = null)
    {
        $this->tag = $tag;
        $this->messageBody = $messageBody;
        $this->topic = $topic;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        /** @var \XuTL\Aliyun\Services\Mns $mns */
        $mns = Aliyun::get('mns');
        $request = new PublishMessageRequest(json_encode($this->messageBody), $this->tag);
        $mns->topic()->publishMessage($request);
    }
}