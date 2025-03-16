<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    protected $table = 'pets';

    public $timestamps = false;
    
    public function category()
    {
        return $this->belongsTo(Categoru::class, 'category_id', 'id');
    }

    public function photoUrls()
    {
        return $this->hasMany(PhotoUrl::class, 'pet_id', 'id');
    }
    
    public function tags()
    {
        return $this->belongsToMany(Tags::class, 'pets_tags', 'tag_id', 'pet_id');
    }
}
