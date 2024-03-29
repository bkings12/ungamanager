<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MonitorController;
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
Route::get('traffic-data', [DashboardController::class, 'getTrafficData']);
Route::get('resource', [DashboardController::class, 'resource']);
Route::get('monitor', [MonitorController::class,'monitor']);
Route::get('interface-names', [DashboardController::class, 'getInterfaceNames']);


