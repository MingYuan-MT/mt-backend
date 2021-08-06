<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/7 4:27 上午
 * @Author      : Jade
 */

namespace App\Http\Services;

use App\Models\Room;
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

    /**
     * @param $rooms
     * @return array
     */
    private function dealData($rooms): array
    {
        $result = [];
        foreach ($rooms as &$room) {
            $result[$room['floor'].'F'][] = $room;
        }
        return $result;
    }
}
