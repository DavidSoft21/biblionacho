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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::delete('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::group(['prefix' => 'users', 'middleware' => ['auth:sanctum', 'checkAdmin']], function () {
    Route::get('index', [UserController::class, 'index']);
    Route::post('store', [UserController::class, 'store']);
    Route::get('show/{user}', [UserController::class, 'show']);
    Route::put('update/{user}', [UserController::class, 'update']);
    Route::delete('destroy/{id}', [UserController::class, 'destroy']);
});

Route::group(['prefix' => 'books'], function () {
    Route::get('index', [BookController::class, 'index']);
    Route::get('show/{book}', [BookController::class, 'show']);
});

Route::group(['prefix' => 'books', 'middleware' => ['auth:sanctum', 'checkAdmin']], function () {
    Route::post('store', [BookController::class, 'store']);
    Route::put('update/{book}', [BookController::class, 'update']);
    Route::delete('destroy/{book}', [BookController::class, 'destroy']);
});

Route::group(['prefix' => 'lendbooks'], function () {
    Route::get('index', [LendBookController::class, 'index'])->middleware('auth:sanctum', 'checkAdmin');
    Route::post('store', [LendBookController::class, 'store'])->middleware('auth:sanctum');
    Route::get('show/{lendBook}', [LendBookController::class, 'show'])->middleware('auth:sanctum');
    Route::put('update/{lendBook}', [LendBookController::class, 'update'])->middleware('auth:sanctum', 'checkAdmin');
    Route::delete('destroy/{lendBook}', [LendBookController::class, 'destroy'])->middleware('auth:sanctum', 'checkAdmin');
});
