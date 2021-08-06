<?php
/**
 * @Author: EricZhou
 * @CreateByIde: VsCode
 * @Date: 2021-08-06 20:20:53
 * @Email: mengyilingjian@outlook.com
 * @LastEditTime: 2021-08-07 01:05:08
 * @LastEditors: EricZhou
 * @Description: 会议服务
 */
namespace App\Http\Services;

use App\Models\Metting;
use App\Models\Room;

class MettingService extends IndexService
{
    /**
     * @title: 
     * @author: EricZhou
     * @param {*} $data 字段与值对应
     * @return Metting
     * @description: 
     */
    public function info($data = []){
        $res = [];
        try{
            $mquery = new Metting();
            $meetData = $mquery->info('id', $data['id']);
            if(empty($meetData)){
                abort(self::RET_FAIL, '会议信息不存在！');
            }
            $rquery = new Room();
            $roomData = $rquery->info('id', $meetData['room_id'], 'name');
            if(empty($roomData)){
                abort(self::RET_FAIL, '会议室信息不存在！');
            }
            $res = [
                'room_name' => $roomData['name'],
                'subject' => $meetData['subject'],
                'metting_moderator' =>$meetData['moderator'],
                'metting_strat_time' => $meetData['metting_strat_time'],
                'metting_end_time' => $meetData['metting_end_time']
            ];
        }catch(\Exception $e){
            abort(self::RET_SERVER_FAIL,'会议详情获取失败:'.$e->getMessage());
        }
        return $res;
    }

    /**
     * @title: 获取会议及会议室的关联信息
     * @author: EricZhou
     * @param {*} $data
     * @return {*}
     * @description: 
     */
    public function infomation($data = []){
        $res = [];
        try{
            $mquery = new Metting();
            $meetData = $mquery->info('id', $data['id']);
            if(empty($meetData)){
                abort(self::RET_FAIL, '会议信息不存在！');
            }
            $rquery = new Room();
            $roomData = $rquery->info('id', $meetData['room_id']);
            if(empty($roomData)){
                abort(self::RET_FAIL, '会议室信息不存在！');
            }
            $res = [
                'room_floor' => $roomData['floor'],
                'room_name' => $roomData['name'],
                'room_capacity' => $roomData['capacity'],
                'room_uses' => isset(Room::$room_uses_map[$roomData['uses']]) ? : '',
                'subject' => $meetData['subject'],
                'metting_moderator' =>$meetData['moderator'],
                'metting_strat_time' => $meetData['metting_strat_time'],
                'metting_end_time' => $meetData['metting_end_time']
            ];
        }catch(\Exception $e){
            abort(self::RET_SERVER_FAIL,'会议详情获取失败:'.$e->getMessage());
        }
        return $res;
    }
}