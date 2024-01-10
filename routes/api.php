<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')
    ->post('/logout', [UserController::class, 'logout']);

Route::middleware('auth:sanctum')
    ->resource('categories', CategoryController::class);

Route::middleware('auth:sanctum')
    ->resource('costs', CostController::class);
