<?php

namespace App\Models;

use App\Casts\StatusCast;
use App\Casts\PriorityCast;
use App\Casts\DueWindowCast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;


class Issue extends Model
{
    /** @use HasFactory<\Database\Factories\IssueFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'assigned_to',
        'created_by',
        'project_id',
        'code',
        'due_window',
        'status_change_at',
    ];

    protected $casts = [
        'status_change_at' => 'datetime',
        'status' => StatusCast::class,
        'priority' => PriorityCast::class,
        'due_window' => DueWindowCast::class,
    ];

    /**
     * Get all comments for the issue
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Comment>
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the project the issue belongs to
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Project>
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user who created the issue
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User>
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user assigned to the issue
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User>
     */
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get all labels associated with the issue
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Label>
     */
    public function labels()
    {
        return $this->belongsToMany(Label::class, 'issue_label');
    }

    /**
     * Mutator to ensure issue code is always uppercase
     * 
     * @param string $value
     * @return void
     */
    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = strtoupper($value);
    }

    /**
     * Accessor to show title trimmed/normalized
     * 
     * @param string $value
     * @return string
     */
    public function getTitleAttribute($value)
    {
        return ucfirst($value);
    }

    /**
     * Scope a query to only include open issues
     * 
     * @param \Illuminate\Database\Eloquent\Builder $q
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOpen(Builder $q)
    {
        return $q->whereIn('status', ['open', 'in_progress', 'blocked']);
    }

    /**
     * Scope a query to only include urgent issues
     * 
     * @param \Illuminate\Database\Eloquent\Builder $q
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUrgent(Builder $q)
    {
        $now = now();
        $in48Hours = $now->copy()->addHours(48);
        return $q->whereIn('priority', ['high', 'highest'])
            ->whereJsonContains('due_window->due_at', function ($query) use ($now, $in48Hours) {
                $query->whereBetween($now, $in48Hours);
            })->open();
    }

    /**
     * Scope a query to include the count of comments
     * 
     * @param \Illuminate\Database\Eloquent\Builder $q
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCommentsCount(Builder $q)
    {
        return $q->withCount('comments');
    }
}
