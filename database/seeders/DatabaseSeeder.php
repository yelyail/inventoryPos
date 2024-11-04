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
            'fullname' => 'Deib',
            'username' => 'deib',
            'job_title' => 'officestaff',
            'phone_number' => '9128349129',
            'password' => Hash::make('deib1234'),
            'archived'=>0
       ]);
    }
}
