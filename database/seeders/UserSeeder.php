<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Alice Test',
                'email' => 'alice@example.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Bob Test',
                'email' => 'bob@example.com',
                'password' => bcrypt('password'),
            ],
        ];

        foreach ($users as $userData) {
            \App\Models\User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        // Optionally, create more random users for testing
        \App\Models\User::factory(3)->create();
    }
}
