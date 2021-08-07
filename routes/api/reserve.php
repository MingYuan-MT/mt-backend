<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/7 3:32 上午
 * @Author      : Jade
 */

use \Illuminate\Support\Facades\Route;

/*
 | 预定 闪定
 */
Route::prefix('reserve')->group(function () {
    Route::post('condition-lists', 'ReserveController@conditionLists'); // 通过条件获取到的会议室列表
    Route::post('shake-lists', 'ReserveController@shakeLists'); // 通过摇一摇获取到的会议室列表
    Route::post('voice-lists', 'ReserveController@voiceLists'); // 通过语音获取到的会议室列表
    Route::post('add', 'ReserveController@add'); // 创建预定会议
    Route::post('edit', 'ReserveController@edit'); // 编辑预定会议
});
