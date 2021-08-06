<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    /**
     * @author: EricZhou
     * @param {*} $filed $condition
     * @return {*}
     * @description: 会议室详情
     */    
    public function info($key, $condition, $fileds = '*'){
        $query = self::query();
        $data = $query->where($key,$condition)->get($fileds)->first();
        return collect($data)->toArray();
    }
}
