<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/6 8:52 下午
 * @Author      : Jade
 */

namespace App\Http\Controllers\MiniApi;

use App\Http\Services\LoginService;

class LoginController extends CommonController
{
    public function login(LoginService $service)
    {
        $params = $this->getParams(
            [
                'nick_name' => 'required',
                'unionid' => 'required|string',
                'mobile' => ['regex:/^(?:(?:\+|00)86)?1[3-9]\d{9}$/'],
            ],
            [
                'nick_name.required' => '昵称不能为空',
                'unionid.required' => '微信用户唯一标识不能为空',
                'mobile.regex' => '请输入正确的手机号',
            ]
        );
        return $service->login($params);
    }
}
