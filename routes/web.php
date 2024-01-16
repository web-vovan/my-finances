<?php

use App\Http\Controllers\UserController;
use App\Livewire\CategoryCreate;
use App\Livewire\CategoryEdit;
use App\Livewire\CategoryList;
use App\Livewire\CreateCost;
use App\Livewire\Home;
use App\Livewire\UserLogin;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')
    ->get('/login', UserLogin::class)
    ->name('login');

Route::middleware('auth')->group(function () {
    Route::get('/', Home::class);

    Route::get('/categories', CategoryList::class);
    Route::get('/categories/{id}/edit', CategoryEdit::class);
    Route::get('/categories/create', CategoryCreate::class);

    Route::get('/create-cost', CreateCost::class);

    Route::post('/logout', [UserController::class, 'logout']);
});
