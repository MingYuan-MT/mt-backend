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
        return $this->where($key,$condition,$fileds)->first()->toArray();
    }
}
