<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/7 3:34 上午
 * @Author      : Jade
 */

use \Illuminate\Support\Facades\Route;

/*
 | 抢占
 */
Route::prefix('seize')->group(function () {
    Route::get('metting-info','SeizeController@mettingInfo'); // 通过二维码展示当前会议室会议信息
    Route::post('confirm','SeizeController@confirm'); // 确认抢占
});
