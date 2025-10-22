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

            // Check if user is attached to project
            $exists = $project->users()->where('user_id', $user->id)->exists();
            
            Log::info('Checking user attachment', [
                'project_id' => $project->id,
                'user_id' => $user->id,
                'exists' => $exists ? 'yes' : 'no'
            ]);
            
            if ($exists) {
                // Get current value before update
                $currentPivot = DB::table('project_user')
                    ->where('project_id', $project->id)
                    ->where('user_id', $user->id)
                    ->first();
                
                Log::info('Current pivot data', [
                    'current_hours' => $currentPivot->contribution_hours ?? 'null',
                    'adding_hours' => $hours
                ]);
                
                // Update existing pivot record - using integer minutes instead
                $affectedRows = $project->users()->updateExistingPivot($user->id, [
                    'contribution_hours' => DB::raw("contribution_hours + " . (int)$minutes),
                    'last_activity' => now()
                ]);
                
                Log::info('Contribution recorded', [
                    'project_id' => $project->id,
                    'user_id' => $user->id,
                    'minutes' => $minutes,
                    'affected_rows' => $affectedRows
                ]);
            } else {
                Log::warning('User not attached to project', [
                    'project_id' => $project->id,
                    'user_id' => $user->id,
                    'message' => 'Cannot record contribution - user must be added to project first'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error recording contribution', [
                'project_id' => $project->id,
                'user_id' => $user->id,
                'minutes' => $minutes,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
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
                    'last_active' => $user->pivot->last_activity ? \Carbon\Carbon::parse($user->pivot->last_activity)->diffForHumans() : 'Never',
                    'formatted_time' => $user->pivot->contribution_time
                ];
            })->toArray();
    }
}
