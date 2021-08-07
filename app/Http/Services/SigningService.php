<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/4 10:10 下午
 * @Author      : Jade
 */

namespace App\Http\Services;

use App\Library\WeChat\MiniProgram;
use App\Models\Metting;

class SigningService
{

    public function list($params)
    {

    }

    public function code($params)
    {
        $query = Metting::query();
        $query->where(['id' => arr_value($params, 'metting_id/d', 0)]);
        $metting_id =  $query->first(['id', 'room_id'])->id;
        $metting_code = [
            'type' => 'signing',
            'metting_id' => $metting_id,
        ];
        // 加密
        $scene = url;
        $optional = [
            'page' => 'pages/sign/signDetails/index'
        ];
        // 生成小程序码
        $mini_program = new MiniProgram();
        $app_code = $mini_program->appCode($scene, $optional);
        dd($app_code);
    }

    public function share($params)
    {

    }

    public function statistics($params)
    {

    }
}
