<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/4 10:10 下午
 * @Author      : Jade
 */

namespace App\Http\Services;

use App\Models\Metting;

class SigningService
{

    public function list($params)
    {

    }

    public function code($params)
    {
        $query = Metting::query();
        $query->where(['id' => arr_value($params, 'id/d', 0)]);
        $metting_id =  $query->first(['id', 'room_id']);
        dd($metting_id);
    }

    public function share($params)
    {

    }

    public function statistics($params)
    {

    }
}
