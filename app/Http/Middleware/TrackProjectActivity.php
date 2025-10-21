<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\Project;
use App\Services\Project\ProjectService;

class TrackProjectActivity
{
    /**
     * Handle API requests and track project activity
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        
        // Only track successful API responses (2xx status)
        if ($response->isSuccessful() && $project = $request->route('project')) {
            $user = $request->user();
            
            if ($user) {
                // Track each successful request as 5 minutes of contribution
                // This simulates active work on the project
                $contributionMinutes = 5;
                
                app(ProjectService::class)->recordContribution(
                    $project,
                    $user,
                    $contributionMinutes
                );
            }
        }
        
        return $response;
    }
}
