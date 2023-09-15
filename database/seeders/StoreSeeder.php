<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('stores')->insert([
            'name' => 'Sample Store',
            'slug' => 'sample-store',
            'description' => 'This is a sample store description.',
            'logo_image' => 'sample-logo.jpg',
            'cover_image' => 'sample-cover.jpg',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
