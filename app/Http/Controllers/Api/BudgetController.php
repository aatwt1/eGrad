<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use Illuminate\Http\Request;

// Kontroler samo za API testiranje 

class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $budgets = Budget::with('categories') // Učitaj i kategorije
                        ->orderBy('year', 'desc')
                        ->get();
        
        return response()->json([
            'success' => true,
            'data' => $budgets
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
     public function store(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:2000|max:2100|unique:budgets,year',
            'total_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        $budget = Budget::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Budžet uspješno kreiran',
            'data' => $budget->load('categories')
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $budget = Budget::with('categories')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $budget
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $budget = Budget::findOrFail($id);

        $validated = $request->validate([
            'year' => 'sometimes|integer|min:2000|max:2100|unique:budgets,year,' . $id,
            'total_amount' => 'sometimes|numeric|min:0',
            'notes' => 'sometimes|nullable|string'
        ]);

        $budget->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Budžet uspješno ažuriran',
            'data' => $budget->load('categories')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $budget = Budget::findOrFail($id);
        
        // Provjeri da li postoje kategorije
        if ($budget->categories()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Nije moguće obrisati budžet jer ima pridružene kategorije'
            ], Response::HTTP_CONFLICT);
        }

        $budget->delete();

        return response()->json([
            'success' => true,
            'message' => 'Budžet uspješno obrisan'
        ], Response::HTTP_NO_CONTENT);
    }
}
