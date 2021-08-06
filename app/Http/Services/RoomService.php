<?php
/**
 * @Author: EricZhou
 * @CreateByIde: VsCode
 * @Date: 2021-08-06 20:20:53
 * @Email: mengyilingjian@outlook.com
 * @LastEditTime: 2021-08-07 04:15:25
 * @LastEditors: EricZhou
 * @Description: 会议室服务
 */
namespace App\Http\Services;

use App\Models\Metting;
use App\Models\Room;
use App\Models\Region;
use App\Models\Config;

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
            $roomData = $rquery->info(['id' => $data['id']]);
            if(empty($roomData)){
                abort(self::RET_FAIL, '会议室信息不存在！');
            }
            $reQuery = new Region();
            $reData = $reQuery->info(['id' => $roomData['region_id']], ['area_name']);
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
            $roomData = $rquery->info(['id' => $data['id']],['id as room_id','name','floor','capacity','uses']);
            if(empty($roomData)){
                abort(self::RET_FAIL, '会议室信息不存在！');
            }
            
            $mquery = new Metting();
            $meetData = $mquery->info(['room_id'=> $roomData['room_id']], ['id as metting_id','subject','moderator','metting_end_time','metting_start_time']);
            if(empty($meetData)){
                abort(self::RET_FAIL, '会议信息不存在！');
            }
            $res = array_merge($roomData, $meetData);
            $res['room_uses'] = isset(Room::$room_uses_map[$roomData['uses']]) ? : '';
            $result = $this->seizeRulesValidate($res['metting_start_time']);
            // dd($result);
            if(!$result['res']){
                if($result['code'] == 1){
                    return $res;
                }
                abort(self::RET_FAIL,$result['msg']);
            }
            $now = date('H:i:s',time());
            if($meetData['metting_start_time'] > $now){
                $res['seize_start_time'] = $now;
                $res['seize_end_time'] = $meetData['metting_start_time'];
            }
            $mettingSeizeTime = date('Y-m-d H:i:s', time());
            $res['metting_seize_time'] = $mettingSeizeTime;
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

    /**
     * @title: 会议室抢占时间校验
     * @path: 
     * @author: EricZhou
     * @param {*}
     * @return {bool}
     * @description: 
     */
    public function seizeRulesValidate($metting_start_time):array {
        $res = [
            'res' =>true,
            'code' => 0,
            'msg' => '可抢占！'
        ];
        try{
            // 获取配置
            $cquery = new Config();
            $cData = $cquery->info('seize_rules');
            if(empty($cData)){
                abort(self::RET_FAIL,'seize_rules配置不存在！');
            }
            $config = json_decode($cData['value'], true);
            $ruleWeek = $config['weeks'];
            $ruleHours = $config['hours'];
            $time = strtotime($metting_start_time) - time();
            
            if($time >= 10 * 60 === false){
                $res = [
                    'res' => false,
                    'code' => 1,
                    'msg' => '会议即将开始，不可抢占！'
                ];
                return $res;
            }

            // 星期校验
            $week = date('w');
            if(array_search($week,$ruleWeek) === false){
                $res = [
                    'res' => false,
                    'code' => 2,
                    'msg' => '不在规定的可抢占[星期]内!'
                ];
                return $res;
            }

            // 时间段校验
            foreach ($ruleHours as $hour){
                $between = explode(",",$hour);
                if(!(date('H:i:s', time()) >= $between[0] && date('H:i:s', time()) <= $between[1])){
                    $res = [
                        'res' => false,
                        'code' => 3,
                        'msg' => '不在规定的可抢占[时间段]内!'
                    ];
                    return $res;
                }
            }
        }catch(\Exception $e){
            abort(self::RET_FAIL,'抢占会议室配置信息获取失败！'.$e->getMessage());
        }
        return $res;
    }
}
