<?php

namespace App\Services\Project;

use App\Models\Project;

class ProjectService
{
    public function createProject(array $data): Project
    {
        return Project::create($data);
    }

    public function updateProject(Project $project, array $data): Project
    {
        $project->update($data);
        return $project;
    }

   
}