<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TodoController;
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


Route::post('v1/auth/login', [AuthController::class, "postLogin"]);

Route::middleware('auth:api')->group(function () {
    //AUTH
    Route::get('v1/auth/user', [AuthController::class, "getUser"]);
    Route::post('v1/auth/logout', [AuthController::class, "postSignout"]);
    Route::post('v1/auth/refresh', [AuthController::class, "postRefresh"]);
    //TODOS
    Route::get('v1/todos', [TodoController::class, "getTodos"]);
    Route::get('v1/todo/{id}', [TodoController::class, "getTodo"]);
    Route::post('v1/todo', [TodoController::class, "postTodo"]);
    Route::put('v1/todo/{id}', [TodoController::class, "putTodo"]);
    Route::delete('v1/todo/{id}', [TodoController::class, "delTodo"]);
});
