<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('pet_id')->constrained('pets');
            $table->boolean('complete');
            $table->integer('quantity');
            $table->dateTime('ship_date');
            $table->enum('difficulty', ['placed', 'approved', 'delivered']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
