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

    /**
     * Get all issues for the project
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Issue>
     */
    public function issues()
    {
        return $this->hasMany(Issue::class);
    }

    /**
     * Get only opened issues for the project (relationship)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Issue>
     */
    public function openedIssues()
    {
        return $this->hasMany(Issue::class)->whereIn('status', ['open', 'in_progress']);
    }

    /**
     * Get all users associated with the project
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<User>
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'project_user')
            ->withPivot('role', 'contribution_hours', 'last_activity')
            ->withCasts(['last_activity' => 'datetime']);
    }

    // Example: scope that returns projects with most open issues
    /**
     * Scope a query to order projects by most open issues
     * 
     * @param \Illuminate\Database\Eloquent\Builder $q
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMostOpen(Builder $q)
    {
        return $q->withCount(['issues as open_issues_count' => function ($q) {
            $q->whereIn('status', ['open', 'in_progress']);
        }])->orderByDesc('open_issues_count');
    }

    // mutator to ensure project code is always uppercase
    /**
     * Mutator to ensure project code is always uppercase
     * 
     * @param string $value
     * @return void
     */
    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = strtoupper($value);
    }

    // Local scope to get projects that have opened issues
    /**
     * Scope a query to only include projects that have opened issues
     * 
     * @param \Illuminate\Database\Eloquent\Builder $q
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithOpenedIssues(Builder $q)
    {
        return $q->whereRelation('issues', 'status' , 'open');
    }
}
