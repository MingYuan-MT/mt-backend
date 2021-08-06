<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/3 1:44 下午
 * @Author      : Jade
 */
/**
 * @                  ___====-_  _-====___
 * @            _--^^^#####//      \\#####^^^--_
 * @         _-^##########// (    ) \\##########^-_
 * @        -############//  |\^^/|  \\############-
 * @      _/############//   (@::@)   \############\_
 * @     /#############((     \\//     ))#############\
 * @    -###############\\    (oo)    //###############-
 * @   -#################\\  / VV \  //#################-
 * @  -###################\\/      \//###################-
 * @ _#/|##########/\######(   /\   )######/\##########|\#_
 * @ |/ |#/\#/\#/\/  \#/\##\  |  |  /##/\#/  \/\#/\#/\#| \|
 * @ `  |/  V  V  `   V  \#\| |  | |/#/  V   '  V  V  \|  '
 * @    `   `  `      `   / | |  | | \   '      '  '   '
 * @                     (  | |  | |  )
 * @                    __\ | |  | | /__
 * @                   (vvv(VVV)(VVV)vvv)
 * @
 * @     ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * @
 * @               神兽保佑            永无BUG
 */

use \Illuminate\Support\Facades\Route;

Route::prefix('index')->group(function () {
    Route::get('index', 'IndexController@index');
});

// 会议信息
Route::group(['prefix' => '/metting'], function () {
    // 获取会议信息
    Route::get('/info','MettingController@info');

    // 获取会议室会议信息
    Route::get('/infomation','MettingController@infomation');

    //更新会议信息
    Route::post('/update','MettingController@update');

    //会议信息确认
    Route::get('/update','MettingController@update');

});

// 会议室信息
Route::group(['prefix' => '/room'], function () {
    // 获取会议室信息
    Route::get('/info','RoomController@info');

    // 获取可抢占的会议室信息
    Route::get('/seizeInfomation','RoomController@seizeInfomation');

    //更新会议室信息
    Route::post('/update','RoomController@update');

    //会议室抢占

    Route::post('/seize','MettingController@seize');
    // 抢占成功界面
    // 抢占未满足条件界面
    // 记录抢占行为日志

    Route::post('/save','MettingController@save');

    Route::get('/list','RoomController@list');

});
