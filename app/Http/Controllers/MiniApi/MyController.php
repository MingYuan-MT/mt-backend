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
use JetBrains\PhpStorm\ArrayShape;

class MyController extends MiniApiController
{
    /**
     * @param MyService $service
     * @return mixed
     */
    public function reserve(MyService $service): mixed
    {
        return $service->records();
    }

    /**
     * @title: 查看用户的资源浪费记录
     * @path:
     * @param MyService $service
     * @return array {*}
     * @description:
     * @author: EricZhou
     */
    #[ArrayShape(['record_count' => "array", 'list' => "\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection"])] public function useLog(MyService $service): array
    {
        return $service->useLogRecords();
    }
}
