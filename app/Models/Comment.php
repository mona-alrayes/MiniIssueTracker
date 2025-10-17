<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory;

    protected $fillable =[
       'body',
       'user_id',
       'issue_id' 
    ];

    /**
     * Get the issue that the comment belongs to
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Issue>
     */
    public function issue(){
        return $this->belongsTo(Issue::class);
    }

    /**
     * Get the user who created the comment
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User>
     */
    public function user(){
        return $this->belongsTo(User::class);
    }
}
