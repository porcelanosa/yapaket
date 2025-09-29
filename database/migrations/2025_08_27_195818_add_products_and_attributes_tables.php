<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('name')->unique();               // системное имя/артикул
            $table->string('title');                        // заголовок (человекочитаемый)
            $table->string('meta_description')->nullable(); // мета-тег description
            $table->string('short_description')->nullable();// короткое описание
            $table->text('description')->nullable();        // полное описание

            $table->integer('price')->default(0);           // цена
            $table->integer('sort')->default(0);            // порядок сортировки
            $table->tinyInteger('status')->default(1);      // статус (0 = скрыт, 1 = активен)

            $table->timestamps();
        });

        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();   // системное имя: size, material
            $table->string('label')->nullable();// человекочитаемое название
            $table->timestamps();
        });

        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                  ->constrained('products')
                  ->cascadeOnDelete();

            $table->foreignId('attribute_id')
                  ->constrained('attributes')
                  ->cascadeOnDelete();

            $table->string('value'); // значение атрибута

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_attributes');
        Schema::dropIfExists('products');
        Schema::dropIfExists('attributes');
    }
};
