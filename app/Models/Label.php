<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    /** @use HasFactory<\Database\Factories\LabelFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
    ];

    /**
     * Get all issues associated with the label
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Issue>
     */
    public function issues(){
        return $this->belongsToMany(Issue::class , 'issue_label');
    }
}
