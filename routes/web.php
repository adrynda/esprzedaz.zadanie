<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\PetController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/token', function () {
    return csrf_token(); 
});

// Route::get('/api/pet', [PetController::class, 'index']);
Route::post('/api/pet/{petId}/uploadImage', [PetController::class, 'uploadImage']);
Route::post('/api/pet', [PetController::class, 'store']);
Route::put('/api/pet', [PetController::class, 'update']);
Route::get('/api/pet/findByStatus', [PetController::class, 'findByStatus']);
Route::get('/api/pet/findByTags', [PetController::class, 'findByTags']);
Route::get('/api/pet/{petId}', [PetController::class, 'show']);
Route::post('/api/pet/{petId}', [PetController::class, 'customUpdate']);
Route::delete('/api/pet/{petId}', [PetController::class, 'destroy']);
