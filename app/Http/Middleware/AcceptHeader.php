<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/3 2:43 下午
 * @Author      : Jade
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AcceptHeader
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $request->headers->set('Accept', 'application/json');
        return $next($request);
    }
}
