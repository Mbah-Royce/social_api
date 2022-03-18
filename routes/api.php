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
/** Auth Routes */
Route::POST('user/register',[App\Http\Controllers\Auth\LoginController::class,'register']);
Route::group(['prefix' => 'user'], function(){
    Route::POST('login', [App\Http\Controllers\Auth\LoginController::class,'login']);
});
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
    Route::POST('student-seed/{schoolId}',[App\Http\Controllers\Admin\StudentController::class,'create']);
    Route::POST('student/{schoolId}/{classRoomID}' ,[App\Http\Controllers\Admin\StudentController::class,'formCreate']);
    Route::PUT('student/{schoolId}/{classRoomID}' ,[App\Http\Controllers\Admin\StudentController::class,'update']);
    Route::GET('student/{schoolId}/{classRoomID}' ,[App\Http\Controllers\Admin\StudentController::class,'index']);
    Route::GET('student/{schoolId}/{classRoomID}/{studentId}' ,[App\Http\Controllers\Admin\StudentController::class,'show']);
    Route::DELETE('student/{schoolId}/{classRoomID}/{studentId}' ,[App\Http\Controllers\Admin\StudentController::class,'destroy']);

    /** Courses CRUD routes*/
    Route::POST('course/{schoolId}/{classRoomID}',[App\Http\Controllers\Admin\CourseController::class,'create']);
    Route::PUT('course/{schoolId}/{classRoomID}',[App\Http\Controllers\Admin\CourseController::class,'update']);
    Route::GET('course/{schoolId}/{classRoomID}',[App\Http\Controllers\Admin\CourseController::class,'index']);
    Route::GET('course/{schoolId}/{classRoomID}/{courseId}',[App\Http\Controllers\Admin\CourseController::class,'show']);
    Route::DELETE('course/{schoolId}/{classRoomID}/{courseId}',[App\Http\Controllers\Admin\CourseController::class,'destroy']);

});
    /** MISCEO administrator Routes */
Route::group(['prefix' => 'misceo-admin', 'middleware' => ['auth:sanctum']], function () {
    
    Route::POST('school',[App\Http\Controllers\SuperAdmin\SchoolController::class,'create']);
    Route::GET('school',[App\Http\Controllers\SuperAdmin\SchoolController::class,'index']);
    Route::POST('student-seed/{id}',[App\Http\Controllers\SuperAdmin\StudentController::class,'create']);
    

});

