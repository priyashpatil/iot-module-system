<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Http\Controllers\DashboardController::class)->name('dashboard');

Route::post('/modules', [\App\Http\Controllers\ModuleController::class, 'store'])->name('modules.store');
Route::get('/modules/{module}', [\App\Http\Controllers\ModuleController::class, 'show'])->name('modules.show');
