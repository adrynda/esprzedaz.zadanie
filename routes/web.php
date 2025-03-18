<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PetController;

use App\Models\Category;
use App\Enums\PetStatusEnum;
use App\Models\Tag;

$formSelectsData = [
    'categories' => Category::all(),
    'petStatuses' => PetStatusEnum::cases(),
    'tags' => Tag::all(),
];

Route::get('/', function () {
    return redirect('/pet');
});

Route::get('/pet', function () {
    return view('pets.index');
});

Route::get('/pet/new', function () use ($formSelectsData) {
    return view('pets.new', $formSelectsData);
});

Route::get('/pet/{id}', function (int $id) use ($formSelectsData) {
    return view('pets.edit', array_merge($formSelectsData, ['id' => $id]));
});





Route::get('/api/token', function () {
    return csrf_token(); 
});

Route::post('/api/pet/{petId}/uploadImage', [PetController::class, 'uploadImage']);
Route::post('/api/pet', [PetController::class, 'store']);
Route::put('/api/pet', [PetController::class, 'update']);
Route::get('/api/pet/findByStatus', [PetController::class, 'findByStatus']);
Route::get('/api/pet/findByTags', [PetController::class, 'findByTags']);
Route::get('/api/pet/{petId}', [PetController::class, 'show']);
Route::post('/api/pet/{petId}', [PetController::class, 'customUpdate']);
Route::delete('/api/pet/{petId}', [PetController::class, 'destroy']);
