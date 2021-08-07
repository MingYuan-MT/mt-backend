<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/7 12:38 上午
 * @Author      : Jade
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param  \Closure  $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $guard = null): mixed
    {
        if (in_array($request->getPathInfo(), ['/v1/login/login', '/v1/login/wechat-auth', '/v1/wechat/callback'])) {
            return $next($request);
        }
        $auth = Auth::guard('api');
        if (!$auth->check()) {
            return response([
                'code' => 401,
                'message' => '请登录',
                'error' => '',
                'data' => '',
            ], 401);
        } else {
            $_SERVER['X_USER_ID'] = $auth->user()->id;
        }
        return $next($request);
    }
}
