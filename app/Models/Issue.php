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

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function labels()
    {
        return $this->belongsToMany(Label::class, 'issue_label');
    }

    // mutator to ensure issue code is always uppercase
     public function setCodeAttribute($value)
    {
        $this->attributes['code'] = strtoupper($value);
    }

    // Accessor: show title trimmed/normalized
    public function getTitleAttribute($value)
    {
        return ucfirst($value);
    }

    // Local scopes per brief
    public function scopeOpen(Builder $q)
    {
        return $q->whereIn('status', ['open', 'in_progress', 'blocked']);
    }

    // return issues with high or highest priority due in next 48 hours and still open
    public function scopeUrgent(Builder $q)
    {
        $now = now();
        $in48Hours = $now->copy()->addHours(48);
        return $q->whereIn('priority', ['high', 'highest'])
            ->whereJsonContains('due_window->due_at', function ($query) use ($now, $in48Hours) {
                $query->whereBetween($now, $in48Hours);
            })->open();
    }

    // Return issues along with the count of their comments
    public function scopeCommentsCount(Builder $q)
    {
        return $q->withCount('comments');
    }
}
