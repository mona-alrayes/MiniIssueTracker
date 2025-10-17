<?php

namespace App\Http\Controllers;

use App\Services\IssueLabelService;
use App\Models\{Project, Issue, Label};
use App\Http\Requests\IssueLabel\AttachLabelsRequest;

/**
 * Controller for managing issue-label relationships
 */
class IssueLabelController extends Controller
{
    /**
     * @param IssueLabelService $service
     */
    public function __construct(private IssueLabelService $service)
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Attach one or more labels to the issue without removing existing ones.
     * @param AttachLabelsRequest $request
     * @param Project $project
     * @param Issue $issue
     * @return \Illuminate\Http\JsonResponse
     */
    public function attach(AttachLabelsRequest $request, Project $project, Issue $issue)
    {
        $labelIds = $request->validated()['label_ids'];
        $labels = $this->service->attach($issue, $labelIds);
        return self::success($labels ,'labels been attached to the issue successfully' , 200);
    }

    /**
     * Sync labels: replace all existing labels with the provided ones.
     * @param AttachLabelsRequest $request
     * @param Project $project
     * @param Issue $issue
     * @return \Illuminate\Http\JsonResponse
     */
    public function sync(AttachLabelsRequest $request, Project $project, Issue $issue)
    {
        $labelIds = $request->validated()['label_ids'];
        $labels = $this->service->sync($issue, $labelIds);
        return self::success($labels , 'labels been sync with issue successfully' , 200);
    }

    /**
     * Detach a single label from the issue.
     * @param Project $project
     * @param Issue $issue
     * @param int $labelId
     * @return \Illuminate\Http\JsonResponse
     */
    public function detach(Project $project, Issue $issue, int $labelId)
    {
        $this->service->detach($issue, $labelId);
        return self::success(null ,'labels been removed from the issue successfully', 204);
    }
    
}
