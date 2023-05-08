<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@antmediahost.com',
            'nomor' => '081234567',
            'username' => 'admin',
            'password' => bcrypt('1q2w3e4r5t'),
            'admin' => 1
        ]);
    }
}
