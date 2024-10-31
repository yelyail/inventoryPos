<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {        
        $faker = \Faker\Factory::create('en_US');
        return [
            'supplier_name' => $faker->company,
            'supplier_address' => $faker->address,
            'supplier_email' => $faker->unique()->safeEmail,
            'supplier_phone' => $faker->phoneNumber,
            'status' => $faker->randomElement([0, 1]),
        ];
    }
}
