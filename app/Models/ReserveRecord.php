<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReserveRecord extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','user_name', 'date','type','metting_id','room_id','created_by','updated_by'];

    const TYPE_NORMAL = 1; // 正常预定、闪定
    const TYPE_SEIZE = 2; // 抢占

    public static function add($params)
    {
        $query = self::query();
        return $query->create($params);
    }

    public static function records($condition)
    {
        $query = self::query();
        return $query
            ->leftJoin('rooms','rooms.id','=','reserve_records.room_id')
            ->leftJoin('mettings','mettings.id','=','reserve_records.metting_id')
            ->where($condition)
            ->select(['rooms.name','mettings.subject','metting_start_time','metting_end_time','date','mettings.status'])
            ->get()
            ->toArray();
    }
}
