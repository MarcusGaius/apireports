<?php

use Illuminate\Support\Facades\Route;
use MarcusGaius\ApiReports\Controllers\GoogleAuthController;
use MarcusGaius\ApiReports\Controllers\YouTubeReportController;
use MarcusGaius\ApiReports\Controllers\FunnelReportController;

// Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
Route::middleware('web')->group(function () {
    Route::post('youtube', [YouTubeReportController::class, 'ytvd'])->name('ytvd');
    Route::post('google', [FunnelReportController::class, 'funnelData'])->name('funnelData');
    Route::get('/', [GoogleAuthController::class, 'home'])->name('home');
    Route::get('code', [GoogleAuthController::class, 'code'])->name('code');
});
    
// });