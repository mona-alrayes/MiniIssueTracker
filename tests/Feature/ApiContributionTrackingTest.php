<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiContributionTrackingTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_activity_tracking()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $project->users()->attach($user);

        $response = $this->actingAs($user)
            ->getJson("/api/projects/{$project->id}");

        $response->assertOk();
        $this->assertDatabaseHas('project_user', [
            'project_id' => $project->id,
            'user_id' => $user->id,
            'contribution_hours' => 0.0167 // ~1 minute
        ]);
    }
}
