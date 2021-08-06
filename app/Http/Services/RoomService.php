<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/3 1:51 下午
 * @Author      : Jade
 */

namespace App\Http\Services;


use App\Models\Room;
use Illuminate\Support\Facades\DB;

class RoomService
{
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
