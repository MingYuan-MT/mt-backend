<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Metting extends Model
{
    use HasFactory;

    protected $table = 'mettings';
    protected $fillable = ['room_id','subject','is_need_sign','moderator_id','moderator','seize_user_id','seize_time','metting_start_time','metting_end_time','remark','update_by','is_deleted','status','created_by'];

    const METTING_STATUS_DEFAULT = 0;
    const METTING_STATUS_ONGOING = 1;
    const METTING_STATUS_END = 2;
    const METTING_STATUS_CANCEL = 3;
    const METTING_STATUS_SEIZE = 4;

    public static $metting_status_map = [
        self::METTING_STATUS_DEFAULT => '未开始',
        self::METTING_STATUS_ONGOING => '进行中',
        self::METTING_STATUS_END => '已结束',
        self::METTING_STATUS_CANCEL => '已取消',
        self::METTING_STATUS_SEIZE => '被抢占',
    ];

    /**
     * @author: EricZhou
     * @param {*} $filed
     * @return {*}
     * @description: 会议详情
     */
    public function info($condition = ['id' => 0], $fileds = ['*']){
        $query = self::query();
        $data = $query->where($condition)->get($fileds)->first();
        return json_decode(json_encode(collect($data)->toArray(),true),true);
    }

    public static function getRoomIds($condition)
    {
       $data = self::query()->where($condition)->get('room_id')->toArray();
       return array_column($data,'room_id');
    }

    public static function add($params)
    {
        $query = self::query();
        return $query->create($params);
    }

    /**
     * @title: 会议抢占记录
     * @path:
     * @author: EricZhou
     * @param {*} $condition
     * @return {*}
     * @description:
     */
    public static function useLogRecords($condition = ['id' => 0]){
        $data = DB::table('mettings as m')
            ->leftJoin('users as u', 'u.id', '=', 'm.seize_user_id')
            ->leftJoin('rooms as r', 'r.id', '=', 'm.room_id')
            ->select('r.name as room_name','u.name as size_name','m.seize_time')
            ->where($condition)
            ->get();

        return json_decode(json_encode(collect($data)->toArray(),true),true);
    }
}
