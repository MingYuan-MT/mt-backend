<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/4 10:09 下午
 * @Author      : Jade
 */

namespace App\Http\Controllers\MiniApi;

use App\Http\Controllers\MiniApiController;
use App\Http\Services\SigningService;

class SigningController extends MiniApiController
{
    /**
     * @return mixed
     */
    public function list(SigningService $service)
    {
        return $service->list($this->params);
    }

    public function code(SigningService $service)
    {
        $params = $this->getParams(
            [
                'metting_id' => 'required|int'
            ],
            [
                'metting_id.required' => '会议ID不能为空',
                'metting_id.int' => '会议ID必须为数字',
            ]
        );
        return $service->code($params);
    }

    public function share(SigningService $service)
    {
        $params = $this->getParams(
            [
                'metting_id' => 'required|int'
            ],
            [
                'metting_id.required' => '会议ID不能为空',
                'metting_id.int' => '会议ID必须为数字',
            ]
        );
        return $service->share($params);
    }

    public function statistics(SigningService $service)
    {
        $params = $this->request->all();
        return $service->statistics($params);
    }

    public function template_message_send()
    {

    }
}
