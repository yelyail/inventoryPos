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
            'fullname' => 'staff123',
            'username' => 'staff123',
            'job_title' => 'officeStaff',
            'phone_number' => '9128349129',
            'password' => Hash::make('staff123'),
            'archived'=>0
       ]);
    }
}
