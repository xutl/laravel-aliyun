<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace XuTL\Aliyun;

use Illuminate\Support\Facades\Facade;
use XuTL\Aliyun\AliyunManage;

/**
 * Class Sms
 * @mixin AliyunManage
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Aliyun extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'aliyun';
    }
}