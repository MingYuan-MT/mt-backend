<?php

namespace App\Exceptions;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * @param Request $request
     * @param Throwable $e
     * @return Application|ResponseFactory|JsonResponse|Response|\Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $e): Response|JsonResponse|\Symfony\Component\HttpFoundation\Response|Application|ResponseFactory
    {
        if ($e instanceof BadRequestHttpException) {
            $content = [
                'code' => $e->getStatusCode(),
                'message' => $e->getMessage(),
                'error' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTrace(),
                ],
                'data' => '',
            ];
            if (!config('app.debug')) {
                $content['error'] = '';
            }
            return response($content, $e->getStatusCode());
        } elseif ($e instanceof NotFoundHttpException) {
            $content = [
                'code' => $e->getStatusCode(),
                'message' => '未找到',
                'error' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTrace(),
                ],
                'data' => '',
            ];
            if (!config('app.debug')) {
                $content['error'] = '';
            }
            return response($content, $e->getStatusCode());
        } elseif ($e instanceof HttpException) {
            $content = [
                'code' => $e->getStatusCode(),
                'message' => $e->getMessage(),
                'error' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTrace(),
                ],
                'data' => '',
            ];
            if (!config('app.debug')) {
                $content['error'] = '';
            }
            return response($content, $e->getStatusCode());
        } else {
            $content = [
                'code' => 500,
                'message' => $e->getMessage(),
                'error' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTrace(),
                ],
                'data' => '',
            ];
            if (!config('app.debug')) {
                $content['error'] = '';
            }
            return response($content, 500);
        }
    }
}
