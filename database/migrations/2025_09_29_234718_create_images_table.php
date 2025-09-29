<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();

            // Универсальная полиморфная связь
            $table->morphs('imageable'); // imageable_id + imageable_type

            // Основные данные
            $table->string('path');                // путь к файлу
            $table->string('title')->nullable();   // заголовок
            $table->string('caption')->nullable(); // подпись
            $table->string('alt')->nullable();     // alt-текст
            $table->string('type')->nullable();    // main, gallery, thumbnail, banner и т.д.
            $table->integer('sort')->default(0);   // порядок сортировки
            $table->boolean('is_active')->default(true);

            // Технические характеристики
            $table->unsignedInteger('filesize')->nullable();
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->string('hash', 64)->nullable();

            // Для расширения
            $table->json('meta')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
