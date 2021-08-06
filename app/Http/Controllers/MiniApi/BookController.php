<?php

namespace App\Http\Controllers\MiniApi;

use App\Http\Services\IndexService;
use App\Http\Services\RoomService;

class BookController extends CommonController
{

    /**
     * 条件预订
     * @param RoomService $service
     * @return string[]
     */
    public function condition(RoomService $service)
    {
        $params = $this->getParams(
            [
                'start_time.required',
                'end_time.required' => '会议结束时间不能为空',
                'region.required' => '所在区域不能为空',
                'is_shadow.int' => '是否需要投影必须是数字',
            ],
            [
                'start_time.required' => '会议开始时间不能为空',
                'end_time.required' => '会议结束时间不能为空',
                'region.required' => '所在区域不能为空',
                'is_shadow.int' => '是否需要投影必须是数字',
            ]
        );
        return $service->list($params);
    }

    /**
     * 摇一摇预订
     * @param RoomService $service
     * @return string[]
     */
    public function shake(RoomService $service)
    {
        $params = $this->getParams(
            [
                'region.required' => '所在区域不能为空',
            ]
        );
        $params['start_time'] = date('Y-m-d H:i:s');
        $params['end_time'] =  date("Y-m-d H:i:s", strtotime("+1 hour"));
        return $service->list($params);
    }
}
