<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pets_tags', function (Blueprint $table) {
            $table->foreignId('pet_id')->constrained('pets');
            $table->foreignId('tag_id')->constrained('tags');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('pets_tags');
    }
};
