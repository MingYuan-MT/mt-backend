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
use App\Models\User;
use App\Library\WeChat\MiniProgram;
use Illuminate\Support\Facades\Storage;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
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
        try {
            $rquery     = new Room();
            $room_data  = $rquery->info(['id' => $data['id'], 'is_deleted' => 0], ['id as room_id','name','floor','capacity','uses','remark']);
            if (empty($room_data)) {
                client_error('会议室信息不存在！');
            }

            $mquery     = new Metting();
            $condition  = ['room_id'=> $room_data['room_id'], 'is_deleted' => 0];
            $fileds     = ['id as metting_id','subject','moderator','metting_start_time','metting_end_time','status as metting_status'];
            $meet_data  = $mquery->info($condition, $fileds);
            if (empty($meet_data)) {
                client_error('会议信息不存在！');
            }
            $res = array_merge($room_data, $meet_data);
            $res['room_uses'] = Room::$room_uses_map[$room_data['uses']] ?? '';

            $seize_result = $this->seizeRulesValidate($res['metting_start_time'], $res['metting_end_time']);
            $res['seize_code'] = $seize_result['code'];
            $res['seize_msg'] = $seize_result['msg'];
            if (($seize_result['code'] != 0) ||
                ($seize_result['code'] == 4 && in_array($meet_data['metting_status'], [
                Metting::METTING_STATUS_DEFAULT,
                Metting::METTING_STATUS_END,
                Metting::METTING_STATUS_CANCEL]))) {
                return $res;
            }
        } catch (\Exception $e) {
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
            'msg' => '可抢占！',
            'seize_start_time' => '',
            'seize_end_time' => '',
        ];

        try {
            // 获取配置
            $cquery = new Config();
            $config_data = $cquery->info('seize_rules');
            if (empty($config_data)) {
                client_error('seize_rules配置不存在！');
            }
            $config = json_decode($config_data['value'], true);
            $rule_week = $config['weeks'];
            $rule_hours = $config['hours'];

            // 星期校验
            $week = date('w');
            if (array_search($week, $rule_week) === false) {
                return [
                    'res' => false,
                    'code' => 1,
                    'msg' => '不在规定的可抢占[星期]内!'
                ];
            }

            // 时间段校验
            $am_rule = explode(',', $rule_hours[0]);
            $pm_rule = explode(',', $rule_hours[1]);
            $am_start_time = $am_rule[0];
            $am_end_time = $am_rule[1];

            $pm_start_time = $pm_rule[0];
            $pm_end_time = $pm_rule[1];
            if (!((date('H:i:s', time()) >= $am_start_time && date('H:i:s', time()) <= $am_end_time)
                ||
               (date('H:i:s', time()) >= $pm_start_time && date('H:i:s', time()) <= $pm_end_time))
            ) {
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

            if (time() > strtotime($metting_end_time)) {
                return [
                    'res' => false,
                    'code' => 4,
                    'msg' => '会议室空闲中，请直接预定！'
                ];
            }

            if (strtotime($metting_start_time) + 15 * 60 > time()) {
                return [
                    'res' => false,
                    'code' => 5,
                    'msg' => '会议开始15分钟内，不可抢占！'
                ];
            }

            $now = date('Y-m-d H:i:s', time());
            if ($now > $metting_start_time) {
                $res['seize_start_time']    = $now;
                $res['seize_end_time']      = $metting_end_time;
            }
        } catch (Exception $e) {
            server_error('抢占会议室配置信息获取失败！' . $e->getMessage());
        }
        return $res;
    }

    /**
     * 会议室确认抢占
     * @param array $data
     * @return array
     * @throws \Throwable
     */
    #[ArrayShape(['seize_end_time' => "mixed"])] public function confirm(array $data = []): array
    {
        $room_id                = $data['room_id'];
        $metting_id             = $data['metting_id'];
        $subject                = $data['subject'];

        $rquery = new Room();
        $rquery->getConnection()->beginTransaction();
        try {
            $room_data  = $rquery->info(['id' => $room_id, 'is_deleted' => 0], ['id as room_id','name','floor','capacity','uses']);
            if (empty($room_data)) {
                client_error('会议室信息不存在！');
            }

            $mquery         = new Metting();
            $condition      = ['id'=> $metting_id,'is_deleted' => 0];
            $fileds         = ['id as metting_id','subject','moderator','metting_start_time','metting_end_time','status as metting_status'];
            $meet_data  = $mquery->info($condition, $fileds);
            if (empty($meet_data)) {
                client_error('会议信息不存在！');
            }
            $res                = array_merge($room_data, $meet_data);
            $res['room_uses']   = Room::$room_uses_map[$room_data['uses']] ?? '';

            $seize_result       = $this->seizeRulesValidate($res['metting_start_time'], $res['metting_end_time']);
            if ($seize_result['res'] === false) {
                client_error($seize_result['msg']);
            }

            $user_info = (new User())::info(['name']);
            // 新增会议信息
            $new_metting    = new Metting();
            $new_metting->room_id               = $room_id;
            $new_metting->subject               = $subject;
            $new_metting->moderator_id          = user_id();
            $new_metting->moderator             = $user_info['name'];
            $new_metting->metting_start_time    = date('Y-m-d H:i:s');
            $new_metting->metting_end_time      = $seize_result['seize_end_time'];
            $new_metting->created_by            = user_id();
            $new_metting->updated_by            = user_id();

            if (!$new_metting->save()) {
                $rquery->getConnection()->rollBack();
                client_error('会议室抢占失败！');
            }

            // 更新旧的会议信息
            $attributes = [
                'is_deleted'    => 1,
                'remark'        =>'用户' . user_id() . '抢占',
                'status'        => Metting::METTING_STATUS_SEIZE,
                'seize_user_id' => user_id(),
                'seize_time'    => date('Y-m-d H:i:s'),
                'updated_by'    => user_id()
            ];
            $update_row = $mquery->where(['id' => $metting_id])->update($attributes);
            if ($update_row <= 0) {
                $rquery->getConnection()->rollBack();
                client_error('会议室抢占失败。');
            }

            // 写入抢占/闪定记录
            $recoded_obj                = new ReserveRecord();
            $recoded_obj->user_id       = user_id();
            $recoded_obj->user_name     = $user_info['name'];
            $recoded_obj->date          = date('Y-m-d H:i:s');
            $recoded_obj->type          = ReserveRecord::TYPE_SEIZE;
            $recoded_obj->metting_id    = $new_metting->id;
            $recoded_obj->room_id       = $room_id;
            $recoded_obj->created_by    = user_id();
            $recoded_obj->updated_by    = user_id();
            if (!$recoded_obj->save()) {
                DB::rollBack();
                client_error('会议室抢占失败。');
            }
        } catch (\PDOException $ex) {
            DB::rollBack();
            server_error('抢占会议室失败：' . $ex->getMessage());
        } catch (Exception $e) {
            server_error('抢占会议室失败:' . $e->getMessage());
        }
        $rquery->getConnection()->commit();
        return [
            'seize_end_time' => $seize_result['seize_end_time']
        ];
    }

    /**
     * @title: 二维码抢占会议室
     * @path:
     * @param {*}
     * @return {*}
     * @description:
     * @author: EricZhou
     */
    public function scanCode(array $data = []) {
        $room_code = [
            'type' => 'seize',
            'room_id' => $data['room_id'],
        ];
        // 加密
        $scene = http_build_query($room_code);
        $optional = [
            'page' => 'pages/size/index'
        ];
        // 生成小程序码
        $mini_program = new MiniProgram();
        $app_code = $mini_program->appCode($scene, $optional);
        $file_name = md5($scene).'.jpeg';
        $storage = Storage::disk('local');
        $storage->put($file_name, $app_code);
        return ['url' => $storage->url($file_name)];
    }
}
