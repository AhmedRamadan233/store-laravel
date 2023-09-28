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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->unsignedBigInteger('product_code_id')->nullable()->unique();
            $table->foreign('product_code_id')->references('id')->on('product_codes')->onDelete('set null');         
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->float('price')->default(0);
            $table->float('compare_price')->nullable();
            $table->json('options')->nullable();
            $table->float('rating')->default(0);
            $table->boolean('features')->default(0);
            $table->enum('status', ['active', 'draft', 'archived'])->default('active');
            $table->string('product_code')->nullable()->unique(); // Add this line
            $table->timestamps();
            $table->softDeletes();
            

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
