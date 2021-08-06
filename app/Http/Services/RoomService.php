<?php
/**
 * @Author: EricZhou
 * @CreateByIde: VsCode
 * @Date: 2021-08-06 20:20:53
 * @Email: mengyilingjian@outlook.com
 * @LastEditTime: 2021-08-06 22:35:39
 * @LastEditors: EricZhou
 * @Description: 会议室服务
 */
namespace App\Http\Services;

use App\Models\Metting;
use App\Models\Room;

class RoomService
{
    /**
     * @title: 
     * @path: 
     * @author: EricZhou
     * @param {*} $data 字段与值对应
     * @return Metting
     * @description: 
     */
    public function info($data = []){
        $rquery = new Room();
        $roomData = $rquery->info('id', $data['id']);
        return $roomData;
    }
}