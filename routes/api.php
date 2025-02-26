<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//Route::post('/login', [AuthController::class, 'login']);
Route::get('/token', [AuthController::class, 'getToken']);
Route::get('/users', [UserController::class, 'index'])->name('get-users');
Route::get('/users/{user}', [UserController::class, 'show']);
Route::get('/positions', [UserController::class, 'positions']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/users', [UserController::class, 'store']);
});
