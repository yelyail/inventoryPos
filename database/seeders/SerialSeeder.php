<?php

namespace Database\Seeders;

use App\Models\serial;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SerialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        serial::factory()->count(10)->create();
    }
}
