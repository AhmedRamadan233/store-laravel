<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       // Create a sample category with no parent
    //    DB::table('categories')->insert([
    //     'name' => 'Sample Category',
    //     'slug' => 'sample-category',
    //     'description' => 'This is a sample category description.',
    //     'image' => 'sample-image.jpg',
    //     'status' => 'active',
    //     'created_at' => now(),
    //     'updated_at' => now(),
    // ]);

    //     // Create another category with a parent
    //     DB::table('categories')->insert([
    //         'name' => 'Subcategory',
    //         'slug' => 'subcategory',
    //         'description' => 'This is a subcategory description.',
    //         'image' => 'subcategory-image.jpg',
    //         'status' => 'active',
    //         'parent_id' => 1, // Set the parent_id to link it to the first category
    //         'created_at' => now(),
    //         'updated_at' => now(),
    //     ]);
    Category::factory(100)->create();

    }
}
