<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Carbon\Carbon;

class ProjectUser extends Pivot
{

    protected $fillable =[
        'project_id',
        'user_id',
        'role',
        'contribution_hours',
        'last_activity'
    ];

    protected $casts = [
        'last_activity' => 'datetime'
    ];

    /**
     * Get formatted contribution hours as hours:minutes
     * 
     * @param string|null $format Optional format string (default: 'H\h i\m')
     * @return string|Carbon
     */
    public function getContributionTimeAttribute($format = 'H\h i\m')
    {
        $hours = $this->contribution_hours;
        $minutes = round(($hours - floor($hours)) * 60);
        
        $time = Carbon::createFromTime(floor($hours), $minutes);
        
        return $format ? $time->format($format) : $time;
    }
}
