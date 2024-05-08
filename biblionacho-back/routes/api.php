<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\LendBookController;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login'])->withoutMiddleware('throttle');
    Route::post('register', [AuthController::class, 'register'])->withoutMiddleware('throttle');
    Route::delete('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::group(['prefix' => 'users', 'middleware' => ['auth:sanctum', 'checkAdmin']], function () {
    Route::get('index', [UserController::class, 'index'])->withoutMiddleware('throttle');
    Route::post('store', [UserController::class, 'store'])->withoutMiddleware('throttle');
    Route::get('show/{user}', [UserController::class, 'show'])->withoutMiddleware('throttle');
    Route::put('update/{user}', [UserController::class, 'update'])->withoutMiddleware('throttle');
    Route::delete('destroy/{id}', [UserController::class, 'destroy'])->withoutMiddleware('throttle');
});

Route::group(['prefix' => 'books'], function () {
    Route::get('index', [BookController::class, 'index'])->withoutMiddleware('throttle');
    Route::get('show/{book}', [BookController::class, 'show'])->withoutMiddleware('throttle');
});

Route::group(['prefix' => 'books', 'middleware' => ['auth:sanctum', 'checkAdmin']], function () {
    Route::post('store', [BookController::class, 'store'])->withoutMiddleware('throttle');
    Route::put('update/{book}', [BookController::class, 'update'])->withoutMiddleware('throttle');
    Route::delete('destroy/{book}', [BookController::class, 'destroy'])->withoutMiddleware('throttle');
});

Route::group(['prefix' => 'lendbooks'], function () {
    Route::get('index', [LendBookController::class, 'index'])->middleware('auth:sanctum', 'checkAdmin')->withoutMiddleware('throttle');
    Route::get('showLendBookUsers', [LendBookController::class, 'showLendBookUser'])->middleware('auth:sanctum', 'checkAdmin')->withoutMiddleware('throttle');
    Route::post('store', [LendBookController::class, 'store'])->middleware('auth:sanctum')->withoutMiddleware('throttle');
    Route::get('show/{lendBook}', [LendBookController::class, 'show'])->withoutMiddleware('throttle');
    Route::put('update/{lendBook}', [LendBookController::class, 'update'])->middleware('auth:sanctum', 'checkAdmin')->withoutMiddleware('throttle');
    Route::delete('destroy/{lendBook}', [LendBookController::class, 'destroy'])->middleware('auth:sanctum', 'checkAdmin')->withoutMiddleware('throttle');
});
