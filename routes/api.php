<?php

use App\Http\Controllers\SpotController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\SocietyController;
use App\Http\Controllers\VaccinationController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(["prefix" => "/v1"], function(){
    Route::group(["prefix" => "/auth"], function(){
        Route::post('/login', [SocietyController::class, 'login']);
        Route::post('/logout', [SocietyController::class, 'logout']);
    });

    Route::post('/consultations', [ConsultationController::class, 'store']);
    Route::get('/consultations', [ConsultationController::class, 'index']);
    Route::get('/spots', [SpotController::class, 'index']);
    Route::post('/vaccinations', [VaccinationController::class, 'store']);
    Route::get('/vaccinations', [VaccinationController::class, 'index']);
});
