<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/3 1:44 下午
 * @Author      : Jade
 */

use \Illuminate\Support\Facades\Route;

Route::prefix('index')->group(function () {
    Route::get('index', 'IndexController@index');
});
