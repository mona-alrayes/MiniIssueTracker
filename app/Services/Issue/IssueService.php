<?php

namespace App\Services\Issue;

use App\Models\User;
use App\Models\Project;
use App\Exceptions\ApiException;

class IssueService
{
    /**
     * Get count of completed issues for a user
     * 
     * @param User $user
     * @return int
     * @throws ApiException
     */
    public function getCompletedIssuesCount(User $user): int
    {
        try {
            return User::withCompletedIssuesCount()
                ->where('id', $user->id)
                ->first()
                ->completed_issues_count;

        } catch (\Exception $e) {
            throw new ApiException('Failed to get completed issues count', 500);
        }
    }

    /**
     * Get opened issues for a project
     * 
     * @param Project $project
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * @throws ApiException
     */
    public function getOpenIssuesForProjects(Project $project)
    {
        try {
            return $project::withOpenedIssues()->paginate(10);
        } catch (\Exception $e) {
            throw new ApiException('Failed to get opened issues for project', 500);
        }
    }
}
