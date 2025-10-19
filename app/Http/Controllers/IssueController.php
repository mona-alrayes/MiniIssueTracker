<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\User;
use App\Models\Project;
use App\Services\Issue\IssueService;
use App\Http\Requests\StoreIssueRequest;
use App\Http\Requests\UpdateIssueRequest;

/**
 * @group Issue Management
 * 
 * APIs for managing issues
 */
class IssueController extends Controller
{
    protected $issueService;

    public function __construct(IssueService $issueService)
    {
        $this->issueService = $issueService;
    }

    /**
     * Display a paginated list of issues
     *
     * @response 200 {
     *   "data": ["..."],
     *   "message": "issues been retrieved successfully",
     *   "status": 200
     * }
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        self::paginated(Issue::paginate(10), null, 'issues been retrieved successfully', 200);
    }

    /**
     * Create a new issue
     * 
     * @bodyParam title string required The issue title. Example: Bug Fix
     * @bodyParam description string required The issue description. Example: Fix the login page
     * @bodyParam status string required The issue status. Example: open
     * @bodyParam priority string required The issue priority. Example: high
     * @bodyParam assigned_to int The ID of assigned user. Example: 1
     * @bodyParam project_id int required The project ID. Example: 1
     * 
     * @response 201 {
     *   "data": {"id": 1, "title": "..."},
     *   "message": "issue been created successfully",
     *   "status": 201
     * }
     * @param StoreIssueRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreIssueRequest $request)
    {
        $issue = Issue::create($request->validated());
        return self::success($issue, 'issue been created successfully', 201);
    }

    /**
     * Display a specific issue
     * 
     * @urlParam issue int required The ID of the issue. Example: 1
     * @response 200 {
     *   "data": {"id": 1, "title": "..."},
     *   "message": "issue been retrieved successfully",
     *   "status": 200
     * }
     * @param Issue $issue
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Issue $issue)
    {
        return self::success($issue, 'issue been retrieved successfully', 200);
    }

    /**
     * Update an existing issue
     * 
     * @urlParam issue int required The ID of the issue. Example: 1
     * @bodyParam title string The issue title. Example: Updated Bug
     * @bodyParam description string The issue description.
     * @bodyParam status string The issue status.
     * 
     * @response 200 {
     *   "data": {"id": 1, "title": "..."},
     *   "message": "issue been updated successfully",
     *   "status": 200
     * }
     * @param UpdateIssueRequest $request
     * @param Issue $issue
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateIssueRequest $request, Issue $issue)
    {
        $issue->update($request->validated());
        return self::success($issue, 'issue been updated successfully', 200);
    }

    /**
     * Delete an issue
     * 
     * @urlParam issue int required The ID of the issue. Example: 1
     * @response 200 {
     *   "data": null,
     *   "message": "issue been deleted successfully",
     *   "status": 200
     * }
     * @param Issue $issue
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Issue $issue)
    {
        $issue->delete();
        return self::success(null, 'issue been deleted successfully', 200);
    }

    /**
     * Get all open issues (status: open, in_progress, or blocked)
     * 
     * @response 200 {
     *   "data": [{"id": 1, "title": "..."}],
     *   "message": "Opened issues retrieved successfully",
     *   "status": 200
     * }
     * @return \Illuminate\Http\JsonResponse
     */
    public function openedIssues()
    {
        return self::success(
            Issue::open()->with(['project', 'assignee'])->paginate(10),
            'Opened issues retrieved successfully',
            200
        );
    }

    /**
     * Get all urgent issues (high priority + due within 48 hours)
     * 
     * @response 200 {
     *   "data": [{"id": 1, "title": "..."}],
     *   "message": "Urgent issues retrieved successfully",
     *   "status": 200
     * }
     * @return \Illuminate\Http\JsonResponse
     */
    public function urgentIssues()
    {
        return self::success(
            Issue::urgent()->with(['project', 'assignee'])->paginate(10),
            'Urgent issues retrieved successfully',
            200
        );
    }

    /**
     * Get count of completed issues for a user
     * 
     * @urlParam user int required The ID of the user. Example: 1
     * @response 200 {
     *   "data": {"completed_issues_count": 5},
     *   "message": "Completed issues count retrieved successfully",
     *   "status": 200
     * }
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function completedIssuesCountOfUser(User $user)
    {
        $count = $this->issueService->getCompletedIssuesCount($user);
        return self::success(
            ['completed_issues_count' => $count],
            'Completed issues count retrieved successfully',
            200
        );
    }

    /**
     * Get opened issues for a specific project
     * 
     * @urlParam project int required The ID of the project. Example: 1
     * @response 200 {
     *   "data": [{"id": 1, "title": "..."}],
     *   "message": "Opened issues of project retrieved successfully",
     *   "status": 200
     * }
     * @param Project $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function openIssuesOfProject(Project $project)
    {
        $issues = $this->issueService->getOpenIssuesForProject($project);
        return self::success(
            $issues,
            'Opened issues of project retrieved successfully',
            200
        );
    }
}
