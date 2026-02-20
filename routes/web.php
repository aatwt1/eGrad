<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\InitiativeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ReportIssueController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Middleware\AdminMiddleware;

/*---------------
-Početna strana
---------------*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

/*----------------
 - Admin rute
------------------*/
Route::middleware(['auth', 'is_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        
        // Admin Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Admin - Upravljanje inicijativama
        Route::get('/initiatives', [App\Http\Controllers\Admin\InitiativeController::class, 'index'])
            ->name('initiatives.index');
        Route::get('/initiatives/{initiative}', [App\Http\Controllers\Admin\InitiativeController::class, 'show'])
            ->name('initiatives.show');
        Route::put('/initiatives/{initiative}/approve', [App\Http\Controllers\Admin\InitiativeController::class, 'approve'])
            ->name('initiatives.approve');
        Route::put('/initiatives/{initiative}/reject', [App\Http\Controllers\Admin\InitiativeController::class, 'reject'])
            ->name('initiatives.reject');
        Route::put('/initiatives/{initiative}/implemented', [App\Http\Controllers\Admin\InitiativeController::class, 'markImplemented'])
            ->name('initiatives.implemented');
    });

/*----------------
 - Javne korisničke rute (za sve prijavljene)
------------------*/
Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    // Initiatives - pregled i kreiranje
    Route::get('/initiatives', [InitiativeController::class, 'index'])
        ->name('initiatives.index');
    Route::get('/initiatives/create', [InitiativeController::class, 'create'])
        ->name('initiatives.create');
    Route::post('/initiatives', [InitiativeController::class, 'store'])
        ->name('initiatives.store');
    Route::get('/initiatives/{initiative}', [InitiativeController::class, 'show'])
        ->name('initiatives.show');

    // Glasanje za inicijative
    Route::post('/initiatives/{initiative}/vote', [InitiativeController::class, 'vote'])
        ->name('initiatives.vote');

    // Eventovi - dozvole za događaje
    Route::get('/events', [EventController::class, 'index'])
        ->name('events.index');
    Route::get('/events/create', [EventController::class, 'create'])
        ->name('events.create');
    Route::post('/events', [EventController::class, 'store'])
        ->name('events.store');
    Route::get('/events/{event}', [EventController::class, 'show'])
        ->name('events.show');
    Route::post('/events/{event}/comments', [EventController::class, 'storeComment'])
        ->name('events.comments.store');
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
| Reported Issues - POTPUNI RESOURCE RUTE
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    // Osnovne rute za pregled i kreiranje (već postoje)
    Route::get('/reported-issues', [ReportIssueController::class, 'index'])
        ->name('reported-issues.index');

    Route::get('/reported-issues/create', [ReportIssueController::class, 'create'])
        ->name('reported-issues.create');
    
    Route::post('/reported-issues', [ReportIssueController::class, 'store'])
        ->name('reported-issues.store');

    Route::get('/reported-issues/{reportedIssue}', [ReportIssueController::class, 'show'])
        ->name('reported-issues.show');

    Route::post('/reported-issues/{reportedIssue}/comments', [ReportIssueController::class, 'storeComment'])
        ->name('reported-issues.comments.store');

    // **NOVE RUTE - ZA UREĐIVANJE I BRISANJE**
    // Ove rute će koristiti policy za autorizaciju (admin može sve, građani samo svoje)
    
    // Edit - prikaz forme za uređivanje
    Route::get('/reported-issues/{reportedIssue}/edit', [ReportIssueController::class, 'edit'])
        ->name('reported-issues.edit');
    
    // Update - spremanje izmjena
    Route::put('/reported-issues/{reportedIssue}', [ReportIssueController::class, 'update'])
        ->name('reported-issues.update');
    
    // Delete - prikaz stranice za potvrdu brisanja
    Route::get('/reported-issues/{reportedIssue}/delete', [ReportIssueController::class, 'delete'])
        ->name('reported-issues.delete');
    
    // Destroy - brisanje iz baze
    Route::delete('/reported-issues/{reportedIssue}', [ReportIssueController::class, 'destroy'])
        ->name('reported-issues.destroy');
});