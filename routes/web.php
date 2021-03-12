<?php

use Illuminate\Support\Facades\Route;
use MarcusGaius\ApiReports\Controllers\GoogleAuthController;

Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::get('/', 'App\Http\Controllers\GoogleAuthController@home')->name('home');
    Route::get('code', 'App\Http\Controllers\GoogleAuthController@code')->name('code');
    Route::get('retrieve', 'App\Http\Controllers\GoogleAuthController@retrieve')->name('retrieve');
});
