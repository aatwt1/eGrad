<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Initiative;
use Illuminae\Http\Request;
use Illuminate\Support\Facades\Auth;

class InitiativeController extends Controller
{
    public function __construct()
    {
        // Middleware za admin provjeru
        $this->middleware(function ($request, $next) {
            if (!Auth::user() || !Auth::user()->isAdmin()) {
                abort(403, 'Samo administratori mogu pristupiti ovoj stranici.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        // Base query sa potrebnim podacima
        $query = Initiative::with('user')
            ->withCount(['comments', 'supporters']);

        // Filtriranje po statusu
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Pretraga po naslovu, opisu ili kategoriji
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Sortiranje
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        
        if (in_array($sortField, ['title', 'status', 'created_at', 'comments_count', 'supporters_count'])) {
            if ($sortField === 'comments_count' || $sortField === 'supporters_count') {
                $query->orderBy($sortField, $sortDirection);
            } else {
                $query->orderBy($sortField, $sortDirection);
            }
        } else {
            $query->latest();
        }

        $initiatives = $query->paginate(15)->withQueryString();

        // Statistike za admina
        $stats = [
            'total' => Initiative::count(),
            'pending' => Initiative::where('status', 'pending')->count(),
            'approved' => Initiative::where('status', 'approved')->count(),
            'rejected' => Initiative::where('status', 'rejected')->count(),
            'implemented' => Initiative::where('status', 'implemented')->count(),
        ];

        // Statusi za dropdown filter
        $statuses = [
            'pending' => 'Na čekanju',
            'approved' => 'Odobreno',
            'rejected' => 'Odbijeno',
            'implemented' => 'Implementirano',
        ];

        return view('admin.initiatives.index', compact('initiatives', 'stats', 'statuses'));
    }

    public function show(Initiative $initiative)
    {
        $initiative->load(['user', 'comments.user', 'supporters']);
        
        return view('admin.initiatives.show', compact('initiative'));
    }

    public function approve(Initiative $initiative)
    {
        $initiative->update([
            'status' => 'approved',
            'admin_notes' => 'Odobreno od strane administratora.',
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
        ]);

        return redirect()->route('admin.initiatives.index')
            ->with('success', "Inicijativa '{$initiative->title}' je odobrena.");
    }

    public function reject(Request $request, Initiative $initiative)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:5|max:2000',
        ]);

        $initiative->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'admin_notes' => $request->rejection_reason,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
        ]);

        return redirect()->route('admin.initiatives.index')
            ->with('success', "Inicijativa '{$initiative->title}' je odbijena.");
    }

    public function markImplemented(Initiative $initiative)
    {
        $initiative->update([
            'status' => 'implemented',
            'admin_notes' => 'Inicijativa označena kao implementirana.',
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
        ]);

        return redirect()->route('admin.initiatives.index')
            ->with('success', "Inicijativa '{$initiative->title}' je označena kao implementirana.");
    }
}