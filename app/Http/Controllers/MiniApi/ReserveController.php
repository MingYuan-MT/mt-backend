<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/7 3:56 上午
 * @Author      : Jade
 */

namespace App\Http\Controllers\MiniApi;

use App\Http\Controllers\MiniApiController;
use App\Http\Services\ReserveService;
use JetBrains\PhpStorm\ArrayShape;

class ReserveController extends MiniApiController
{
    /**
     * @param ReserveService $service
     * @return array[]
     */
    #[ArrayShape(['data' => "array"])] public function conditionLists(ReserveService $service): array
    {
        $params = $this->getParams(
            [
                'start_time.required',
                'end_time.required',
                'region.required',
                'is_shadow.int',
            ],
            [
                'start_time.required' => '会议开始时间不能为空',
                'end_time.required' => '会议结束时间不能为空',
                'region.required' => '所在区域不能为空',
                'is_shadow.int' => '是否需要投影必须是数字',
            ]
        );
        return $service->lists('condition', $params);
    }

    /**
     * @param ReserveService $service
     * @return array[]
     */
    #[ArrayShape(['data' => "array"])] public function shakeLists(ReserveService $service): array
    {
        $params = $this->getParams(
            [
                'region.required',
            ],
            [
                'region.required' => '所在区域不能为空'
            ]
        );
        $params['start_time'] = date('Y-m-d H:i:s');
        $params['end_time'] =  date("Y-m-d H:i:s", strtotime("+1 hour"));
        return $service->lists('shake', $params);
    }

    /**
     * @param ReserveService $service
     * @return array
     */
    #[ArrayShape(['data' => "array"])] public function voiceLists(ReserveService $service): array
    {
        $params = $this->getParams(
            [
                'text.required',
            ],
            [
                'text.required' => '语音信息不能为空'
            ]
        );
        return $service->lists('voice', $params);
    }

    public function add()
    {

    }

    public function edit()
    {

    }
}
