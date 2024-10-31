<?php

namespace Database\Factories;

use App\Models\inventory;
use App\Models\product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inventory>
 */
class InventoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = inventory::class;
    public function definition(): array
    {
        $faker = \Faker\Factory::create('en_US');

        // Generate warranty information
        $warrantyPeriod = $faker->numberBetween(1, 24); 
        $warrantyUnit = $faker->randomElement(['days', 'weeks', 'months']); 

        return [
            'product_id' => Product::factory(), 
            'date_arrived' => $faker->date(),
            'warranty_supplier' => "{$warrantyPeriod} {$warrantyUnit}", 
            'status' => $faker->randomElement(['approve','pending']), 
        ];
    }
}
