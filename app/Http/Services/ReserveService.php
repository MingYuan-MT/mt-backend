<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/7 4:27 上午
 * @Author      : Jade
 */

namespace App\Http\Services;

use App\Models\Metting;
use App\Models\ReserveRecord;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
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
        //获取当前时间段正在使用的会议室id
        $roomWhere = ['status' => 1];
        if (isset($params['start_time']) && $params['start_time']) {
            $roomWhere[] = ['metting_strat_time', '>', $params['start_time']];
            $roomWhere[] = ['metting_end_time', '<', $params['start_time']];
        }
        $roomIds = Metting::getRoomIds($roomWhere);
        $where = [];
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
                return ['message' => '更新成功'];
            }
            return ['message' => '会议不存在'];
        } catch (\Exception $e) {
            server_error('会议更新失败:' . $e->getMessage());
        }
    }

    public function add($params)
    {
        try {
            $metting = Metting::add([
                'room_id' => $params['room_id'],
                'subject' => $params['subject'],
                'moderator' => $params['moderator'],
                'metting_strat_time' => $params['metting_strat_time'],
                'metting_end_time' => $params['metting_end_time'],
                'status' => 0
            ]);
            //会议室预订记录表
            $userId = user_id();
            $user = User::info(['id' => $userId]);
            ReserveRecord::add([
                'user_id' => $userId,
                'user_name' => $user['name'],
                'date' => date('Y-m-d H:i:s'),
                'type' => 1,
                'metting_id' => $metting->id,
                'room_id' => $params['room_id']
            ]);
            return ['message' => '预订成功'];
        } catch (\Exception $e) {
            server_error('会议预订失败:' . $e->getMessage());
        }
    }

    /**
     * @param $rooms
     * @return array
     */
    private function dealData($rooms): array
    {
        $result = [];
        foreach ($rooms as $room) {
            $result[$room['floor'] . 'F'][] = $room;
        }
        return $result;
    }
}
