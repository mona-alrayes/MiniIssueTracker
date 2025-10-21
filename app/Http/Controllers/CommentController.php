<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Issue;
use App\Models\Project;
use App\Services\Comment\CommentService;
use Illuminate\Http\JsonResponse;
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

    public function update(UpdateCommentRequest $request, Comment $comment): JsonResponse
    {
        $comment->update($request->validated());
        return self::success($comment, 'Comment updated successfully', 200);
    }

    public function destroy(Comment $comment): JsonResponse
    {
        $comment->delete();
        return self::success(null, 'Comment deleted successfully', 200);
    }

    public function IssueCommentsCount(Project $project, Issue $issue): JsonResponse
    {
        // Use the commentsCount scope on the Issue query builder
        $issueWithCount = Issue::commentsCount()
            ->find($issue->id);

        return self::success([
            'comments_count' => $issueWithCount->comments_count
        ], 'Comments count retrieved successfully', 200);
    }
}
