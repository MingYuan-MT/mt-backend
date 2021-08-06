<?php
/**
 * @Author: EricZhou
 * @CreateByIde: VsCode
 * @Date: 2021-08-06 20:20:53
 * @Email: mengyilingjian@outlook.com
 * @LastEditTime: 2021-08-07 01:05:21
 * @LastEditors: EricZhou
 * @Description: 会议室服务
 */
namespace App\Http\Services;

use App\Models\Metting;
use App\Models\Room;
use App\Models\Region;

class RoomService extends IndexService
{
    /**
     * @title:
     * @path:
     * @author: EricZhou
     * @param {*} $data 字段与值对应
     * @return array
     * @description:
     */
    public function info($data = []){
        $roomData = [];
        try{
            $rquery = new Room();
            $roomData = $rquery->info('id', $data['id']);
            if(empty($roomData)){
                abort(self::RET_FAIL, '会议室信息不存在！');
            }
            $reQuery = new Region();
            $reData = $reQuery->info('id', $roomData['region_id'], ['area_name']);
            $roomData['region_name'] = $reData['area_name'];
            $roomData['room_uses'] = isset(Room::$room_uses_map[$roomData['uses']]) ? : '';
        }catch(\Exception $e){
            abort(self::RET_SERVER_FAIL, '会议室信息获取失败:'.$e->getMessage());
        }
        return $roomData;
    }

    /**
     * @title:
     * @path:
     * @author: EricZhou
     * @param {*}
     * @return {*}
     * @description: 根据会议室id获取会议室的可抢占信息
     */
    public function seizeInfomation($data = []) {
        $res = [];
        try{
            $rquery = new Room();
            $roomData = $rquery->info('id', $data['id'],['id as room_id','name','floor','capacity','uses']);
            if(empty($roomData)){
                abort(self::RET_FAIL, '会议室信息不存在！');
            }
            $mquery = new Metting();
            $meetData = $mquery->info('room_id', $roomData['room_id'], ['id as metting_id','subject','moderator','metting_end_time']);
            if(empty($meetData)){
                abort(self::RET_FAIL, '会议信息不存在！');
            }
            $res = array_merge($roomData, $meetData);
            $res['room_uses'] = isset(Room::$room_uses_map[$roomData['uses']]) ? : '';
        }catch(\Exception $e){
            abort(self::RET_SERVER_FAIL, '抢占会议室信息获取失败:'.$e->getMessage());
        }
        return $res;
    }

    /**
     * @param $params
     * @return string[]
     */
    public function list($params): array
    {
        //获取当前时间段正在使用的会议室id
        $where = [];
        $mettingService = new MettingService();
        $roomIds = $mettingService->getRoomIds($params);
        if ($roomIds) {
            $where[] = ['id', 'not in', $params['room_id']];
        }
        if (isset($params['region']) && $params['region']) {
            $where['region_id'] = 26691;
        }
        if (isset($params['is_shadow'])) {
            $where['projection_mode'] = $params['is_shadow'] ? [1, 2, 3] : 0;
        }
        $model = new Room();
        $rooms = $model->getList($where);
        $rooms = $this->dealData($rooms);
        return ['data' => $rooms];
    }

    private function dealData($rooms)
    {
        $result = [];
        foreach ($rooms as &$room) {
            $result[$room['floor'].'F'][] = $room;
        }
        return $result;
    }
}
