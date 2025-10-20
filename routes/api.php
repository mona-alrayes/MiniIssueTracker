<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\IssueLabelController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectUserController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Authentication routes (public)
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Protected authentication routes
Route::middleware('auth:api')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/projects/all-opened-issue', [ProjectController::class, 'openedIssuesForAllProjects']);

    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('issues', IssueController::class);
    Route::apiResource('labels', LabelController::class);

    // Custom endpoints
    Route::get('/issues/opened', [IssueController::class, 'openedIssues']);
    Route::get('/issues/urgent', [IssueController::class, 'urgentIssues']);
    Route::get('/users/{user}/completed-issues-count', [IssueController::class, 'completedIssuesCountOfUser']);
    Route::get('/projects/{project}/opened-issue', [ProjectController::class, 'openedIssues']);

    // Project user management
    Route::post('projects/{project}/users', [ProjectUserController::class, 'addingUserToProject']);
    Route::delete('projects/{project}/users', [ProjectUserController::class, 'removingUserFromProject']);

    // Issue labels
    Route::post('projects/{project}/issues/{issue}/labels', [IssueLabelController::class, 'attach']);
    Route::delete('projects/{project}/issues/{issue}/labels/{label}', [IssueLabelController::class, 'detach']);
    Route::put('projects/{project}/issues/{issue}/labels', [IssueLabelController::class, 'sync']);

    // Comments (nested resource)
    Route::apiResource('projects.issues.comments', CommentController::class)
        ->only(['index', 'store', 'show', 'update', 'destroy'])
        ->shallow();

    Route::get('projects/{project}/issues/{issue}/comments-count', [CommentController::class, 'IssueCommentsCount']);
});
