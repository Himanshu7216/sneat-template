<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;
use App\Models\Country;
use App\Models\Continent;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        City::factory()->create([
            'name' => 'Vadodara',

            'country_id' => function () {

                return Country::factory()->create([
                    'name' => 'India',

                    'continent_id' => function () {

                        return Continent::factory()->create([
                            'name' => 'Asia',
                        ])->id;
                    },

                ])->id;
            },
        ]);
    }
}
