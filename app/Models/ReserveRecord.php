<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReserveRecord extends Model
{
    use HasFactory;

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
