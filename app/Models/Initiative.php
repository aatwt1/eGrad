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
        'reviewed_by',
        'rejection_reason',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
        ];
    }

    /**
     * Relacija sa korisnikom (autor inicijative)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacija sa komentarima (polimorfna)
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    // Relacija sa glasovima
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    
    // Pomoćna metoda za broj glasova
     
    public function getVotesCountAttribute()
    {
        return $this->votes()->count();
    }

        public function localCommunity()
    {
        return $this->belongsTo(LocalCommunity::class);
    }


    
    // Pomoćna metoda za provjeru da li je korisnik glasao
    
    public function isVotedByUser($userId)
    {
        return $this->votes()->where('user_id', $userId)->exists();
    }

   
    // Scope za filtriranje po statusu
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope za pretragu
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('category', 'like', "%{$search}%");
        });
    }
}
