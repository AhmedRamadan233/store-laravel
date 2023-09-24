<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->words(5, true);
        $storeIds = Store::pluck('id'); // Get an array of all store IDs
        $categoryIds = Category::pluck('id'); // Get an array of all store IDs

        return [
            'store_id' =>$this->faker->randomElement($storeIds), // Select a random store ID from the array
            'category_id' => $this->faker->randomElement($categoryIds), // Select a random store ID from the array
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->sentence,
            'image' => $this->faker->imageUrl,
            // 'product_code' => $this->faker->imageUrl(100,100),
            'price' => $this->faker->randomFloat(1, 1, 499),
            'compare_price' => $this->faker->optional()->randomFloat(1, 500, 9999),
            'options' => json_encode(['option1' => 'value1', 'option2' => 'value2']),
            'rating' => $this->faker->randomFloat(2, 0, 5),
            'features' => $this->faker->boolean,
            // 'status' => $this->faker->randomElement(['active', 'draft', 'archived']),
        ];
    }
}
