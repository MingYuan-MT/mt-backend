<?php
/**
 * @Author: EricZhou
 * @CreateByIde: VsCode
 * @Date: 2021-08-06 20:20:53
 * @Email: mengyilingjian@outlook.com
 * @LastEditTime: 2021-08-07 11:43:44
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
     * @author: EricZhou
     * @param {*} $data 字段与值对应
     * @return Metting
     * @description:
     */
    public function info($data = []){
        $res = [];
        try{
            $mquery = new Metting();
            $meet_data = $mquery->info('id', $data['id']);
            if(empty($meet_data)){
                client_error('会议信息不存在！');
            }
            $rquery = new Room();
            $room_data = $rquery->info('id', $meet_data['room_id'], 'name');
            if(empty($room_data)){
                client_error('会议室信息不存在！');
            }
            $res = [
                'room_name' => $room_data['name'],
                'subject' => $meet_data['subject'],
                'metting_moderator' =>$meet_data['moderator'],
                'metting_strat_time' => $meet_data['metting_strat_time'],
                'metting_end_time' => $meet_data['metting_end_time']
            ];
        }catch(\Exception $e){
            server_error('会议详情获取失败:'.$e->getMessage());
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
            $mquery     = new Metting();
            $meet_data  = $mquery->info('id', $data['id']);
            if(empty($meet_data)){
                client_error('会议信息不存在！');
            }
            $rquery     = new Room();
            $room_data  = $rquery->info('id', $meet_data['room_id']);
            if(empty($room_data)){
                client_error('会议室信息不存在！');
            }
            $res = [
                'room_floor'    => $room_data['floor'],
                'room_name'     => $room_data['name'],
                'room_capacity' => $room_data['capacity'],
                'room_uses'     => Room::$room_uses_map[$room_data['uses']] ?? '',
                'subject'       => $meet_data['subject'],
                'metting_moderator'     =>$meet_data['moderator'],
                'metting_strat_time'    => $meet_data['metting_strat_time'],
                'metting_end_time'      => $meet_data['metting_end_time']
            ];
        }catch(\Exception $e){
            server_error('会议详情获取失败:'.$e->getMessage());
        }
        return $res;
    }

    public function getRoomIds($params)
    {
        $where =  ['status' => 1];
        if (isset($params['start_time']) && $params['start_time']) {
            $where[] = ['metting_strat_time','>',$params['start_time']];
            $where[] = ['metting_end_time','<',$params['start_time']];
        }
        $model = new Metting();
        return $model->getRoomIds($where);
    }

    public function save($params)
    {


    }

    public function update($params)
    {
        try {
            $metting = Metting::find($params['id']);
            if($metting){
                $metting->update([
                    'subject' => $params['subject'],
                    'moderator' => $params['moderator'],
                    'updated_by' => '周磊',
                ]);
                return ['message' =>'更新成功'];
            }
            return ['message' =>'会议不存在'];
        }catch(\Exception $e){
            server_error('会议更新失败:'.$e->getMessage());
        }
    }
}
