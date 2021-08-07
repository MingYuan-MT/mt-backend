<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/6 8:52 下午
 * @Author      : Jade
 */

namespace App\Http\Controllers\MiniApi;

use App\Http\Controllers\MiniApiController;
use App\Http\Services\LoginService;
use JetBrains\PhpStorm\ArrayShape;

class LoginController extends MiniApiController
{
    /**
     * 登陆&注册
     * @param LoginService $service
     * @return array
     */
    #[ArrayShape(['token' => "string"])] public function login(LoginService $service): array
    {
        $params = $this->getParams(
            [
                'nick_name' => 'required',
                'code' => 'required',
            ],
            [
                'nick_name.required' => '昵称不能为空',
                'code.required' => 'code 不能为空',
            ]
        );
        return $service->login($params);
    }
}
