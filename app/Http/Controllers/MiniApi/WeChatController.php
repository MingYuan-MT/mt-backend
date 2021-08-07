<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/7 2:40 下午
 * @Author      : Jade
 */

namespace App\Http\Controllers\MiniApi;

use App\Http\Controllers\MiniApiController;
use App\Http\Services\WeChatService;

class WeChatController extends MiniApiController
{
    public function callback()
    {
        dd($this->request->all());
    }

    /**
     * @param WeChatService $service
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|void
     */
    public function code(WeChatService $service)
    {
        $params = $this->getParams(
            [
                'name' => 'required'
            ],
            [
                'name.required' => '文件名不能为空',
            ]
        );
        return $service->code($params);
    }
}
