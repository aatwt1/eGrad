<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_id',
        'category_name',
        'allocated_amount',
    ];

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }
}
