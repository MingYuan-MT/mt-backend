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
                'is_need_sign.int',
            ],
            [
                'start_time.required' => '会议开始时间不能为空',
                'end_time.required' => '会议结束时间不能为空',
                'region.required' => '所在区域不能为空',
                'is_shadow.int' => '是否需要投影必须是数字',
                'is_need_sign.int' => '是否需要签到必须是数字',
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

    public function add(ReserveService $service)
    {
        $params = $this->getParams(
            [
                'room_id' => 'required|int',
                'start_time'  => 'required',
                'end_time'  => 'required',
                'is_need_sign'  => 'required',
            ],
            [
                'room_id.required' => '会议室ID不能为空',
                'room_id.int' => '会议室ID必须为数字',
                'start_time.required' => '会议开始时间不能为空',
                'end_time.required' => '会议结束时间不能为空',
                'is_need_sign.required' => '是否需要签到不能为空'
            ]
        );
        return $service->add($params);
    }

    public function edit(ReserveService $service)
    {
        $params = $this->getParams(
            [
                'id' => 'required|int',
                'subject'  => 'required',
                'moderator'  => 'required',
            ],
            [
                'id.required' => '会议ID不能为空',
                'id.int' => '会议ID必须为数字',
                'subject.required' => '会议主题不能为空',
                'moderator.required' => '会议主主持人不能为空',
            ]
        );
        return $service->edit($params);
    }
}
