<?php

use App\Http\Controllers\UserController;
use App\Livewire\CategoryCreate;
use App\Livewire\CategoryEdit;
use App\Livewire\CategoryList;
use App\Livewire\CostCreate;
use App\Livewire\CostEdit;
use App\Livewire\Home;
use App\Livewire\Statistic;
use App\Livewire\UserLogin;
use App\Models\Cost;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')
    ->get('/login', UserLogin::class)
    ->name('login');

Route::middleware('auth')->group(function () {
    Route::get('/', Home::class);

    Route::get('/categories', CategoryList::class);
    Route::get('/categories/{uuid}/edit', CategoryEdit::class);
    Route::get('/categories/create', CategoryCreate::class);

    Route::get('/costs/create', CostCreate::class);
    Route::get('/costs/{uuid}/edit', CostEdit::class);

    Route::get('/statistic', Statistic::class);

    Route::post('/logout', [UserController::class, 'logout']);
});
