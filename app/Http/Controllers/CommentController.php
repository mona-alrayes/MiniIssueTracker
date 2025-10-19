<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Issue;
use App\Models\Project;
use App\Services\Comment\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct(protected CommentService $commentService) {}

    public function index(Project $project, Issue $issue): JsonResponse
    {
        $comments = $this->commentService->getComments($project, $issue);
        return self::paginated($comments, null, 'Comments retrieved successfully', 200);
    }

    public function store(StoreCommentRequest $request, Project $project, Issue $issue): JsonResponse
    {
        $comment = $this->commentService->addComment($request->validated(), $project, $issue, Auth::user());
        return self::success($comment, 'Comment created successfully', 201);
    }

    public function show(Project $project, Issue $issue, Comment $comment): JsonResponse
    {
        $comment = $this->commentService->showComment($project, $issue, $comment);
        return self::success($comment, 'Comment retrieved successfully', 200);
    }

    public function update(UpdateCommentRequest $request, Project $project, Issue $issue, Comment $comment): JsonResponse
    {
        $updated = $this->commentService->updateComment($request->validated(), $project, $issue, $comment);
        return self::success($updated, 'Comment updated successfully', 200);
    }

    public function destroy(Project $project, Issue $issue, Comment $comment): JsonResponse
    {
        $this->commentService->deleteComment($project, $issue, $comment);
        return self::success(null, 'Comment deleted successfully', 204);
    }

    public function IssueCommentsCount(Project $project, Issue $issue): JsonResponse
    {
        $count = $issue->commentsCount()->get();
        return self::success($count, 'Comments count retrieved successfully', 200);
    }
}
