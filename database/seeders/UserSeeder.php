<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@issuetracker.com',
            'password' => Hash::make('password123'),
        ]);

        // Create team members
        $users = [
            ['name' => 'John Doe', 'email' => 'john.doe@issuetracker.com'],
            ['name' => 'Jane Smith', 'email' => 'jane.smith@issuetracker.com'],
            ['name' => 'Mike Johnson', 'email' => 'mike.johnson@issuetracker.com'],
            ['name' => 'Sarah Williams', 'email' => 'sarah.williams@issuetracker.com'],
            ['name' => 'David Brown', 'email' => 'david.brown@issuetracker.com'],
            ['name' => 'Emily Davis', 'email' => 'emily.davis@issuetracker.com'],
            ['name' => 'Chris Wilson', 'email' => 'chris.wilson@issuetracker.com'],
            ['name' => 'Lisa Anderson', 'email' => 'lisa.anderson@issuetracker.com'],
            ['name' => 'Tom Martinez', 'email' => 'tom.martinez@issuetracker.com'],
            ['name' => 'Anna Taylor', 'email' => 'anna.taylor@issuetracker.com'],
        ];

        foreach ($users as $userData) {
            User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('password123'),
            ]);
        }
    }
}
