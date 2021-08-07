<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/7 4:32 上午
 * @Author      : Jade
 */

namespace App\Http\Services;

use App\Models\Metting;
use App\Models\ReserveRecord;
use JetBrains\PhpStorm\ArrayShape;

class MyService
{
    public function records()
    {
        $data = ReserveRecord::records(['user_id' => user_id()]);
        return $this->dealData($data);
    }

    private function dealData($data)
    {
        $weekArray = array("日", "一", "二", "三", "四", "五", "六");
        foreach ($data as &$item) {
            $item['week'] = "星期".$weekArray[date("w", strtotime($item['date']))];
            $item['date'] = date('Y年m月d日',strtotime($item['date']));
            $item['metting_start_time'] = date('H:i',strtotime($item['metting_start_time']));
            $item['metting_end_time'] = date('H:i',strtotime($item['metting_end_time']));
            $item['status'] = Metting::$metting_status_map[$item['status']];
        }
        return $data;
    }

    /**
     * @title: 会议抢占记录
     * @path:
     * @return array {*}
     * @description:
     * @author: EricZhou
     */
    #[ArrayShape(['record_count' => "array", 'list' => "\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection"])] public function useLogRecords(): array
    {
        $query = Metting::query();
        $columns = ['rooms.name as room_name','users.name as size_name','mettings.seize_time'];
        $query->where(['mettings.moderator_id' => user_id(), 'mettings.status' => Metting::METTING_STATUS_SEIZE]);
        $query->leftJoin('users', 'users.id', '=', 'mettings.seize_user_id');
        $query->leftJoin('rooms', 'rooms.id', '=', 'mettings.room_id');
        $data = $query->get($columns);
        $data = collect($data)->toArray();
        $record_count['month_times'] = 0;
        $record_count['year_times'] = 0;
        foreach ($data as $record) {
            if(date('m',strtotime($record['seize_time']) == date('m'))){
                $record_count['month_times'] ++;
            }
            if(date('y',strtotime($record['seize_time']) == date('y'))){
                $record_count['year_times'] ++;
            }
        }
        return [
            'record_count' => $record_count,
            'list' => array_values($data),
        ];
    }
}
