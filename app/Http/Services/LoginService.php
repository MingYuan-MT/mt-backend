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
     * 微信授权
     * @param $params
     * @return array
     */
    public function wechatAuth($params): array
    {
        $mini_program = new MiniProgram();
        return $mini_program->auth(arr_value($params, 'code/s', ''));
    }

    /**
     * 登陆&注册
     * @param $params
     * @return array
     */
    #[ArrayShape(['token' => "string"])] public function login($params): array
    {
        return ['token' => User::login($params)];
    }
}
