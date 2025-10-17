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
            
            // More precise API timing (uses request duration)
            $durationMinutes = microtime(true) - LARAVEL_START;
            $durationMinutes = round($durationMinutes / 60, 2); // Convert to minutes
            
            // Minimum 1 minute threshold for API calls
            if ($durationMinutes >= 1) {
                app(ProjectService::class)->recordContribution(
                    $project,
                    $user,
                    $durationMinutes
                );
            }
        }
        
        return $response;
    }
}
