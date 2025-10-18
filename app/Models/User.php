<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable , HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get all comments created by the user
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Comment>
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get all issues created by the user
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Issue>
     */
    public function created_issues()
    {
        return $this->hasMany(Issue::class, 'created_by');
    }

    /**
     * Get all issues assigned to the user
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Issue>
     */
    public function assigned_issues()
    {
        return $this->hasMany(Issue::class, 'assigned_to');
    }

    /**
     * Get all projects the user belongs to
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Project>
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_user')->withPivot('role', 'contribution_hours', 'last_activity');
    }

    /**
     * Scope a query to include the count of completed issues
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithCompletedIssuesCount($query)
    {
        return $query->withCount([
            'assigned_issues as completed_issues_count' => fn($q) =>
                $q->where('status', 'completed'),
        ]);
    }

    /**
     * Get last activity timestamp for a project
     */
    public function currentProjectActivity(Project $project): ?Carbon
    {
        return $this->projects()->where('project_id', $project->id)
            ->first()?->pivot?->last_activity;
    }
}
