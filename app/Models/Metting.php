<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metting extends Model
{
    use HasFactory;

    protected $table = 'mettings';

    /**
     * @author: EricZhou
     * @param {*} $filed
     * @return {*}
     * @description: 会议详情
     */    
    public function info($key, $condition, $fileds = '*'){
        $query = self::query();
        $data = $query->where($key,$condition)->get($fileds)->first();
        return collect($data)->toArray();
    }
}
