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

Route::post('/login',[\App\Http\Controllers\Api\UserController::class,'login']);
Route::group(['middleware'=>'auth:api'],function (){
    Route::get('/getUser',[\App\Http\Controllers\Api\UserController::class,'index']);
    Route::get('/showUser/{id}',[\App\Http\Controllers\Api\UserController::class,'show']);
    Route::post('/updateUser/{id}',[\App\Http\Controllers\Api\UserController::class,'update']);
    Route::post('/newUser',[\App\Http\Controllers\Api\UserController::class,'store']);
    Route::delete('/deleteUser/{id}',[\App\Http\Controllers\Api\UserController::class,'destroy']);
    Route::post('/newCategory',[\App\Http\Controllers\Api\CategoryController::class,'store']);
    Route::get('/allCategory',[\App\Http\Controllers\Api\CategoryController::class,'index']);
    Route::get('/showCategory/{id}',[\App\Http\Controllers\Api\CategoryController::class,'show']);
    Route::delete('/deleteCategory/{id}',[\App\Http\Controllers\Api\CategoryController::class,'destroy']);
    Route::post('/updateCategory/{id}',[\App\Http\Controllers\Api\CategoryController::class,'update']);
    Route::post('/newMovie',[\App\Http\Controllers\Api\MovieController::class,'store']);
    Route::get('/allMovie',[\App\Http\Controllers\Api\MovieController::class,'index']);
    Route::post('/updateMovie/{id}',[\App\Http\Controllers\Api\MovieController::class,'update']);
    Route::get('/showMovie/{id}',[\App\Http\Controllers\Api\MovieController::class,'show']);
    Route::delete('/deleteMovie/{id}',[\App\Http\Controllers\Api\MovieController::class,'destroy']);
    Route::get('/searchMovieName/{name}',[\App\Http\Controllers\Api\MovieController::class,'searchMovieName']);
    Route::get('/movieMonth',[\App\Http\Controllers\Api\MovieController::class,'movieMonth']);
//    Route::get('/searchMovieMonth/{date}',[\App\Http\Controllers\Api\MovieController::class,'searchMovieMonth']);
    Route::get('/searchMovieDaye',[\App\Http\Controllers\Api\MovieController::class,'searchMovieDaye']);
});

