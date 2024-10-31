<?php

namespace Database\Factories;

use App\Models\category;
use App\Models\product;
use App\Models\supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

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
    protected $model = product::class;

    public function definition(): array
    {
        $faker = \Faker\Factory::create('en_US');
        
        // Generate a random model name
        $modelName = $this->getModelName($faker->randomElement(['Bond Paper', 'HP Printer', 'First Aid Kit', 'Emergency Preparedness Kit']));
        
        return [
            'supplier_ID' => supplier::factory(), 
            'category_id' => category::factory(), 
            'product_name' => $modelName,  // Use the generated model name here
            'product_description' => $this->getDescription($modelName), // Pass model name to getDescription
            'unitPrice' => $faker->randomFloat(2, 1, 1000),
            'added_date' => $faker->date(),
            'typeOfUnit' => $faker->randomElement(['ream', 'pcs']),
            'product_image' => "https://via.placeholder.com/640x480.png?text=Computer+Part",         
        ];
    }

    private function getModelName($modelName)
    {
        return $modelName;
    }

    private function getDescription($modelName)
    {
        switch ($modelName) {
            case 'Bond Paper':
                return 'A high-quality bond paper ideal for printing documents, letters, and reports. It offers a smooth finish and is suitable for both inkjet and laser printers.';
            case 'HP Printer':
                return 'A reliable inkjet printer with wireless capabilities, perfect for home or office use. Known for its high-quality prints and user-friendly interface.';
            case 'First Aid Kit':
                return 'Comprehensive first aid kit containing essential medical supplies for treating minor injuries. Suitable for home, travel, and workplace use.';
            case 'Emergency Preparedness Kit':
                return 'A complete emergency kit designed for natural disasters and unexpected events. Contains food, water, medical supplies, and survival tools.';
            default:
                return 'wala';
        }
    }

}
