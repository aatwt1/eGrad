<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Initiative;
use App\Models\Vote;
use App\Models\LocalCommunity;
use Illuminate\Http\Request;

class InitiativeController extends Controller
{
    /**
     * GET /api/initiatives
     * Filter: status, local_community_id
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Bazni query sa relacijama
        $query = Initiative::with(['user', 'localCommunity', 'votes']);
        
        
        $query->where(function($q) use ($user) {
            $q->where('status', 'approved') // Odobrene inicijative
              ->orWhere('user_id', $user->id); // Svoje inicijative
        });
        
        // ===== FILTRIRANJE =====
        $this->applyFilters($query, $request);
        
        $initiatives = $query->paginate(10)->withQueryString();

        $localCommunities = LocalCommunity::orderBy('name')->get();
        
        return view('initiatives.index', compact(
            'initiatives', 
            'localCommunities'
        ));
    }

    /**
     * Admin view - sve inicijative sa svim statusima.
     */
    public function adminIndex(Request $request)
    {
        // Ova metoda je zaštićena admin middleware-om
        
        $query = Initiative::with(['user', 'localCommunity', 'votes']);
        
        // ===== FILTRIRANJE =====
        $this->applyFilters($query, $request);
        
        $initiatives = $query->paginate(10)->withQueryString();
        
        $categories = $this->getCategories();
        $localCommunities = LocalCommunity::orderBy('name')->get();
        
        return view('initiatives.admin-index', compact(
            'initiatives', 
            'categories', 
            'localCommunities'
        ));
    }

    /**
     * Apply filters to query.
     */
    private function applyFilters($query, Request $request)
    {
        // Filtriranje po statusu
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        // Filtriranje po kategoriji
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category', $request->category);
        }
        
        // Filtriranje po mjesnoj zajednici
        if ($request->has('community') && !empty($request->community)) {
            $query->where('local_community_id', $request->community);
        }
        
        // Pretraga
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('goal', 'like', "%{$search}%");
            });
        }
        
        // Sortiranje
        $sort = $request->get('sort', 'latest');
        
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'votes':
                $query->withCount('votes')->orderBy('votes_count', 'desc');
                break;
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            default:
                $query->latest();
                break;
        }
    }

   
    
    /**
     * Show the form for creating a new initiative.
     */
    public function create()
    {
        // Dohvati sve mjesne zajednice za dropdown
        $localCommunities = LocalCommunity::orderBy('name')->get();

        // Dohvati kategorije (može biti iz config fajla ili modela)
        $categories = [
            'infrastructure' => 'Infrastruktura',
            'environment' => 'Životna sredina',
            'sports' => 'Sport i rekreacija',
            'culture' => 'Kultura i umjetnost',
            'education' => 'Obrazovanje',
            'social' => 'Socijalna pitanja',
            'other' => 'Ostalo',
        ];

        return view('initiatives.create', compact('localCommunities', 'categories'));
    }

    /**
     * Store a newly created initiative in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:30',
            'goal' => 'nullable|string',
            'local_community_id' => 'required|exists:local_communities,id',
            'category' => 'required|string',
            'estimated_budget' => 'nullable|numeric|min:0',
        ]);

        // Kreiraj inicijativu
        $initiative = Initiative::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'goal' => $validated['goal'],
            'local_community_id' => $validated['local_community_id'],
            'category' => $validated['category'],
            'estimated_budget' => $validated['estimated_budget'],
            'status' => 'pending', // Inicijalni status
        ]);

        return redirect()
            ->route('initiatives.index')
            ->with('success', 'Vaša inicijativa je uspješno kreirana! Čeka odobrenje administratora.');
    }

    /**
     * GET /api/initiatives/{id}
     */
    public function show(Initiative $initiative)
    {
        return response()->json(
            $initiative->load(['comments.user', 'votes'])
        );
    }

    /**
     * POST /api/initiatives/{initiative}/vote
     */
    public function vote(Request $request, Initiative $initiative)
    {
        $user = $request->user();

        $alreadyVoted = Vote::where('initiative_id', $initiative->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyVoted) {
            return response()->json([
                'message' => 'Već ste glasali za ovu inicijativu.'
            ], 409);
        }

        Vote::create([
            'initiative_id' => $initiative->id,
            'user_id' => $user->id,
        ]);

        return response()->json([
            'message' => 'Glas je uspješno zabilježen.'
        ]);
    }

    /**
     * PATCH /api/initiatives/{initiative}/approve
     * ADMIN
     */
    public function approve(Initiative $initiative)
    {
        $initiative->update([
            'status' => 'approved'
        ]);

        return response()->json([
            'message' => 'Inicijativa je odobrena.'
        ]);
    }
}

