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
     * 成功返回码
     * @var int
     */
    const RET_SUCC = 0;

    /**
     * 失败返回码
     * @var int
     */
    const RET_FAIL = -1;

    /**
     * 失败返回码
     * @var int
     */
    const RET_SERVER_FAIL = 500;

    /**
     * @param $params
     * @return string[]
     */
    public function index($params): array
    {
        return ['message' => 'Hello world!'];
    }
}
