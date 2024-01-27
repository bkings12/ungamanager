<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MonitorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'indexdash'])->name('admin.dashboard');
    Route::get('/admin/devices', [DeviceController::class, 'index'])->name('admin.devices');
    Route::get('/admin/createdevice', [DeviceController::class, 'createdevice'])->name('admin.createdevice');
    Route::post('/admin/devices', [DeviceController::class, 'store'])->name('devices.store');
    Route::get('/admin/devices/{device}', [DeviceController::class, 'show'])->name('devices.show');
    Route::get('/admin/devices/{device}/edit', [DeviceController::class, 'edit'])->name('devices.edit');
    Route::put('/admin/devices/{device}', [DeviceController::class, 'update'])->name('devices.update');
    Route::delete('/admin/devices/{device}', [DeviceController::class, 'destroy'])->name('devices.destroy');
    Route::post('/admin/editdevices', [DeviceController::class, 'editdevice'])->name('admin.editdevice');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/admin/monitor', [MonitorController::class, 'inview'])->name('admin.monitor');
    Route::get('/admin/generate', [MonitorController::class, 'showGenerateReportForm'])->name('admin.showGenerate');
Route::post('/admin/generate', [MonitorController::class, 'generateReport'])->name('admin.generate');
});

require __DIR__.'/auth.php';
