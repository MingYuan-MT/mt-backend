<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/5 12:07 上午
 * @Author      : Jade
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return JsonResponse|Response
     */
    public function handle(Request $request, Closure $next): Response|JsonResponse
    {
        $response = $next($request);
        if ($response instanceof JsonResponse) {
            $data = [
                'code' => $response->getStatusCode(),
                'message' => 'success',
                'error' => '',
                'data' => $response->getData()
            ];
            $response->setData($data);
            return $response;
        }

        if ($response instanceof Response && $response->exception) {
            return $response;
        }
    }
}
