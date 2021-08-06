<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/6 11:07 下午
 * @Author      : Jade
 */

use \Illuminate\Support\Facades\Route;

/*
 | 登陆
 */
Route::prefix('login')->group(function () {
    Route::post('login', 'LoginController@login'); // 登陆&注册
});
