<?php

namespace Database\Seeders;

use App\Models\supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        supplier::factory()->count(10)->create();
    }
}
