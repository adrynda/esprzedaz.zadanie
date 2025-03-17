<?php

namespace App\Services;

use App\Enums\PetStatusEnum;
use App\DTOs\PetCustomUpdateDTO;
use App\Models\Category;
use App\Models\Pet;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;

class PetService
{
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

    public function customUpdate(PetCustomUpdateDTO $petCustomUpdateDTO): Pet
    {
        $pet = Pet::find($petCustomUpdateDTO->id);

        if (isset($petCustomUpdateDTO->payload['name'])) {
            $pet->name = $petCustomUpdateDTO->payload['name'];
        }

        if (isset($petCustomUpdateDTO->payload['status'])) {
            $pet->status = PetStatusEnum::from($petCustomUpdateDTO->payload['status'])->value;
        }
        
        $pet->save();

        return $pet;
    }

    public function findByStatus(PetStatusEnum $petStatus): Collection
    {
        return Pet::where('status', '=', $petStatus->value)->get();
    }

    public function findByTags(array $tags): Collection
    {
        return Pet::whereHas('tags', function ($query) use ($tags) {
                $query->whereIn('name', $tags);
            })
            ->get();
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
}
