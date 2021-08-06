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
