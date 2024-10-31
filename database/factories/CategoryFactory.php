<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {$faker = \Faker\Factory::create();
        return [
            'category_name' => $faker->randomElement(['Office Supplies', 'Electronics', 'Printing Paper', 'Emergency Kits']),
            'brand_name' => $faker->randomElement(['Hammermill', 'HP', 'Energizer', 'First Aid Only', 'Johnson & Johnson', 'Red Cross']),
        ];
    }
}
