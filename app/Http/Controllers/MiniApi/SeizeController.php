<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/7 4:43 上午
 * @Author      : Jade
 */

namespace App\Http\Controllers\MiniApi;

use App\Http\Controllers\MiniApiController;
use App\Http\Services\SeizeService;

class SeizeController extends MiniApiController
{
    /**
     * @param SeizeService $service
     * @return array
     */
    public function mettingInfo(SeizeService $service): array
    {
        $params = $this->getParams(
            [
                'id' => 'required|int'
            ],
            [
                'id.required' => '会议室ID不能为空',
                'id.int' => '会议室ID必须为数字',
            ]
        );
        return $service->mettingInfo($params);
    }

    public function confirm(SeizeService $service)
    {

    }
}
