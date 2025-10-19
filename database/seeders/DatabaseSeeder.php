<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ProjectSeeder::class,
            LabelSeeder::class,
            IssueSeeder::class,
            CommentSeeder::class,
        ]);

        $this->command->info('Database seeded successfully with realistic data!');
        $this->command->info('You can login with:');
        $this->command->info('Email: admin@issuetracker.com');
        $this->command->info('Password: password123');
    }
}
