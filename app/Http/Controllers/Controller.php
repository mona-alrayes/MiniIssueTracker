<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Project;

/**
 * Base controller for the application.
 */
abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function success($data = null, $message = 'Done Successfully!', int $status = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => trans($message),
            'data' => $data,
        ], $status);
    }

    public static function error($data = null, $message = 'Operation failed!', int $status = 400): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => trans($message),
            'data' => $data,
        ], $status);
    }

    /**
     * Paginated response helper.
     *
     * @param \Illuminate\Pagination\LengthAwarePaginator $paginator
     * @param string|null $resourceClass
     * @param string $message
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public static function paginated($paginator, ?string $resourceClass = null, string $message = '', int $status = 200): \Illuminate\Http\JsonResponse
    {
        // prefer getting the underlying collection from paginator
        $items = $paginator->getCollection();

        $transformedItems = is_null($resourceClass)
            ? $items
            : $resourceClass::collection($items);

        return response()->json([
            'status' => 'success',
            'message' => trans($message),
            'data' => $transformedItems,
            'pagination' => [
                'total' => $paginator->total(),
                'count' => $paginator->count(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'total_pages' => $paginator->lastPage(),
                'next_page_url' => $paginator->nextPageUrl(),
                'prev_page_url' => $paginator->previousPageUrl(),
            ],
        ], $status);
    }

    protected function resolveProject($project): Project
    {
        if ($project instanceof Project) {
            return $project;
        }

        return Project::findOrFail($project);
    }
}
