<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    const PROJECTION_MODE_NULL = 0;
    const PROJECTION_MODE_TV = 1;
    const PROJECTION_MODE_SHADOW = 2;
    const PROJECTION_MODE_EL = 3;
    public static $projection_mode_map = [
        self::PROJECTION_MODE_NULL => "无",
        self::PROJECTION_MODE_TV => "电视",
        self::PROJECTION_MODE_SHADOW => "投影",
        self::PROJECTION_MODE_EL => "电子屏",
    ];

    /**
     * @param {*} $filed $condition
     * @return {*}
     * @description: 会议室详情
     * @author: EricZhou
     */
    public function info($condition = ['id' => 0], $fileds = ['*'])
    {
        $query = self::query();
        $data = $query->where($condition)->get($fileds)->first();
        return json_decode(json_encode(collect($data)->toArray(),true),true);;
    }

    public static function getList($condition, $order)
    {
        return self::query()
            ->leftJoin('buildings', 'buildings.id', '=', 'rooms.building_id')
            ->select('rooms.*', 'buildings.name as build_name')
            ->where($condition)
            ->orderByRaw(DB::raw($order))
            ->get()
            ->toArray();
    }

    public static function getRoomIds($condition)
    {
        $data = self::query()->where($condition)->get('id')->toArray();
        return array_column($data, 'id');
    }
}
