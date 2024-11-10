<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Http\Controllers\DashboardController::class)->name('dashboard');

Route::post('/modules', [\App\Http\Controllers\ModuleController::class, 'store'])->name('modules.store');
Route::get('/modules/{module}', [\App\Http\Controllers\ModuleController::class, 'show'])->name('modules.show');
Route::get('/modules/{module}/metrics', [\App\Http\Controllers\ModuleLogsController::class, 'metrics'])->name('modules.metrics');
Route::get('/modules/{module}/failures', [\App\Http\Controllers\ModuleLogsController::class, 'failures'])->name('modules.failures');
Route::post('/modules/{module}/sensors', [\App\Http\Controllers\SensorController::class, 'store'])->name('module.sensors.store');
Route::put('/modules/{module}/toggle', [\App\Http\Controllers\ModuleController::class, 'toggle'])->name('modules.toggle');
