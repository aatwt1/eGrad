<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Budget;
use App\Models\BudgetCategory;
use App\Models\Initiative;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
  public function index($year = null)
{
    $year = $year ?? now()->year;

    $budget = Budget::with('categories')
        ->where('year', $year)
        ->first();

    
    $chartColors = [
        '#3B82F6', '#10B981', '#F59E0B', '#EF4444',
        '#8B5CF6', '#EC4899', '#14B8A6', '#F97316',
        '#6366F1', '#8B5CF6', '#EC4899', '#F97316',
        '#3B82F6', '#10B981', '#F59E0B', '#EF4444'
    ];

    return view('budgets.index', [
        'year' => $year,
        'budget' => $budget,
        'previousYear' => $year - 1,
        'nextYear' => $year + 1,
        'chartColors' => $chartColors, 
    ]);
}

    public function show(Budget $budget)
    {
        return view('budgets.show', compact('budget'));
    }



    /*
    public function index()
    {
        $currentYearBudget = Budget::orderBy('year', 'desc')->first();
        $categories = BudgetCategory::where('budget_id', $currentYearBudget->id)->get();

        $chartData = $categories->map(function ($category) {
            return [
                'label' => $category->name,
                'amount' => $category->amount,
            ];
        });

        // Fetch initiatives for the current year
        $approvedInitiatives = Initiative::whereYear('created_at', now()->year)
            ->where('status', 'approved')
            ->get();

        $pendingInitiatives = Initiative::whereYear('created_at', now()->year)
            ->whereIn('status', ['pending', 'rejected'])
            ->get();

        return view('budgets.index', [
            'currentYearBudget' => $currentYearBudget,
            'chartData' => $chartData,
            'approvedInitiatives' => $approvedInitiatives,
            'pendingInitiatives' => $pendingInitiatives
        ]);
    }
    */

    public function vote(Initiative $initiative)
    {
        $initiative->votes()->updateOrCreate(
            ['user_id' => Auth::id()],
            ['vote' => 1] // +1
        );

        return back()->with('success', 'You voted for this initiative.');
    }
}
