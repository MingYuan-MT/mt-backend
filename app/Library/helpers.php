<?php
/**
 * @Description : 系统帮助函数库 ｜ 使用率比较高的方法可以封装此处
 *
 * @Date        : 2021/8/3 2:08 下午
 * @Author      : Jade
 */

if (!function_exists('arr_value')) {
    /**
     * 获取变量 支持默认值
     * @param array $data 数据源
     * @param string $name 字段名
     * @param null $default 默认值
     * @return mixed
     */
    function arr_value(array $data = [], string $name = '', $default = null): mixed
    {
        return \App\Library\Core\Core::value($data, $name, $default);
    }
}

if (!function_exists('rand_str')) {
    /**
     * 生成随机字符串
     * @param int $length
     * @return string
     */
    function rand_str(int $length = 32): string
    {
        $rand = '';
        $rand_str = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $max = strlen($rand_str) -1;
        mt_srand((double) microtime() * 1000000);
        for($i = 0; $i < $length; $i++) {
            $rand .= $rand_str[mt_rand(0, $max)];
        }
        return $rand;
    }
}

if (!function_exists('user_id')) {
    /**
     * 获取当前登陆的用户ID
     * @return mixed
     */
    function user_id(): mixed
    {
        return $_SERVER['X_USER_ID'] ?? 0;
    }
}

if (!function_exists('client_error')) {
    /**
     * 客户端错误｜请求端错误
     * @param $message
     */
    function client_error($message) {
        throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException($message);
    }
}

if (!function_exists('server_error')) {
    /**
     * 服务端错误
     * @param $message
     */
    function server_error($message) {
        throw new \Symfony\Component\HttpKernel\Exception\HttpException(500, $message);
    }
}
