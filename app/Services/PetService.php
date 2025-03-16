<?php

namespace App\Services;

use App\Enums\PetStatusEnum;
use App\Models\Category;
use App\Models\Pet;
use App\Models\Tag;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;

class PetService
{
    public function uploadImage(): void
    {

    }

    public function create(array $payload): Pet
    {
        $pet = new Pet();
        
        $this->fillPetModel($pet, $payload);

        return $pet;
    }

    public function update(array $payload): Pet
    {
        $pet = Pet::find($payload['id']);
        
        $this->fillPetModel($pet, $payload);

        return $pet;
    }

    public function findByStatus(PetStatusEnum $petStatus): array
    {
        return Pet::where('status', '=', $petStatus->value)->findAll();
    }

    private function fillPetModel(Pet &$pet, array $payload): void
    {
        $pet->category_id = Category::find($payload['category']['id'])->id;
        $pet->name = $payload['name'];
        $pet->status = PetStatusEnum::from($payload['status'])->value;
        $pet->save();

        $tagIds = [];
        foreach ($payload['tags'] as $tagPayload) {
            $tagIds[] = Tag::find($tagPayload['id'])?->id;
        }
        $pet->tags()->sync(array_filter($tagIds));
    }

    public function getById(int $id): Pet
    {
        return Pet::getById($id);
    }

    public function remove(string $id): void
    {
        $pet = Pet::getById($id);

        foreach ($pet->photoUrls() as $photoUrl) {
            if (file_exists($photoUrl->name)) {
                unlink($photoUrl->name);
            }

            $photoUrl->delete();
        }

        $pet->delete();
    }
}
