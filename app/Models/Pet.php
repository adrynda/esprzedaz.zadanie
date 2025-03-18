<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pet extends Model
{
    public $timestamps = false;

    protected $table = 'pets';

    protected $with = ['category', 'tags'];

    protected $appends = ['photoUrls'];
    
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function photoUrls(): HasMany
    {
        return $this->hasMany(PhotoUrl::class, 'pet_id', 'id');
    }
    
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'pets_tags', 'pet_id', 'tag_id');
    }

    public function getPhotoUrlsAttribute()
    {
        return $this->photoUrls()->pluck('name')->toArray();
    }
}
