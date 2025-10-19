<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\AddingUserToProjectRequest;
use App\Http\Requests\Project\RemoveUserToProjectRequest;
use App\Models\Project;
use App\Services\Project\ProjectService;

/**
 * Controller for managing project-user relationships
 */
class ProjectUserController extends Controller
{
    /** @var ProjectService */
    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    /**
     * Add a user to a project
     * 
     * @param AddingUserToProjectRequest $request Validated request containing user_id and role
     * @param Project $project The project to add the user to
     * @return \Illuminate\Http\JsonResponse
     */
    public function addingUserToProject(AddingUserToProjectRequest $request, Project $project)
    {
        $validatedRequest = $request->validated();
        $this->projectService->attachUsersToProject($project, $validatedRequest);
        return self::success($validatedRequest, 'User added to project successfully', 201);
    }

    /**
     * Remove a user from a project
     * 
     * @param RemoveUserToProjectRequest $request Validated request containing user_id
     * @param Project $project The project to remove the user from
     * @return \Illuminate\Http\JsonResponse
     */
    public function removingUserFromProject(RemoveUserToProjectRequest $request, Project $project)
    {
        $validatedRequest = $request->validated();
        $this->projectService->detachUserFromProject($project, $validatedRequest['user_id']);
        return self::success(null, 'User removed from project successfully', 200);
    }
}
