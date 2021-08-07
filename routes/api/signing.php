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
    Route::get('lists', 'SigningController@lists'); // 获取需要签到的会议列表
    Route::get('code', 'SigningController@code'); // 获取签到二维码
    Route::post('signing', 'SigningController@signing'); // 扫描二维码进行签到
    Route::get('statistics', 'SigningController@statistics'); // 获取会议签到详情
});
