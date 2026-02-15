<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Initiative;
use App\Models\ReportedIssue;
use App\Models\Budget;
use App\Models\LocalCommunity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {

    // Provjera da li je korisnik admin
    if (auth()->user()->role != 1) {
        // Ako nije admin, preusmjeri na obični dashboard
        return view('dashboard');

    }

        // - STATISTIKA -
        $stats = [
            'total_users' => User::count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
            'total_initiatives' => Initiative::count(),
            'pending_initiatives' => Initiative::where('status', 'pending')->count(),
            'approved_initiatives' => Initiative::where('status', 'approved')->count(),
            'rejected_initiatives' => Initiative::where('status', 'rejected')->count(),
            'total_issues' => ReportedIssue::count(),
            'pending_issues' => ReportedIssue::where('status', 'pending')->count(),
            'resolved_issues' => ReportedIssue::where('status', 'resolved')->count(),
            'in_progress_issues' => ReportedIssue::where('status', 'in_progress')->count(),
            'total_budgets' => Budget::count(),
            'current_year_budget' => Budget::where('year', date('Y'))->first(),
        ];

        // - GRAFIČKI PODACI -
        // Inicijative po mjesecima (za zadnjih 6 mjeseci)
        $initiativesByMonth = Initiative::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Prijavljeni problemi po statusu
        $issuesByStatus = [
            'pending' => ReportedIssue::where('status', 'pending')->count(),
            'in_progress' => ReportedIssue::where('status', 'in_progress')->count(),
            'resolved' => ReportedIssue::where('status', 'resolved')->count(),
            'rejected' => ReportedIssue::where('status', 'rejected')->count(),
        ];

        // Inicijative po kategorijama
        $initiativesByCategory = Initiative::select('category', DB::raw('COUNT(*) as total'))
            ->groupBy('category')
            ->get();

        // - NAJNOVIJI PODACI -
        $latest_users = User::latest()->take(5)->get();
        $latest_initiatives = Initiative::with('user')->latest()->take(5)->get();
        $latest_issues = ReportedIssue::with('user')->latest()->take(5)->get();
        
        // - MJESNE ZAJEDNICE STATISTIKA -
        $communities = LocalCommunity::withCount(['initiatives', 'reportedIssues'])
            ->orderBy('name')
            ->get();

        // - AKTIVNOSTI -
        $recent_activities = $this->getRecentActivities();

        return view('admin.dashboard', compact(
            'stats',
            'initiativesByMonth',
            'issuesByStatus',
            'initiativesByCategory',
            'latest_users',
            'latest_initiatives',
            'latest_issues',
            'communities',
            'recent_activities'
        ));
    }

    private function getRecentActivities()
    {
        // Kombinacija nedavnih aktivnosti iz različitih modela
        $activities = [];
        
        // Nedavne inicijative
        $initiatives = Initiative::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function($item) {
                return [
                    'type' => 'initiative',
                    'title' => $item->title,
                    'user' => $item->user->name,
                    'status' => $item->status,
                    'created_at' => $item->created_at,
                ];
            });
        
        // Nedavni problemi
        $issues = ReportedIssue::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function($item) {
                return [
                    'type' => 'issue',
                    'title' => $item->title,
                    'user' => $item->user->name,
                    'status' => $item->status,
                    'created_at' => $item->created_at,
                ];
            });
        
        // Novi korisnici
        $users = User::latest()
            ->take(5)
            ->get()
            ->map(function($item) {
                return [
                    'type' => 'user',
                    'title' => $item->name,
                    'email' => $item->email,
                    'created_at' => $item->created_at,
                ];
            });
        
        // Spoji i sortiraj po datumu
        $activities = $initiatives->concat($issues)->concat($users)
            ->sortByDesc('created_at')
            ->take(10)
            ->values()
            ->toArray();
        
        return $activities;
    }
}