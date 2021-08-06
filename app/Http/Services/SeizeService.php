<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/7 4:43 上午
 * @Author      : Jade
 */

namespace App\Http\Services;

use App\Models\Config;
use App\Models\Metting;
use App\Models\Room;
use Exception;

class SeizeService
{
    /**
     * 根据会议室id获取会议室的可抢占信息
     * @param array $data
     * @return array
     */
    public function mettingInfo(array $data = []): array
    {
        $res = [];
        try{
            $rquery = new Room();
            $roomData = $rquery->info(['id' => $data['id']],['id as room_id','name','floor','capacity','uses']);
            if(empty($roomData)){
                abort(400, '会议室信息不存在！');
            }

            $mquery = new Metting();
            $meetData = $mquery->info(['room_id'=> $roomData['room_id']], ['id as metting_id','subject','moderator','metting_end_time','metting_start_time']);
            if(empty($meetData)){
                abort(400, '会议信息不存在！');
            }
            $res = array_merge($roomData, $meetData);
            $res['room_uses'] = isset(Room::$room_uses_map[$roomData['uses']]) ? : '';
            $result = $this->seizeRulesValidate($res['metting_start_time']);
            if(!$result['res']){
                if($result['code'] == 1){
                    return $res;
                }
                abort(500, $result['msg']);
            }
            $now = date('H:i:s',time());
            if($meetData['metting_start_time'] > $now){
                $res['seize_start_time'] = $now;
                $res['seize_end_time'] = $meetData['metting_start_time'];
            }
            $mettingSeizeTime = date('Y-m-d H:i:s', time());
            $res['metting_seize_time'] = $mettingSeizeTime;
        }catch(Exception $e){
            abort(500, '抢占会议室信息获取失败:'.$e->getMessage());
        }
        return $res;
    }

    /**
     * 会议室抢占时间校验
     * @param $metting_start_time
     * @return array
     */
    private function seizeRulesValidate($metting_start_time): array
    {
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
                abort(500,'seize_rules配置不存在！');
            }
            $config = json_decode($cData['value'], true);
            $ruleWeek = $config['weeks'];
            $ruleHours = $config['hours'];
            $time = strtotime($metting_start_time) - time();

            if($time >= 10 * 60 === false){
                return [
                    'res' => false,
                    'code' => 1,
                    'msg' => '会议即将开始，不可抢占！'
                ];
            }

            // 星期校验
            $week = date('w');
            if(array_search($week,$ruleWeek) === false){
                return [
                    'res' => false,
                    'code' => 2,
                    'msg' => '不在规定的可抢占[星期]内!'
                ];
            }

            // 时间段校验
            foreach ($ruleHours as $hour){
                $between = explode(",",$hour);
                if(!(date('H:i:s', time()) >= $between[0] && date('H:i:s', time()) <= $between[1])){
                    return [
                        'res' => false,
                        'code' => 3,
                        'msg' => '不在规定的可抢占[时间段]内!'
                    ];
                }
            }
        }catch(Exception $e){
            abort(500,'抢占会议室配置信息获取失败！'.$e->getMessage());
        }
        return $res;
    }
}
