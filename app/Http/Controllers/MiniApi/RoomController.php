<?php
/**
 * @Author: EricZhou
 * @CreateByIde: VsCode
 * @Date: 2021-08-06 22:28:44
 * @Email: mengyilingjian@outlook.com
 * @LastEditTime: 2021-08-07 01:21:12
 * @LastEditors: EricZhou
 * @Description: 会议室
 */
namespace App\Http\Controllers\MiniApi;

use App\Http\Services\RoomService;

class RoomController extends CommonController
{
    /**
     * @title: 会议室
     * @path: v1/room/info
     * @author: EricZhou
     * @param {id} 会议室id
     * @return {json}
     * @description: 
     */
    public function info(RoomService $service){
         $params = $this->getParams(
            [
                'id' => 'required|int'
            ],
            [
                'id.required' => '会议室ID不能为空',
                'id.int' => '会议室ID必须为数字',
            ]
        );
        return $service->info($params);
    }

    
    /**
     * @title: 根据会议室id获取会议室的可抢占信息
     * @path: /v1/room/seizeInfomation
     * @author: EricZhou
     * @param {RoomService} $service
     * @return {*}
     * @description: 
     */
    public function seizeInfomation(RoomService $service){
        $params = $this->getParams(
            [
                'id' => 'required|int'
            ],
            [
                'id.required' => '会议室ID不能为空',
                'id.int' => '会议室ID必须为数字',
            ]
        );
        return $service->seizeInfomation($params);
    }

    /**
     * @title: 更新会议室
     * @path: v1/metting/update
     * @author: EricZhou
     * @param {RoomService} $service
     * @return {*}
     * @description: 
     */
    public function update(RoomService $service){
        
    }

    /**
     * @title: 确认抢占会议室
     * @path: v1/room/seize/confirm
     * @author: EricZhou
     * @param {MettingService} $service
     * @return {*}
     * @description: 
     */
    public function seizeConfirm(RoomService $service){

    }
}