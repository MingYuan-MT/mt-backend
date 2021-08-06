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

    public function info($params)
    {
        $query = Metting::query();
        $query->where(['id' => 1, 'room_id' => 1]);
        return $query->first(['id', 'room_id']);
    }

    public function share($params)
    {

    }

    public function statistics($params)
    {

    }
}
