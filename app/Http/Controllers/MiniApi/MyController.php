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

    public function useLog(MyService $service)
    {

    }
}
