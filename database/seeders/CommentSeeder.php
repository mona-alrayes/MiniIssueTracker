<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Issue;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $issues = Issue::all();
        $users = User::all();

        $commentTemplates = [
            'I can reproduce this issue on my local environment.',
            'Working on this now. Should have a fix ready by end of day.',
            'This is a duplicate of another issue we fixed last week.',
            'Can you provide more details about the steps to reproduce?',
            'I think we should prioritize this for the next sprint.',
            'Fixed in commit abc123. Please review.',
            'This requires changes to the database schema.',
            'I tested the fix and it works perfectly!',
            'We need to discuss this with the product team first.',
            'Added unit tests to cover this scenario.',
            'This is blocking other features. Marking as urgent.',
            'I have a different approach that might be more efficient.',
            'Documentation has been updated to reflect these changes.',
            'The issue is more complex than initially thought.',
            'Deployed to staging for testing.',
            'QA team confirmed this is resolved.',
            'We should add this to our regression test suite.',
            'This might affect performance. Need to benchmark.',
            'Great work on fixing this!',
            'Can we schedule a meeting to discuss the implementation?',
        ];

        foreach ($issues as $issue) {
            // Add 2-6 comments per issue
            $numComments = rand(2, 6);
            
            for ($i = 0; $i < $numComments; $i++) {
                Comment::create([
                    'body' => $commentTemplates[array_rand($commentTemplates)],
                    'user_id' => $users->random()->id,
                    'issue_id' => $issue->id,
                    'created_at' => now()->subDays(rand(0, 20)),
                    'updated_at' => now()->subDays(rand(0, 20)),
                ]);
            }
        }
    }
}
