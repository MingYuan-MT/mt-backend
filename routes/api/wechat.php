<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/7 2:38 下午
 * @Author      : Jade
 */

use \Illuminate\Support\Facades\Route;

/*
 | 微信回调
 */
Route::prefix('wechat')->group(function () {
    Route::any('callback', 'WeChatController@callback'); // 回调
    Route::any('code', 'WeChatController@code'); // 回调
});
