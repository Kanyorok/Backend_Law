<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CaseController;
use App\Http\Controllers\ReservationController;

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
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
  
    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);
    });
});

Route::prefix('cases')->group(function(){
    Route::get('/', [CaseController::class, 'index']);
    Route::get('{id}', [CaseController::class, 'show']);
    Route::post('/', [CaseController::class, 'store']);
    Route::put('{id}', [CaseController::class, 'update']);

});

Route::prefix('reservations')->group(function(){
    Route::get('/', [ReservationController::class, 'index']);
    Route::get('{id}', [ReservationController::class, 'show']);
    Route::delete('{id}', [ReservationController::class, 'delete']);
    Route::post('/', [ReservationController::class, 'store']);
});
