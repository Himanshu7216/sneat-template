<?php

namespace Database\Factories;

use App\Models\Products;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Products>
 */
class ProductsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Products::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => ucwords(fake()->words(3, true)),
            'sku' => 'SKU-' . strtoupper(fake()->unique()->bothify('??###??#')),
            'description' => fake()->sentence(),
            'color' => fake()->safeColorName(),
            'size' => fake()->randomElement(['S', 'M', 'L', 'XL', 'XXL']),
            'image' => ['https://via.placeholder.com/640x480.png/0044cc?text=Product+Image'],
            'price' => fake()->randomFloat(2, 10, 1000),
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
        ];
    }
}
