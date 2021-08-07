<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/7 1:49 下午
 * @Author      : Jade
 */

namespace App\Library\WeChat;

use Overtrue\LaravelWeChat\Facade as EasyWeChat;
use EasyWeChat\Kernel\Exceptions\Exception;

class MiniProgram
{
    private $app;

    public function __construct()
    {
        $this->app = EasyWeChat::miniProgram();
    }

    /**
     * 登陆
     * @param $code
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string|void
     */
    public function auth($code)
    {
        try {
            $auth = $this->app->auth->session($code);
            return arr_value($auth, 'openid/s', '');
        } catch (Exception $exception) {
            server_error($exception->getMessage());
        }
    }

    /**
     * 小程序码
     * @param $scene
     * @param $optional
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string|void
     */
    public function appCode($scene, $optional)
    {
        try {
            return $this->app->app_code->getUnlimit($scene, $optional);
        } catch (Exception $exception) {
            server_error($exception->getMessage());
        }
    }
}
