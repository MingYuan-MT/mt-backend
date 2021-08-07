<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/7 2:40 下午
 * @Author      : Jade
 */

namespace App\Http\Controllers\MiniApi;

use App\Http\Controllers\MiniApiController;

class WeChatController extends MiniApiController
{
    public function callback()
    {
        dd($this->request->all());
    }
}
