<?php

namespace App\Services\Issue;

use App\Models\User;
use App\Models\Project;
use App\Exceptions\IssueOperationException;

class IssueService
{
    /**
     * Get count of completed issues for a user
     * 
     * @param User $user
     * @return int
     * @throws IssueOperationException
     */
    public function getCompletedIssuesCount(User $user): int
    {
        try {
            return $user->withCompletedIssuesCount()->completed_issues_count;
        } catch (\Exception $e) {
            throw new IssueOperationException('Failed to get completed issues count');
        }
    }

    /**
     * Get opened issues for a project
     * 
     * @param Project $project
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * @throws IssueOperationException
     */
    public function getOpenIssuesForProject(Project $project)
    {
        try {
            return $project->openedIssues()
                ->with(['project', 'assignee'])
                ->paginate(10);
        } catch (\Exception $e) {
            throw new IssueOperationException('Failed to get opened issues for project');
        }
    }
}
