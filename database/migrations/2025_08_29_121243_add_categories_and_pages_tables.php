<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // === CATEGORIES ===
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')
                  ->nullable()
                  ->constrained('categories')
                  ->nullOnDelete();
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->text('meta_description')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->integer('sort')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // === PAGES ===
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->text('meta_description')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->integer('sort')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // === CATEGORY_PRODUCT (pivot) ===
        Schema::create('category_product', function (Blueprint $table) {
            $table->foreignId('category_id')
                  ->constrained('categories')
                  ->cascadeOnDelete();
            $table->foreignId('product_id')
                  ->constrained('products')
                  ->cascadeOnDelete();

            $table->primary(['category_id', 'product_id']);
        });

        // === PAGE_PRODUCT (pivot) ===
        Schema::create('page_product', function (Blueprint $table) {
            $table->foreignId('page_id')
                  ->constrained('pages')
                  ->cascadeOnDelete();
            $table->foreignId('product_id')
                  ->constrained('products')
                  ->cascadeOnDelete();

            $table->primary(['page_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_product');
        Schema::dropIfExists('category_product');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('categories');
    }
};
