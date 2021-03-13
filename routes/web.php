<?php

use Illuminate\Support\Facades\Route;
use MarcusGaius\ApiReports\Controllers\GoogleAuthController;

Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::get('/', [GoogleAuthController::class, 'home'])->name('home');
    Route::get('code', [GoogleAuthController::class, 'code'])->name('code');
    Route::get('retrieve', [GoogleAuthController::class, 'retrieve'])->name('retrieve');
});