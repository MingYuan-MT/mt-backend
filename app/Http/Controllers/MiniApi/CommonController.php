<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/3 1:49 下午
 * @Author      : Jade
 */

namespace App\Http\Controllers\MiniApi;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CommonController  extends BaseController
{
    use ValidatesRequests;

    /**
     * 请求信息
     */
    protected Request $request;

    /**
     * 请求参数
     * @var array
     */
    protected array $params;

    /**
     * CommonController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->params = $request->all();
        $this->request = $request;
    }

    /**
     * @param $rules
     * @param array $message
     * @return array
     */
    protected function getParams($rules, array $message = []): array
    {
        $validator = Validator::make($this->params, $rules, $message);
        if ($validator->fails()) {
            throw new BadRequestHttpException($validator->errors()->first());
        }
        return $validator->valid();
    }
}
