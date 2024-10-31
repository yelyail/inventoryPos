<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       User::create([
            'fullname' => 'Ariel July',
            'username' => 'admin',
            'job_title' => 'supervisor',
            'phone_number' => '9123456789',
            'password' => Hash::make('admin1234'),
            'archived'=>0
       ]);
    }
}
