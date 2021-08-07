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
use JetBrains\PhpStorm\ArrayShape;

class SigningController extends MiniApiController
{
    /**
     * @param SigningService $service
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function lists(SigningService $service): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $service->lists($this->params);
    }

    /**
     * @param SigningService $service
     * @return array
     */
    #[ArrayShape(['url' => "string"])] public function code(SigningService $service): array
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

    public function statistics(SigningService $service)
    {
        $params = $this->request->all();
        return $service->statistics($params);
    }

}
