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
    public function info($condition = ['id' => 0], $fileds = ['*']){
        $query = self::query();
        $data = $query->where($condition)->get($fileds)->first();
        return json_decode(json_encode(collect($data)->toArray(),true),true);;
    }
}
