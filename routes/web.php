<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\InitiativeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ReportIssueController;
use App\Http\Controllers\Admin\DashboardController;

/*---------------
-Početna strana
---------------*/

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    ])
    ->get('/', [DashboardController::class, 'index'])
    ->name('dashboard');
/*----------------
 -Initiatives
------------------*/
Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    // svi prijavljeni korisnici
    Route::get('/initiatives', [InitiativeController::class, 'index'])
        ->name('initiatives.index');

    Route::get('/initiatives/create', [InitiativeController::class, 'create'])
        ->name('initiatives.create');

    Route::get('/initiatives/{initiative}', [InitiativeController::class, 'show'])
        ->name('initiatives.show');


    Route::post('/initiatives', [InitiativeController::class, 'store'])
        ->name('initiatives.store');

    // samo admin (odobravanje, edit, delete)
    Route::get('/initiatives/{initiative}/edit', [InitiativeController::class, 'edit'])
        ->middleware('permission:approve_initiative')
        ->name('initiatives.edit');

    Route::put('/initiatives/{initiative}', [InitiativeController::class, 'update'])
        ->middleware('permission:approve_initiative')
        ->name('initiatives.update');

    Route::delete('/initiatives/{initiative}', [InitiativeController::class, 'destroy'])
        ->middleware('permission:approve_initiative')
        ->name('initiatives.destroy');

    // glasanje
    Route::post('/initiatives/{initiative}/vote', [InitiativeController::class, 'vote'])
        ->name('initiatives.vote');
});


/*
|--------------------------------------------------------------------------
| Budget (read-only za usera)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    // Budžet po godini
    Route::get('/budgets/{year?}', [BudgetController::class, 'index'])
        ->whereNumber('year')
        ->name('budgets.index');

    // Pregled jednog budžeta (ID)
    Route::get('/budget/show/{budget}', [BudgetController::class, 'show'])
        ->name('budgets.show');

    // samo admin
    Route::post('/budgets', [BudgetController::class, 'store'])
        ->middleware('permission:manage_budget')
        ->name('budgets.store');

    Route::put('/budgets/{budget}', [BudgetController::class, 'update'])
        ->middleware('permission:manage_budget')
        ->name('budgets.update');

    Route::delete('/budgets/{budget}', [BudgetController::class, 'destroy'])
        ->middleware('permission:manage_budget')
        ->name('budgets.destroy');
});


/*
|--------------------------------------------------------------------------
| Events
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'verified'])->resource('events', EventController::class);


/*
|--------------------------------------------------------------------------
| Reported Issues
|--------------------------------------------------------------------------
*/
//Route::middleware(['auth:sanctum', 'verified'])->resource('reported-issues', ReportIssueController::class);

Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    Route::get('/reported-issues', [ReportIssueController::class, 'index'])
        ->name('reported-issues.index');

    Route::get('/reported-issues/create', [ReportIssueController::class, 'create'])
        ->name('reported-issues.create');

    
    Route::post('/reported-issues', [ReportIssueController::class, 'store'])
        ->name('reported-issues.store');

    Route::post(
        '/reported-issues/{reportedIssue}/comments',
        [ReportIssueController::class, 'storeComment']
    )->name('reported-issues.comments.store');

    Route::get('/reported-issues/{reportedIssue}', [ReportIssueController::class, 'show'])
    ->name('reported-issues.show');

});
