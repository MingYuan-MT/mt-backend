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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function getRoomIds($params)
    {
        $where =  ['status' => 1];
        if (isset($params['start_time']) && $params['start_time']) {
            $where[] = ['metting_strat_time','>',$params['start_time']];
            $where[] = ['metting_end_time','<',$params['start_time']];
        }
        $model = new Metting();
        return $model->getRoomIds($where);
    }

    public function save($params)
    {
        $model = new  Metting();
        $model->room_id = $params['room_id'];
        $model->subject = '张力'.'预订的会议室';
        $model->moderator = '张力';
        $model->metting_strat_time = $params['start_time'];
        $model->metting_end_time = $params['end_time'];
        $model->status = 0;
        $model->save();
        return ['message' => '预订成功'];
    }

    public function update($params)
    {
        $metting = Metting::find($params['id']);
        if($metting){
            $metting->update([
                'subject' => $params['subject'],
                'moderator' => $params['moderator'],
                'updated_by' => '周磊',
            ]);
            return ['message' =>'更新成功'];
        }
        return ['message' =>'会议不存在'];

    }
}
