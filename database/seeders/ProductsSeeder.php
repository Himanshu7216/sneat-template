<?php

namespace Database\Seeders;

use App\Models\Products;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 categories if none exist
        if (Category::count() === 0) {
            Category::factory()->count(10)->create();
        }

        $categoryIds = Category::pluck('id')->toArray();

        // Create 300 products distributed across existing categories
        Products::factory()
            ->count(30000)
            ->state(fn () => [
                'category_id' => fake()->randomElement($categoryIds),
            ])
            ->create();
    }
}
