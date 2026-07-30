<?php

namespace Database\Factories;

use App\Models\Continent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Continent>
 */
class ContinentFactory extends Factory
{
    protected $model = Continent::class;

    public function definition(): array
    {
        $continents = [
            'Asia',
            'Africa',
            'Europe',
            'North America',
            'South America',
            'Australia',
            'Antarctica',
        ];

        return [
            'name' => fake()->unique()->randomElement($continents),
        ];
    }
}
