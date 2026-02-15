<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportedIssue extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'location',
        'status',
        'attachments',
        'user_id',
        'local_community_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function localCommunity()
    {
        return $this->belongsTo(LocalCommunity::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }


}
