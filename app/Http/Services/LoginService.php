<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/6 8:52 下午
 * @Author      : Jade
 */

namespace App\Http\Services;

use App\Models\User;

class LoginService
{
    /**
     * @param $params
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function login($params)
    {
        $token =  User::login($params);
        return ['token' => $token];
    }
}
