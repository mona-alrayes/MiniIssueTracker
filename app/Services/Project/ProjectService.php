<?php

namespace App\Services\Project;

use App\Exceptions\ProjectOperationException;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProjectService
{
    public function createProject(array $data): Project
    {
        return Project::create($data);
    }

    public function updateProject(Project $project, array $data): Project
    {
        $project->update($data);
        return $project;
    }

    public function attachUserstoProject(Project $project, array $data)
    {
        try{
         return DB::transaction(function () use ($project, $data) {
            $project->users()->sync($data);
            return $project->load('users')->users;
         });
        }catch(Exception $e){
        throw new ProjectOperationException('Failed to attach users to project');
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
            \Log::error("Contribution tracking failed: {$e->getMessage()}");
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