<?php

namespace Database\Factories;

use App\Models\product;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Serial>
 */
class SerialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $this->faker = \Faker\Factory::create('en_US');
        return [
            'product_id' => product::factory(), 
            'serial_number' => $this->generateSerialNumber(), 
            'status' => $this->faker->randomElement([0, 1]), 
        ];
    }
    private function generateSerialNumber(){
        $letters = strtoupper(Str::random(3));
        $numbers = $this->faker->randomNumber(6, true); 

        return $letters . $numbers; 
    }
}

