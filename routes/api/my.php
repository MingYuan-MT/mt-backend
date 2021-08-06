<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/7 3:31 上午
 * @Author      : Jade
 */

use \Illuminate\Support\Facades\Route;

/*
 | 我的
 */
Route::prefix('my')->group(function () {
    Route::get('reserve', 'MyController@reserve'); // 查看我预订的会议室
    Route::get('use-log', 'MyController@useLog'); // 查看我的资源浪费记录
});
