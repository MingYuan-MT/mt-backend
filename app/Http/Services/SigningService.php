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
        $query->where('id',1);
        $data = $query->first(['id', 'room_id']);
        dd($data);
    }

    public function share($params)
    {

    }

    public function statistics($params)
    {

    }
}
