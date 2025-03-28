<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('category_id')->constrained('categories');
            $table->string('name');
            $table->enum('status', ['available', 'pending', 'sold']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
