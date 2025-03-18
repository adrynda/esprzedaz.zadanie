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
    return view('pets.index', ['petStatuses' => PetStatusEnum::cases()]);
});

Route::get('/pet/new', function () use ($formSelectsData) {
    return view('pets.new', $formSelectsData);
});

Route::get('/pet/{id}', function (int $id) use ($formSelectsData) {
    return view('pets.edit', array_merge($formSelectsData, ['id' => $id]));
});

Route::get('/pet/{id}/customUpdate', function (int $id) {
    return view('pets.customUpdate', ['id' => $id, 'petStatuses' => PetStatusEnum::cases()]);
});

Route::get('/pet/{id}/uploadImage', function (int $id) {
    return view('pets.uploadImage', ['id' => $id]);
});
