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

if (!function_exists('sys_encrypt')) {
    /**
     * 系统加密方法
     * @param array $data 要加密的集合
     * @param string $key 加密密钥
     * @param int $expire 过期时间 单位 秒
     * @return string
     */
    function sys_encrypt(array $data, string $key = '', int $expire = 0): string
    {
        $key = md5(empty($key) ? config('app.key') : $key);
        $data = base64_encode(json_encode($data));
        $x = 0;
        $len = strlen($data);
        $l = strlen($key);
        $char = '';

        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) {
                $x = 0;
            }
            $char .= substr($key, $x, 1);
            $x++;
        }

        $str = sprintf('%010d', $expire ? $expire + time() : 0);

        for ($i = 0; $i < $len; $i++) {
            $str .= chr(ord(substr($data, $i, 1)) + (ord(substr($char, $i, 1))) % 256);
        }
        return str_replace(array('+', '/', '='), array('-', '_', ''), base64_encode($str));
    }
}

if (!function_exists('sys_decrypt')) {
    /**
     * 系统解密方法
     * @param string $data 要解密的字符串 （必须是think_encrypt方法加密的字符串）
     * @param string $key 加密密钥
     * @return array
     * @author wapai
     */
    function sys_decrypt(string $data, string $key = ''): array
    {
        $key = md5(empty($key) ? config('app.key') : $key);
        $data = str_replace(array('-', '_'), array('+', '/'), $data);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        $data = base64_decode($data);
        $expire = substr($data, 0, 10);
        $data = substr($data, 10);

        if ($expire > 0 && $expire < time()) {
            return '';
        }
        $x = 0;
        $len = strlen($data);
        $l = strlen($key);
        $char = $str = '';

        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) {
                $x = 0;
            }
            $char .= substr($key, $x, 1);
            $x++;
        }

        for ($i = 0; $i < $len; $i++) {
            if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
                $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
            } else {
                $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
            }
        }
        return json_decode(base64_decode($str), true);
    }
}
