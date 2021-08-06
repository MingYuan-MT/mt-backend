<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/3 2:04 下午
 * @Author      : Jade
 */

namespace App\Http\Controllers\MiniApi;

use App\Http\Services\IndexService;

class IndexController extends CommonController
{
    /**
     * @param IndexService $service
     * @return string[]
     */
    public function index(IndexService $service): array
    {
        return $service->index($this->params);
    }
}
