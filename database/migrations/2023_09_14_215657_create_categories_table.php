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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')
                ->nullable() //constraintلازم قبل ال 
                ->constrained('categories', 'id')  
                ->nullOnDelete(); // null on delete  is not  allowed for categories  columns  
                //->cascadeOnDelete() //  cascade delete  all children        
               // ->restrictOnDelete();//delete categories from parent table
            $table->string('name')->nullable(); // or ->default(null)
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->string('image')->nullable();
            $table->enum('status' , ['active','inactive','archive'])->default('active');
               
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
