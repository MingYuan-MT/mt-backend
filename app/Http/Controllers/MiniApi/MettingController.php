<?php
/**
 * @Author: EricZhou
 * @CreateByIde: VsCode
 * @Date: 2021-08-06 20:17:55
 * @Email: mengyilingjian@outlook.com
 * @LastEditTime: 2021-08-06 20:41:04
 * @LastEditors: EricZhou
 * @Description: file content
 */
namespace App\Http\Controllers\MiniApi;

use App\Http\Services\MettingService;

class MettingController extends CommonController
{
    /**
     * @title: 会议详情
     * @path: v1/metting/info
     * @author: EricZhou
     * @param {metting_id}
     * @return {json}
     * @description:
     */
    public function info(MettingService $service){
         $params = $this->getParams(
            [
                'id' => 'required|int'
            ],
            [
                'id.required' => '会议ID不能为空',
                'id.int' => '会议ID必须为数字',
            ]
        );
        return $service->info($params);
    }

    /**
     * @title: 更新会议室
     * @path: v1/metting/update
     * @author: EricZhou
     * @param {MettingService} $service
     * @return {*}
     * @description:
     */
    public function update(MettingService $service)
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
        return $service->update($params);
    }

    /**
     * @title: 抢占会议室
     * @path: v1/metting/seize
     * @author: EricZhou
     * @param {MettingService} $service
     * @return {*}
     * @description:
     */
    public function seize(MettingService $service){

    }


    public function save(MettingService $service)
    {
        $params = $this->getParams(
            [
                'room_id' => 'required|int',
                'start_time'  => 'required',
                'end_time'  => 'required',
            ],
            [
                'room_id.required' => '会议室ID不能为空',
                'room_id.int' => '会议室ID必须为数字',
                'start_time.required' => '会议开始时间不能为空',
                'end_time.required' => '会议结束时间不能为空',
            ]
        );
        return $service->save($params);
    }
}
