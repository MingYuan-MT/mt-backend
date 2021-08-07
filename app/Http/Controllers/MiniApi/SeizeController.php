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

    /**
     * @title: 确认抢占信息
     * @path: 
     * @author: EricZhou
     * @param {SeizeService} $service
     * @return {*}
     * @description: 确认抢占信息
     */
    public function confirm(SeizeService $service)
    {
         $params = $this->getParams(
            [
                'metting_id'            => 'required|int',
                'room_id'               => 'required|int',
                'subject'               => 'required|string',
            ],
            [
                'metting_id.required'   => '会议室ID不能为空',
                'metting_id.int'        => '会议室ID必须为数字',
                'room_id.required'      => '会议室ID不能为空',
                'room_id.int'           => '会议室ID必须为数字',
                'subject.required'      => '主题不能为空',
                'subject.string'        => '主题字段数据类型错误',
            ]
        );
        // return response()->json(['code'=>0,'msg'=>'抢占成功']);
        return $service->confirm($params);
    }
}
