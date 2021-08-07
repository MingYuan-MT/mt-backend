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
use App\Models\ReserveRecord;
use Exception;
use PDOException;
use Illuminate\Support\Facades\DB;

class SeizeService
{
    /**
     * 根据会议室id获取会议室的可抢占信息
     * @param array $data
     * @return array
     */
    public function mettingInfo(array $data = [])
    {
        $res = [];
        try{
            $rquery     = new Room();
            $room_data  = $rquery->info(['id' => $data['id']],['id as room_id','name','floor','capacity','uses']);
            if(empty($room_data)){
                client_error('会议室信息不存在！');
            }

            $mquery     = new Metting();
            $condition  = ['room_id'=> $room_data['room_id']];
            $fileds     = ['id as metting_id','subject','moderator','metting_start_time','metting_end_time','status as metting_status'];
            $meet_data  = $mquery->info($condition, $fileds);
            if(empty($meet_data)){
                client_error('会议信息不存在！');
            }
            $res = array_merge($room_data, $meet_data);
            $res['room_uses'] = Room::$room_uses_map[$room_data['uses']] ?? '';
            $seize_result = $this->seizeRulesValidate($res['metting_start_time'],$res['metting_end_time']);
            $res['seize_code'] = $seize_result['code'];
            $res['seize_msg'] = $seize_result['msg'];
            if(!$seize_result['res']){
                if($seize_result['code'] == 3){
                    return $res;
                }
                if($seize_result['code'] == 4 && in_array($meet_data['metting_status'],[
                    Metting::METTING_STATUS_DEFAULT,
                    Metting::METTING_STATUS_END,
                    Metting::METTING_STATUS_CANCEL])){
                    return $res;
                }
            }
            $now = date('Y-m-d H:i:s',time());
            if($now > $meet_data['metting_start_time']){
                $res['seize_start_time']    = $now;
                $res['seize_end_time']      = $meet_data['metting_end_time'];
            }
        }catch(\Exception $e){
            server_error('抢占会议室信息获取失败:' . $e->getMessage());
        }
        return $res;
    }

    /**
     * 会议室抢占时间校验
     * @param $metting_start_time
     * @return array
     */
    private function seizeRulesValidate($metting_start_time, $metting_end_time): array
    {
        $res = [
            'res' =>true,
            'code' => 0,
            'msg' => '可抢占！'
        ];
        try{
            // 获取配置
            $cquery = new Config();
            $config_data = $cquery->info('seize_rules');
            if(empty($config_data)){
                client_error('seize_rules配置不存在！');
            }
            $config = json_decode($config_data['value'], true);
            $rule_week = $config['weeks'];
            $rule_hours = $config['hours'];

            // 星期校验
            $week = date('w');
            if(array_search($week,$rule_week) === false){
                return [
                    'res' => false,
                    'code' => 1,
                    'msg' => '不在规定的可抢占[星期]内!'
                ];
            }

            // 时间段校验
            foreach ($rule_hours as $hour){
                $between = explode(",",$hour);
                if(date('H:i:s', time()) >= $between[0] && date('H:i:s', time()) <= $between[1]){
                    break;
                }
                return [
                    'res' => false,
                    'code' => 2,
                    'msg' => '不在规定的可抢占[时间段]内!'
                ];
            }

            if (strtotime($metting_start_time) > time() && strtotime($metting_start_time) - time() < 15 * 60) {
                return [
                    'res' => false,
                    'code' => 3,
                    'msg' => '会议即将开始，不可抢占！'
                ];
            }

            if(time() > strtotime($metting_end_time)){
                return [
                    'res' => false,
                    'code' => 4,
                    'msg' => '会议室空闲中，请直接预定！'
                ];
            }
        }catch(Exception $e){
            server_error('抢占会议室配置信息获取失败！' . $e->getMessage());
        }
        return $res;
    }

    /**
     * @title: 会议室确认抢占
     * @path: 
     * @author: EricZhou
     * @param {*}
     * @return {*}
     * @description: 
     */
    public function confirm(array $data = []){
        $room_id = $data['room_id'];
        $metting_id = $data['metting_id'];
        DB::beginTransaction();
        try{
            DB::commit();
        }catch(PDOException $ex){
            DB::rollBack();
        }
    }
}
