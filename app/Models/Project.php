<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'code',
    ];

    public function issues(){
        return $this->hasMany(Issue::class);
    }

    public function users(){
        return $this->belongsToMany(User::class, 'project_user')->withPivot('role', 'contribution_hours', 'last_activity');
    }

      // Example: scope that returns projects with most open issues
    public function scopeMostOpen(Builder $q)
    {
        return $q->withCount(['issues as open_issues_count' => function ($q) {
            $q->whereIn('status', ['open','in_progress','blocked']);
        }])->orderByDesc('open_issues_count');
    }

    // mutator to ensure project code is always uppercase
    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = strtoupper($value);
    }
    
}
