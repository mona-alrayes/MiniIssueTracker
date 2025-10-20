<?php

namespace App\Services\Project;

use App\Models\User;
use App\Models\Project;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Service layer for project-related operations
 */
class ProjectService
{
    /**
     * Create a new project
     * 
     * @param array $data Project data
     * @return Project Newly created project
     */
    public function createProject(array $data): Project
    {
        return Project::create($data);
    }

    /**
     * Update an existing project
     * 
     * @param Project $project Project to update
     * @param array $data Updated project data
     * @return Project Updated project
     */
    public function updateProject(Project $project, array $data): Project
    {
        $project->update($data);
        return $project;
    }

    /**
     * Attach a user to a project with specified role
     * 
     * @param Project $project The project to attach to
     * @param array $userData ['user_id' => int, 'role' => string]
     * @return bool True on success
     * @throws ProjectOperationException
     */
    public function attachUsersToProject(Project $project, array $userData)
    {
        try {
            return DB::transaction(function () use ($project, $userData) {
                $project->users()->attach($userData['user_id'], [
                    'role' => $userData['role']
                ]);
                return true;
            });
        } catch (\Exception $e) {
            throw new ApiException('Failed to attach user to project', 500);
        }
    }

    /**
     * Detach a user from a project
     * 
     * @param Project $project The project to detach from
     * @param int $userId ID of user to detach
     * @return bool True on success
     * @throws ProjectOperationException
     */
    public function detachUserFromProject(Project $project, int $userId)
    {
        try {
            return DB::transaction(function () use ($project, $userId) {
                $project->users()->detach($userId);
                return true;
            });
        } catch (\Exception $e) {
            throw new ApiException('Failed to detach user from project', 500);
        }
    }

    /**
     * Record API contribution time 
     */
    public function recordContribution(Project $project, User $user, float $minutes): void
    {
        try {
            $hours = $minutes / 60;

            $project->users()->updateExistingPivot($user->id, [
                'contribution_hours' => DB::raw("ROUND(contribution_hours + $hours, 4)"),
                'last_activity' => now()
            ]);
        } catch (\Exception $e) {

            Log::error('Error recording contribution', [
                'project_id' => $project->id,
                'user_id' => $user->id,
                'minutes' => $minutes,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get formatted contributions report
     */
    public function getContributionsReport(Project $project): array
    {
        return $project->users()
            ->orderByPivot('contribution_hours', 'desc')
            ->get()
            ->map(function ($user) {
                return [
                    'user' => $user->only(['id', 'name', 'email']),
                    'hours' => $user->pivot->contribution_hours,
                    'last_active' => $user->pivot->last_activity->diffForHumans(),
                    'formatted_time' => $user->pivot->contribution_time
                ];
            })->toArray();
    }
}
