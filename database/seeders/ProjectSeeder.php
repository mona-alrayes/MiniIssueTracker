<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            [
                'name' => 'E-Commerce Platform',
                'description' => 'A modern e-commerce platform with shopping cart, payment integration, and order management.',
                'code' => 'ECOM',
            ],
            [
                'name' => 'Mobile Banking App',
                'description' => 'Secure mobile banking application with account management, transfers, and bill payments.',
                'code' => 'MBANK',
            ],
            [
                'name' => 'CRM System',
                'description' => 'Customer Relationship Management system for tracking leads, contacts, and sales pipeline.',
                'code' => 'CRM',
            ],
            [
                'name' => 'Healthcare Portal',
                'description' => 'Patient portal for appointment scheduling, medical records, and telemedicine.',
                'code' => 'HEALTH',
            ],
            [
                'name' => 'Learning Management System',
                'description' => 'Online learning platform with courses, quizzes, and student progress tracking.',
                'code' => 'LMS',
            ],
        ];

        $users = User::all();
        $roles = ['developer', 'manager', 'tester'];

        foreach ($projects as $projectData) {
            $project = Project::create($projectData);

            // Attach random users to each project with different roles
            $selectedUsers = $users->random(rand(3, 6));
            
            foreach ($selectedUsers as $user) {
                DB::table('project_user')->insert([
                    'project_id' => $project->id,
                    'user_id' => $user->id,
                    'role' => $roles[array_rand($roles)],
                    'contribution_hours' => rand(10, 200),
                    'last_activity' => now()->subDays(rand(0, 30)),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
