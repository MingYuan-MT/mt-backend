<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/3 1:51 下午
 * @Author      : Jade
 */

namespace App\Http\Services;


class IndexService
{
    /**
     * @param $params
     * @return string[]
     */
    public function index($params): array
    {
        return ['message' => 'Hello world!'];
    }
}
