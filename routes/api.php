<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServiceController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('services')->group(function (){
    Route::get('',[ServiceController::class,'index']);
    Route::get('{id}',[ServiceController::class,'show']);
    Route::post('',[ServiceController::class,'store']);
    Route::put('{service}',[ServiceController::class,'update']);
    Route::delete('{service}',[ServiceController::class,'destroy']);
});

Route::prefix('auth')->group(function(){
    Route::post('register',[AuthController::class,'register']);
    Route::post('login',[AuthController::class,'login']);
    Route::get('logout',[AuthController::class,'logout'])
        ->middleware('auth:sanctum');
    Route::get('user',[AuthController::class,'user'])
        ->middleware('auth:sanctum');
});

Route::prefix('posts')->group(function (){
    Route::get('',[PostController::class,'index']);
    Route::get('{id}',[PostController::class,'show']);
    Route::post('',[PostController::class,'store']);
    Route::put('{post}',[PostController::class,'update']);
    Route::delete('{post}',[PostController::class,'destroy']);
});

Route::prefix('roles')->group(function (){
    Route::get('',[RoleController::class,'index']);
    Route::get('{id}',[RoleController::class,'show']);
    Route::post('',[RoleController::class,'store']);
    Route::put('{role}',[RoleController::class,'update']);
    Route::delete('{role}',[RoleController::class,'destroy']);
});
