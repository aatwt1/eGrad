<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'total_amount',
        'notes',
    ];

    public function categories()
    {
        return $this->hasMany(BudgetCategory::class);
    }
}
