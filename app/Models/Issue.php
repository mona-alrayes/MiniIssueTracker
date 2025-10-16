<?php

namespace App\Models;

use App\Enums\StatusType;
use App\Enums\PriorityType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'due_window' => 'json',
        'status_change_at' => 'datetime',
        'status' => StatusType::class,
        'priority' => PriorityType::class,
    ];

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function project(){
        return $this->belongsTo(Project::class);
    }

    public function creator(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee(){
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function labels(){
        return $this->belongsToMany(Label::class, 'issue_label');
    }
}
