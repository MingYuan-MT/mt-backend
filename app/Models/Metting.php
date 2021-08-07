<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metting extends Model
{
    use HasFactory;

    protected $table = 'mettings';
    protected $fillable = ['room_id','subject', 'moderator','update_by','is_deleted','status','created_by'];

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
       $data = self::query()->where($condition)->get('room_id')->toArray();
       return array_column($data,'room_id');
    }

    public static function add($params)
    {
        $query = self::query();
        return $query->create($params);
    }
}
