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

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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
        $model = new  Metting();
        $model->room_id = $params['room_id'];
        $model->subject = '张力'.'预订的会议室';
        $model->moderator = '张力';
        $model->metting_strat_time = $params['start_time'];
        $model->metting_end_time = $params['end_time'];
        $model->status = 0;
        $model->save();
        return ['message' => '预订成功'];
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
            abort(self::RET_SERVER_FAIL,'会议更新失败:'.$e->getMessage());
        }
    }
}
