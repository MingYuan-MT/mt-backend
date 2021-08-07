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
        $where = [];
        $roomIds = $this->getRoomIds($params);
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

    private function getRoomIds($params)
    {
        $where =  ['status' => 1];
        if (isset($params['start_time']) && $params['start_time']) {
            $where[] = ['metting_start_time','>',$params['start_time']];
            $where[] = ['metting_end_time','<',$params['start_time']];
        }
        $model = new Metting();
        return $model->getRoomIds($where);
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
