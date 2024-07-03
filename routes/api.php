<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Unauthorized;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::middleware([Unauthorized::class, 'auth:sanctum'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::put('user/{user}/update', [UserController::class, 'editUpdate']);
    Route::delete('user/{user}/delete', [UserController::class, 'deleteUser']);
    Route::get('user-active', [UserController::class, 'showLoginUser']);

    Route::get('news', [NewsController::class, 'getNews']);
    Route::get('news/{news}', [NewsController::class, 'News']);
    Route::post('news/create', [NewsController::class, 'createNews']);
    Route::put('news/{news}/update', [NewsController::class, 'updateNews']);
    Route::delete('news/{news}/delete', [NewsController::class, 'deleteNews']);
});
