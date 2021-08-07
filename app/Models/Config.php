<?php
 namespace App\Models;
/**
 * @Author: EricZhou
 * @CreateByIde: VsCode
 * @Date: 2021-08-07 01:47:46
 * @Email: mengyilingjian@outlook.com
 * @LastEditTime: 2021-08-07 17:22:39
 * @LastEditors: EricZhou
 * @Description: 配置表
 */

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;
    /**
     * @author: EricZhou
     * @param {name} $name 
     * @return {*}
     * @description: 获取地域信息
     */    
    public function info($name, $fileds = ['*']){
        $query = self::query();
        $data = $query->where('name',$name)->get($fileds)->first();
        return json_decode(json_encode(collect($data)->toArray(),true),true);;
    }
}