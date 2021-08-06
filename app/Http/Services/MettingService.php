<?php
/**
 * @Author: EricZhou
 * @CreateByIde: VsCode
 * @Date: 2021-08-06 20:20:53
 * @Email: mengyilingjian@outlook.com
 * @LastEditTime: 2021-08-06 21:18:42
 * @LastEditors: EricZhou
 * @Description: 会议服务
 */
namespace App\Http\Services;

use App\Models\Metting;

class MettingService
{
    /**
     * @title: 
     * @path: 
     * @author: EricZhou
     * @param {*} $data 字段与值对应
     * @return Metting
     * @description: 
     */
    public function info($data = []){
        $query = new Metting();
        $data = $query->info('id', $data['id']);
        return $data;
    }
}