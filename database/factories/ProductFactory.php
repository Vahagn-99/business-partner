<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
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
        return [
            'name' => $this->faker->title()
            , 'price' => $this->faker->randomFloat(max: 5000)
            , 'description' => $this->faker->realText
            , 'in_stock' => $this->faker->boolean
            , 'category_id' => Category::factory()->create()->getKey()
        ];
    }
}
