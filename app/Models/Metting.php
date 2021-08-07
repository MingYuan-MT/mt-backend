<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metting extends Model
{
    use HasFactory;

    protected $table = 'mettings';
    protected $fillable = ['room_id','subject', 'moderator','update_by','is_deleted','status','created_by'];

    const METTING_STATUS_DEFAULT = 0;
    const METTING_STATUS_ONGOING = 1;
    const METTING_STATUS_END = 2;
    const METTING_STATUS_CANCEL = 3;

    public static $metting_status_map = [
        self::METTING_STATUS_DEFAULT => '未开始',
        self::METTING_STATUS_ONGOING => '进行中',
        self::METTING_STATUS_END => '已结束',
        self::METTING_STATUS_CANCEL => '已取消',
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
        return collect($data)->toArray();
    }

    public static function getRoomIds($condition)
    {
        return self::query()->where($condition)->value('room_id');
    }

    public static function add($params)
    {
        $query = self::query();
        return $query->create($params);
    }
}
