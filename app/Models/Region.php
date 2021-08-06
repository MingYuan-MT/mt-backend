<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;
    /**
     * @author: EricZhou
     * @param {*} $filed $condition
     * @return {*}
     * @description: 获取地域信息
     */    
    public function info($key, $condition, $fileds = ['*']){
        $query = self::query();
        $data = $query->where($key,$condition)->get($fileds)->first();
        return collect($data)->toArray();
    }
}
