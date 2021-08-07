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
     * @author: EricZhou
     * @param {*}
     * @return {*}
     * @description: 
     */
    public function useLogRecords()
    {
        $records = Metting::useLogRecords(['m.moderator_id' => user_id(), 'm.status' => Metting::METTING_STATUS_SEIZE]);
        $record_count['month_times'] = 0;
        $record_count['year_times'] = 0;
        foreach ($records as $record) {
            if(date('m',strtotime($record['seize_time']) == date('m'))){
                $record_count['month_times'] ++;
            }
            if(date('y',strtotime($record['seize_time']) == date('y'))){
                $record_count['year_times'] ++;
            }
        }
        $data['record_count'] = $record_count;
        $data['list']           = $records;
        return $data;
    }
}
