<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\PetController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', [PetController::class, 'index']);