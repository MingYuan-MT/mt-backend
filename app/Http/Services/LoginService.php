<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/6 8:52 下午
 * @Author      : Jade
 */

namespace App\Http\Services;

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
        return ['token' => User::login($params)];
    }
}
