<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class IssueLabel extends Pivot
{
    protected $fillable = [
        'issue_id',
        'label_id',
    ];

}
