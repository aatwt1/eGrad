<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'user_id',
        'commentable_type',
        'commentable_id',
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    
    // polymorphic relationship to e-services
    
    public function commentable()
    {
        return $this->morphTo();
    }
}
