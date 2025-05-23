<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('filename');  // Имя файла изображения
            $table->string('path');      // Путь к изображению относительно public
            $table->timestamps();        // created_at и updated_at

            // Можно добавить дополнительные поля при необходимости
            // $table->integer('width')->nullable();
            // $table->integer('height')->nullable();
            // $table->string('mime_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};