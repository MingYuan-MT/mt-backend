<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/6 8:52 下午
 * @Author      : Jade
 */

namespace App\Http\Services;

use App\Library\WeChat\MiniProgram;
use App\Models\User;
use JetBrains\PhpStorm\ArrayShape;

class LoginService
{
    /**
     * 登陆&注册
     * @param $params
     * @return array
     */
    #[ArrayShape(['token' => "string"])] public function login($params): array
    {
        $mini_program = new MiniProgram();
        $openid = $mini_program->auth(arr_value($params, 'code/s', ''));
        if (empty($openid)) {
            client_error('微信授权登陆失败');
        }
        $params['openid'] = $openid;
        return [
            'token' => User::login($params)
        ];
    }
}
