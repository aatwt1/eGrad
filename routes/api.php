<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BudgetController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resource('budget', BudgetController::class);

// API rute za budžet 
//Route::middleware(['auth:sanctum', AdminMiddleware::class])->group(function () {
  //  Route::apiResource('budgets', BudgetController::class);
//});

// routes/api.php - PRIVREMENO
//Route::prefix('api')->group(function () {
    // Route::middleware(['auth:sanctum', AdminMiddleware::class])->group(function () {
      //  Route::apiResource('budgets', BudgetController::class);
    // });
//});

Route::get('/budgets-test', [BudgetController::class, 'index']);
Route::post('/budgets-test', [BudgetController::class, 'index']);
Route::get('/budgets-test1', [BudgetController::class, 'create']);

