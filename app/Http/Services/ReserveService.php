<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/7 4:27 上午
 * @Author      : Jade
 */

namespace App\Http\Services;

use App\Library\UnitTime\UnitTime;
use App\Library\XunFei\Nlp;
use App\Models\Metting;
use App\Models\ReserveRecord;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;

class ReserveService
{
    /**
     * @param $type
     * @param $params
     * @return array[]
     */
    #[ArrayShape(['data' => "array"])] public function lists($type, $params): array
    {
        switch ($type) {
            case 'condition':
                break;
            case 'shake':
                break;
            case 'voice';
                $this->voice($params);
                break;

        }
        //获取当前时间段正在使用的会议室id
        $roomWhere = ['status' => 1, 'is_deleted' => 0];
        if (isset($params['start_time']) && $params['start_time']) {
            $roomWhere[] = ['metting_start_time', '>', $params['start_time']];
            $roomWhere[] = ['metting_end_time', '<', $params['start_time']];
        }
        $roomIds = Metting::getRoomIds($roomWhere);
        $where = [];
        if ($roomIds) {
            $where[] = ['id', 'not in', $params['room_id']];
        }
        if (isset($params['region']) && $params['region']) {
            $where['rooms.region_id'] = 26691;
        }
        if (isset($params['is_shadow'])) {
            $where['projection_mode'] = $params['is_shadow'] ? [1, 2, 3] : 0;
        }

        $order = $this->getOrder();
        $rooms = Room::getList($where, $order);
        $rooms = $this->dealData($rooms,$params);
        return $rooms;
    }

    public function edit($params)
    {
        try {
            $metting = Metting::find($params['id']);
            if ($metting) {
                $metting->update([
                    'subject' => $params['subject'],
                    'moderator' => $params['moderator'],
                    'updated_by' => user_id(),
                ]);
                return [];
            }
            return server_error('会议不存在');
        } catch (\Exception $e) {
            server_error('会议更新失败:' . $e->getMessage());
        }
    }

    public function add($params)
    {
        try {
            $userId = user_id();
            $user = User::info(['id' => $userId]);
            DB::beginTransaction();
            $metting = Metting::add([
                'room_id' => $params['room_id'],
                'subject' => $user['name'] . '预订的会议室',
                'moderator' => $user['name'],
                'metting_start_time' => $params['start_time'],
                'metting_end_time' => $params['end_time'],
                'status' => 0,
                'created_by' => user_id(),
                'moderator_id' => user_id(),
                'is_need_sign' => $params['is_need_sign']
            ]);
            //会议室预订记录表
            ReserveRecord::add([
                'user_id' => $userId,
                'user_name' => $user['name'],
                'date' => date('Y-m-d H:i:s'),
                'type' => 1,
                'metting_id' => $metting->id,
                'room_id' => $params['room_id'],
                'created_by' => user_id(),
            ]);
            DB::commit();
            return $metting;
        } catch (\Exception $e) {
            DB::rollBack();
            server_error('会议预订失败:' . $e->getMessage());
        }
    }

    /**
     * @param $rooms
     * @return array
     */
    private function dealData($rooms,$params): array
    {
        $result = [];
        foreach ($rooms as $room) {
            $room['uses_name'] = Room::$room_uses_map[$room['uses']];
            $room['projection_mode_name'] = Room::$projection_mode_map[$room['projection_mode']];
            $room['start_time'] = $params['start_time'];
            $room['end_time'] = $params['end_time'] ?? '';
            $room['is_need_sign'] = $params['is_need_sign'] ?? 0;
            $result[$room['build_name']][$room['floor'] . 'F'][] = $room;
        }
        return $result;
    }


    private function getOrder()
    {
        $userId = user_id();
        $user = User::info(['id' => $userId]);
        $order = 'id asc';
        if ($user['seat_number']) {
            $userRegion = explode('-', $user['seat_number']);
            if ($userRegion) {
                $userBuild = $userRegion[0];
                $userFloor = $userRegion[1];
                $roomIds = Room::getRoomIds(['floor' => $userFloor]);
                if ($roomIds) {
                    $roomIds = implode(',', $roomIds);
                    $order = "rooms.id not in(" . $roomIds . "),floor desc";
                }
            }
        }
        return $order;
    }

    /**
     * @param $params
     */
    private function voice(&$params)
    {
        $text = arr_value($params, 'text/s', '');
        try {
            $nlp = new Nlp();
            $nlp_data = $nlp->get($text);
            $nlp_time = $nlp->getTime($nlp_data);
            if (empty($nlp_time)) {
                client_error('语音解析失败，请重新尝试');
            }
            $unit_time = new UnitTime();
            $unit_time_data = $unit_time->get($nlp_time);
            if (empty($unit_time_data)) {
                client_error('语音处理失败，请重新尝试');
            }
            $params['start_time'] = arr_value($unit_time_data, 'keyDate/s', '');
        } catch (\Exception $e) {
            server_error($e->getMessage());
        }
    }
}
