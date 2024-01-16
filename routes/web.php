<?php

use App\Livewire\CategoryCreate;
use App\Livewire\CategoryEdit;
use App\Livewire\CategoryList;
use App\Livewire\CreateCost;
use App\Livewire\Home;
use App\Livewire\UserLogin;
use Illuminate\Support\Facades\Route;

Route::get('/login', UserLogin::class)
    ->middleware('guest')
    ->name('login');

Route::middleware('auth')->group(function () {
    Route::get('/', Home::class);

    Route::get('/categories', CategoryList::class);
    Route::get('/categories/{id}/edit', CategoryEdit::class);
    Route::get('/categories/create', CategoryCreate::class);

    Route::get('/create-cost', CreateCost::class);
});


