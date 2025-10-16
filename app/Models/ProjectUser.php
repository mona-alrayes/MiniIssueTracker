<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProjectUser extends Pivot
{
    protected $fillable =[
        'project_id',
        'user_id',
        'role',
        'contribution_hours',
        'last_activity'
    ];


    
}
