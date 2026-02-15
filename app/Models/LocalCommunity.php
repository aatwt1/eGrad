<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalCommunity extends Model
{
    use HasFactory;

    protected $table = 'local_communities';

    protected $fillable = [
        'name',
        'postal_code',
    ];

    public function reportedIssues()
    {
        return $this->hasMany(ReportedIssue::class);
    }

    public function initiatives()
    {
        return $this->hasMany(Initiative::class);
    }
}
