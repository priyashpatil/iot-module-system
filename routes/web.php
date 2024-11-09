<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Http\Controllers\DashboardController::class)->name('dashboard');
Route::post('/modules', [\App\Http\Controllers\ModuleController::class, 'store'])->name('modules.store');
