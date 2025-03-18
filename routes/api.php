<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PetController;
use App\Http\Middleware\ApiKeyMiddleware;

Route::middleware([ApiKeyMiddleware::class])->group(function () {
    // Potrzebne do testów na Postman'ie
    Route::get('/token', function () {
        return csrf_token(); 
    });

    Route::post('/pet/{petId}/uploadImage', [PetController::class, 'uploadImage']);
    Route::post('/pet', [PetController::class, 'store']);
    Route::put('/pet', [PetController::class, 'update']);
    Route::get('/pet/findByStatus', [PetController::class, 'findByStatus']);
    Route::get('/pet/findByTags', [PetController::class, 'findByTags']);
    Route::get('/pet/{petId}', [PetController::class, 'show']);
    Route::post('/pet/{petId}', [PetController::class, 'customUpdate']);
    Route::delete('/pet/{petId}', [PetController::class, 'destroy']);
});
