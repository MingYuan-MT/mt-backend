<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    // 会议室用途
    const ROOM_USES_TEL = 1;
    const ROOM_USES_TALK = 2;
    const ROOM_USES_ACTIVITY = 3;
    public static $room_uses_map = [
        self::ROOM_USES_TEL => "电话",
        self::ROOM_USES_TALK => "洽谈",
        self::ROOM_USES_ACTIVITY => "活动",
    ];

    // 会议室状态
    const ROOM_STATUS_CAN = 1;
    const ROOM_STATUS_SEIZE = 2;
    const ROOM_STATUS_CLOSE = 3;
    public static $room_status_map = [
        self::ROOM_STATUS_CAN => "可预定",
        self::ROOM_STATUS_SEIZE => "已预占",
        self::ROOM_STATUS_CLOSE => "封闭",
    ];

    /**
     * @author: EricZhou
     * @param {*} $filed $condition
     * @return {*}
     * @description: 会议室详情
     */    
    public function info($condition = ['id' => 0], $fileds = ['*']){
        $query = self::query();
        $data = $query->where($condition)->get($fileds)->first();
        return collect($data)->toArray();
    }
}
