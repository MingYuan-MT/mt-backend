<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/7 3:52 上午
 * @Author      : Jade
 */

namespace App\Http\Controllers\MiniApi;

use App\Http\Controllers\MiniApiController;
use App\Http\Services\MyService;

class MyController extends MiniApiController
{

    public function reserve(MyService $service)
    {
        return $service->records();
    }

    /**
     * @title: 查看用户的资源浪费记录
     * @path: 
     * @author: EricZhou
     * @param {MyService} $service
     * @return {*}
     * @description: 
     */
    public function useLog(MyService $service)
    {
        return $service->useLogRecords();
    }
}
