<?php
/**
 * @Author: EricZhou
 * @CreateByIde: VsCode
 * @Date: 2021-08-06 20:20:53
 * @Email: mengyilingjian@outlook.com
 * @LastEditTime: 2021-08-06 23:11:00
 * @LastEditors: EricZhou
 * @Description: 会议室服务
 */
namespace App\Http\Services;

use App\Models\Metting;
use App\Models\Room;

class RoomService extends IndexService 
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
        $roomData = [];
        try{
            $rquery = new Room();
            $roomData = $rquery->info('id', $data['id']);
            if(empty($roomData)){
                abort(self::RET_FAIL, '会议室信息不存在！');
            }
        }catch(\Exception $e){
            abort(self::RET_SERVER_FAIL, '会议室信息获取失败:'.$e->getMessage());
        }
        return $roomData;
    }
}