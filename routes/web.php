<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Http\Controllers\DashboardController::class)->name('dashboard');

Route::post('/modules', [\App\Http\Controllers\ModuleController::class, 'store'])->name('modules.store');
Route::get('/modules/{module}', [\App\Http\Controllers\ModuleController::class, 'show'])->name('modules.show');
Route::post('/modules/{module}/sensors', [\App\Http\Controllers\SensorController::class, 'store'])->name('module.sensors.store');

Route::put('/modules/{module}/activate', [\App\Http\Controllers\ModuleController::class, 'activate'])->name('modules.activate');
Route::put('/modules/{module}/deactivate', [\App\Http\Controllers\ModuleController::class, 'deactivate'])->name('modules.deactivate');
