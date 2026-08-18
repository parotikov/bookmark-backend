<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\BookmarkController;
use App\Http\Controllers\Api\LabelController;
use App\Http\Controllers\AuthController;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/url', [BookmarkController::class, 'show']);

Route::get('/bookmarks', [BookmarkController::class, 'index']);
Route::get('/bookmarks/{q}', [BookmarkController::class, 'search']);
Route::post('/bookmarks', [BookmarkController::class, 'store']);
Route::post('/bookmarks/disable', [BookmarkController::class, 'disable']);

Route::get('/labels', [LabelController::class, 'index']);
Route::get('/labels/stats', [LabelController::class, 'stats']);
Route::get('/labels/{q}', [LabelController::class, 'search']);
Route::get('/labels/edit/{q}', [LabelController::class, 'show']);
Route::post('/labels/edit/{q}', [LabelController::class, 'update']);


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);

});