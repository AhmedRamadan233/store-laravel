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
        Schema::create('stores', function (Blueprint $table) {
                       // id => bigint , unsigned , auto_increment , primary_key
                       $table->id();
                       $table->string('name'); // varchar(255) if write txt is not add volum as (255)
                       $table->string('slug')->unique(); // value of unique ->null
                       $table->string('description')->nullable();
                       $table->string('logo_image')->nullable();
                       $table->string('cover_image')->nullable();
                       $table->enum('status' , ['active','inactive','archived'])->default('active');
                       // 2 column : created at && updated at 
                       $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
