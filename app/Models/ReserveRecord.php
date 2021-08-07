<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReserveRecord extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','user_name', 'date','metting_id','room_id','created_by','updated_by'];

    public static function add($params)
    {
        $query = self::query();
        return $query->create($params);
    }

    public static function records($condition)
    {
        $query = self::query();
        return $query->where($condition)->get()->toArray();
    }
}
