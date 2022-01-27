<?php

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

    /** School administrator Routes */
Route::group(['prefix' => 'school-admin', 'middleware' => ['auth:sanctum']], function () {
    
    /** ClassRoom CRUD routes*/
    Route::POST('class-room/{id}',[App\Http\Controllers\Admin\ClassRoomController::class,'create']);
    Route::GET('class-rooms/{id}',[App\Http\Controllers\Admin\ClassRoomController::class,'index']);
    Route::GET('class-rooms/{id}',[App\Http\Controllers\Admin\ClassRoomController::class,'show']);
    Route::DELETE('class-room/{id}',[App\Http\Controllers\Admin\ClassRoomController::class,'destroy']);
    Route::PATCH('class-room/{id}',[App\Http\Controllers\Admin\ClassRoomController::class,'update']);

    /** Student CRUD routes*/

});

