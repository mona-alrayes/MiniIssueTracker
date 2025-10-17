<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Http\Requests\StoreIssueRequest;
use App\Http\Requests\UpdateIssueRequest;

class IssueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        self::paginated(Issue::paginate(10) , null ,'issues been retrived successfully', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIssueRequest $request)
    {
        $issue = Issue::create($request->validated());
        return self::success($issue , 'issue been created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Issue $issue)
    {
        return self::success($issue , 'issue been retrived successfully', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIssueRequest $request, Issue $issue)
    {
        $issue->update($request->validated());
        return self::success($issue , 'issue been updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Issue $issue)
    {
        $issue->delete();
        return self::success(null , 'issue been deleted successfully', 200);
    }
}
