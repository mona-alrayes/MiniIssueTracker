<?php

namespace App\Services\Comment;

use App\Exceptions\ApiException;
use App\Models\Comment;
use App\Models\Issue;
use App\Models\Project;
use App\Models\User;

class CommentService
{
    public function getComments(Project $project, Issue $issue): \Illuminate\Database\Eloquent\Collection
    {
        if ($issue->project_id !== $project->id) {
            throw new ApiException('Issue not found in this project', 404);
        }

        return $issue->comments()->with('user:id,name')->paginate(10);
    }

    public function addComment(array $data, Project $project, Issue $issue, User|int $user): Comment
    {
        if ($issue->project_id !== $project->id) {
            throw new ApiException('Issue not found in this project', 404);
        }

        $userId = $user instanceof User ? $user->id : $user;

        return $issue->comments()->create([
            'body' => $data['body'],
            'user_id' => $userId
        ])->load('user:id,name');
    }

    public function showComment(Project $project, Issue $issue, Comment $comment): Comment
    {
        if ($issue->project_id !== $project->id || $comment->issue_id !== $issue->id) {
            throw new ApiException('Comment not found for this issue', 404);
        }

        return $comment->load('user:id,name');
    }

    public function updateComment(array $data, Project $project, Issue $issue, Comment $comment): Comment
    {
        if ($issue->project_id !== $project->id || $comment->issue_id !== $issue->id) {
            throw new ApiException('Comment not found for this issue', 404);
        }

        $comment->update(['body' => $data['body'] ?? $comment->body]);

        return $comment->refresh()->load('user:id,name');
    }

    public function deleteComment(Project $project, Issue $issue, Comment $comment): void
    {
        if ($issue->project_id !== $project->id || $comment->issue_id !== $issue->id) {
            throw new ApiException('Comment not found for this issue', 404);
        }

        $comment->delete();
    }
}
