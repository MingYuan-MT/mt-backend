<?php
/**
 * @Description : 签到模块路由
 *
 * @Date        : 2021/8/4 10:11 下午
 * @Author      : Jade
 */

use \Illuminate\Support\Facades\Route;

/*
 | 签到
 */
Route::prefix('signing')->group(function () {
    Route::get('list', 'SigningController@list'); // 获取需要签到的会议列表
    Route::get('info', 'SigningController@info'); // 获取签到二维码
    Route::get('share', 'SigningController@share'); // 分享签到二维码
    Route::get('statistics', 'SigningController@statistics');
});
