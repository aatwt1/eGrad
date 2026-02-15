<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Initiative extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'goal',
        'status',
        'user_id',
        'local_community_id',
        'category',
        'estimated_budget',
        'rejection_reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function localCommunity()
    {
        return $this->belongsTo(LocalCommunity::class);
    }
}