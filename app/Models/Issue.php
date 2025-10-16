<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'due_date',
        'code',
        'due_window',
        'status_change_at',
    ];
}
