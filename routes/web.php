<?php

use App\Livewire\CreateCost;
use App\Livewire\Home;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class);
Route::get('/create-cost', CreateCost::class);
