<?php

namespace Database\Seeders;

use App\Models\Issue;
use App\Models\Project;
use App\Models\User;
use App\Models\Label;
use App\Enums\StatusType;
use App\Enums\PriorityType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IssueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();
        $users = User::all();
        $labels = Label::all();

        $issueTemplates = [
            ['title' => 'Login page not responsive on mobile', 'description' => 'The login page layout breaks on mobile devices with screen width less than 768px.', 'status' => StatusType::Open, 'priority' => PriorityType::High],
            ['title' => 'Add dark mode support', 'description' => 'Implement dark mode theme across the entire application with user preference storage.', 'status' => StatusType::Open, 'priority' => PriorityType::Medium],
            ['title' => 'Payment gateway integration', 'description' => 'Integrate Stripe payment gateway for processing customer payments.', 'status' => StatusType::Inprogress, 'priority' => PriorityType::Highest],
            ['title' => 'Database query optimization', 'description' => 'Optimize slow queries in the reports module. Current response time is 5+ seconds.', 'status' => StatusType::Open, 'priority' => PriorityType::High],
            ['title' => 'Update API documentation', 'description' => 'Update API documentation to reflect recent endpoint changes and new authentication flow.', 'status' => StatusType::Open, 'priority' => PriorityType::Low],
            ['title' => 'Fix memory leak in background jobs', 'description' => 'Background jobs are consuming excessive memory causing server crashes.', 'status' => StatusType::Inprogress, 'priority' => PriorityType::Highest],
            ['title' => 'Add user profile picture upload', 'description' => 'Allow users to upload and manage their profile pictures with image cropping.', 'status' => StatusType::Open, 'priority' => PriorityType::Medium],
            ['title' => 'Implement email notifications', 'description' => 'Send email notifications for important events like password reset, new messages, etc.', 'status' => StatusType::Completed, 'priority' => PriorityType::Medium],
            ['title' => 'Fix broken links in footer', 'description' => 'Several links in the footer section are returning 404 errors.', 'status' => StatusType::Open, 'priority' => PriorityType::Low],
            ['title' => 'Add export to CSV functionality', 'description' => 'Users should be able to export data tables to CSV format.', 'status' => StatusType::Inprogress, 'priority' => PriorityType::Medium],
            ['title' => 'Security vulnerability in file upload', 'description' => 'File upload feature allows execution of malicious scripts. Needs immediate attention.', 'status' => StatusType::Open, 'priority' => PriorityType::Highest],
            ['title' => 'Improve search functionality', 'description' => 'Add filters and advanced search options to the main search feature.', 'status' => StatusType::Open, 'priority' => PriorityType::Medium],
            ['title' => 'Add two-factor authentication', 'description' => 'Implement 2FA using TOTP for enhanced security.', 'status' => StatusType::Inprogress, 'priority' => PriorityType::High],
            ['title' => 'Dashboard loading too slow', 'description' => 'Dashboard takes 8+ seconds to load. Need to optimize queries and caching.', 'status' => StatusType::Open, 'priority' => PriorityType::High],
            ['title' => 'Add pagination to user list', 'description' => 'User list page crashes when there are more than 1000 users.', 'status' => StatusType::Completed, 'priority' => PriorityType::Medium],
        ];

        $counter = 1;
        foreach ($projects as $project) {
            // Create 5-8 issues per project
            $numIssues = rand(5, 8);
            
            for ($i = 0; $i < $numIssues; $i++) {
                $template = $issueTemplates[array_rand($issueTemplates)];
                $creator = $users->random();
                $assignee = $users->random();

                // Create due window for some issues
                $dueWindow = null;
                if (rand(0, 1)) {
                    $dueWindow = [
                        'due_at' => now()->addDays(rand(1, 30))->toDateTimeString(),
                        'remind_before' => rand(1, 7) . ' days',
                    ];
                }

                $issue = Issue::create([
                    'title' => $template['title'],
                    'description' => $template['description'],
                    'status' => $template['status'],
                    'priority' => $template['priority'],
                    'project_id' => $project->id,
                    'created_by' => $creator->id,
                    'assigned_to' => $assignee->id,
                    'code' => $project->code . '-' . $counter++,
                    'due_window' => $dueWindow,
                    'status_change_at' => $template['status'] !== StatusType::Open ? now()->subDays(rand(1, 10)) : null,
                ]);

                // Attach 1-4 random labels to each issue
                $randomLabels = $labels->random(rand(1, 4));
                foreach ($randomLabels as $label) {
                    DB::table('issue_label')->insert([
                        'issue_id' => $issue->id,
                        'label_id' => $label->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
