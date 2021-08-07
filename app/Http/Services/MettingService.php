<?php
/**
 * @Author: EricZhou
 * @CreateByIde: VsCode
 * @Date: 2021-08-06 20:20:53
 * @Email: mengyilingjian@outlook.com
 * @LastEditTime: 2021-08-07 11:27:25
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
            $meetData = $mquery->info('id', $data['id']);
            if(empty($meetData)){
                client_error('会议信息不存在！');
            }
            $rquery = new Room();
            $roomData = $rquery->info('id', $meetData['room_id'], 'name');
            if(empty($roomData)){
                client_error('会议室信息不存在！');
            }
            $res = [
                'room_name' => $roomData['name'],
                'subject' => $meetData['subject'],
                'metting_moderator' =>$meetData['moderator'],
                'metting_strat_time' => $meetData['metting_strat_time'],
                'metting_end_time' => $meetData['metting_end_time']
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
            $mquery = new Metting();
            $meetData = $mquery->info('id', $data['id']);
            if(empty($meetData)){
                client_error('会议信息不存在！');
            }
            $rquery = new Room();
            $roomData = $rquery->info('id', $meetData['room_id']);
            if(empty($roomData)){
                client_error('会议室信息不存在！');
            }
            $res = [
                'room_floor' => $roomData['floor'],
                'room_name' => $roomData['name'],
                'room_capacity' => $roomData['capacity'],
                'room_uses' => Room::$room_uses_map[$roomData['uses']] ? : '',
                'subject' => $meetData['subject'],
                'metting_moderator' =>$meetData['moderator'],
                'metting_strat_time' => $meetData['metting_strat_time'],
                'metting_end_time' => $meetData['metting_end_time']
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
