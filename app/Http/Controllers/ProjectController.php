<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return self::paginated(Project::paginate(10), null, 'Projects retrieved successfully', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        $project = Project::create($request->validated());
        return self::success($project, 'Project created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return self::success($project, 'Project retrieved successfully', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->update($request->validated());
        return self::success($project, 'Project updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return self::success(null, 'Project deleted successfully', 200);
    }

    /**
     * Get opened issues for a project
     * 
     * @urlParam project int required The ID of the project. Example: 1
     * @response 200 {
     *   "data": [{"id": 1, "title": "..."}],
     *   "message": "Opened issues retrieved successfully",
     *   "status": 200
     * }
     * @param Project $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function openedIssues(Project $project)
    {
        return self::paginated($project->openedIssues()->paginate(10), null, 'Opened issues retrieved successfully', 200);
    }

    /**
     * Get opened issues for all projects
     * 
     * @response 200 {
     *   "data": [{"id": 1, "title": "..."}],
     *   "message": "Opened issues retrieved successfully",
     *   "status": 200
     * }
     * @return \Illuminate\Http\JsonResponse
     */
    public function openedIssuesForAllProjects()
    {
        return self::paginated(Project::withOpenedIssues()->paginate(10), null, 'Opened issues retrieved successfully', 200);
    }
}
