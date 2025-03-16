<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pet extends Model
{
    protected $table = 'pets';

    public $timestamps = false;
    
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function photoUrls(): array
    {
        return $this->hasMany(PhotoUrl::class, 'pet_id', 'id');
    }
    
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'pets_tags', 'pet_id', 'tag_id');
    }

    public static function getById(int $id): self
    {
        $model = self::find($id);

        if (empty($model)) {
            throw new ModelNotFoundException();
        }

        return $model;
    }
}
