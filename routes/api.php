<?php

use Illuminate\Support\Facades\Route;

Route::get('modules/{module}', \App\Http\Controllers\Api\ModuleController::class);
