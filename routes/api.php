<?php

use App\Http\Controllers\IssueController;
use App\Http\Controllers\IssueLabelController;
use App\Http\Controllers\LabelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectUserController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::apiResource('projects', ProjectController::class);
Route::apiResource('users' , UserController::class);
Route::apiResource('issues' , IssueController::class);
Route::apiResource('labels', LabelController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('projects/{project}/users', [ProjectUserController::class, 'addingUserToProject']);
    Route::delete('projects/{project}/users', [ProjectUserController::class, 'removingUserFromProject']);
    
    Route::post('projects/{project}/issues/{issue}/labels', [IssueLabelController::class, 'attach']);
    Route::delete('projects/{project}/issues/{issue}/labels/{label}', [IssueLabelController::class, 'detach']);
    Route::put('projects/{project}/issues/{issue}/labels', [IssueLabelController::class, 'sync']);
});
