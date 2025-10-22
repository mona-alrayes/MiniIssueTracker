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
        
        \Log::info('TrackProjectActivity Middleware Executed', [
            'url' => $request->url(),
            'method' => $request->method(),
            'status' => $response->status(),
            'has_project' => $request->route('project') ? 'yes' : 'no',
            'has_user' => $request->user() ? 'yes' : 'no',
            'is_successful' => $response->isSuccessful() ? 'yes' : 'no'
        ]);
        
        // Only track successful API responses (2xx status)
        if ($response->isSuccessful() && $project = $request->route('project')) {
            $user = $request->user();
            
            if ($user) {
                // Track each successful request as 5 minutes of contribution
                // This simulates active work on the project
                $contributionMinutes = 5;
                
                \Log::info('Attempting to record contribution', [
                    'project_id' => $project->id,
                    'user_id' => $user->id,
                    'minutes' => $contributionMinutes
                ]);
                
                app(ProjectService::class)->recordContribution(
                    $project,
                    $user,
                    $contributionMinutes
                );
            } else {
                \Log::warning('No authenticated user for contribution tracking');
            }
        }
        
        return $response;
    }
}
