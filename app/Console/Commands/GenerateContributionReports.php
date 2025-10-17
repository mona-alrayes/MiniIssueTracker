<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;
use App\Services\Project\ProjectService;

class GenerateContributionReports extends Command
{
    protected $signature = 'reports:contributions';
    protected $description = 'Generate daily contribution reports';

    public function handle(ProjectService $service)
    {
        Project::each(function ($project) use ($service) {
            $report = $service->getContributionsReport($project);
            
            // Store report (example: JSON file)
            $path = storage_path("app/reports/project_{$project->id}_contributions.json");
            file_put_contents($path, json_encode($report, JSON_PRETTY_PRINT));
            
            $this->info("Generated report for project: {$project->name}");
        });
    }
}
