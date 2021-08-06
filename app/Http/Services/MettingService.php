<?php
/**
 * @Author: EricZhou
 * @CreateByIde: VsCode
 * @Date: 2021-08-06 20:20:53
 * @Email: mengyilingjian@outlook.com
 * @LastEditTime: 2021-08-06 22:22:25
 * @LastEditors: EricZhou
 * @Description: 会议服务
 */
namespace App\Http\Services;

use App\Models\Metting;
use App\Models\Room;

class MettingService
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
        $mquery = new Metting();
        $meetData = $mquery->info('id', $data['id']);

        $rquery = new Room();
        $roomData = $rquery->info('id', $meetData['room_id'], 'name');
        $data = [
            'room_name' => $roomData['name'],
            'subject' => $meetData['subject'],
            'metting_moderator' =>$meetData['moderator'],
            'metting_strat_time' => $meetData['metting_strat_time'],
            'metting_end_time' => $meetData['metting_end_time']
        ];
        return $data;
    }
}