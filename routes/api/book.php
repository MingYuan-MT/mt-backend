<?php

use \Illuminate\Support\Facades\Route;
Route::prefix('book')->group(function () {
    Route::get('condition', 'BookController@condition');
});
