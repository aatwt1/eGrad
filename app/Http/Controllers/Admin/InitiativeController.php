<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Initiative;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InitiativeController extends Controller
{
    /**
     * Prikaz svih inicijativa za admina
     */
    public function index(Request $request)
    {
        // Base query sa potrebnim podacima
        $query = Initiative::with('user')
            ->withCount([
                'comments',
                'votes as votes_count'
            ]);

        // Filtriranje po statusu
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Pretraga po naslovu, opisu ili kategoriji
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Sortiranje
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        
        $allowedSortFields = ['title', 'status', 'created_at', 'comments_count', 'votes_count'];
        
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
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
    $initiative->load([
        'user', 
        'localCommunity',
        'comments.user', 
        'comments' => function($q) {
            $q->latest();
        }
    ]);
    
    $initiative->loadCount('votes as votes_count');
    
    $statusInfo = $this->getStatusInfo($initiative->status);
    
    return view('admin.initiatives.show', compact('initiative', 'statusInfo'));
    }

    public function approve(Initiative $initiative)
    {
        $initiative->update([
            'status' => 'approved',
            'admin_notes' => 'Odobreno od strane administratora.',
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
        ]);

        $this->addSystemComment($initiative, 'Inicijativa je odobrena od strane administratora.');

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

        
        $this->addSystemComment($initiative, 'Inicijativa je odbijena. Razlog: ' . $request->rejection_reason);

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

        $this->addSystemComment($initiative, 'Inicijativa je označena kao implementirana.');

        return redirect()->route('admin.initiatives.index')
            ->with('success', "Inicijativa '{$initiative->title}' je označena kao implementirana.");
    }

  
    private function addSystemComment(Initiative $initiative, string $content)
    {
        $initiative->comments()->create([
            'user_id' => Auth::id(),
            'content' => '[Sistem] ' . $content,
        ]);
    }

   
    public function deleteComment(Comment $comment)
    {
        if (Auth::user()->isAdmin()) {
            $comment->delete();
            return back()->with('success', 'Komentar je obrisan.');
        }

        return back()->with('error', 'Nemate dozvolu za brisanje komentara.');
    }

    private function getStatusInfo($status)
    {
    $statuses = [
        'pending' => [
            'text' => 'Na čekanju',
            'color' => 'bg-yellow-100 text-yellow-800'
        ],
        'approved' => [
            'text' => 'Odobreno',
            'color' => 'bg-green-100 text-green-800'
        ],
        'rejected' => [
            'text' => 'Odbijeno',
            'color' => 'bg-red-100 text-red-800'
        ],
        'implemented' => [
            'text' => 'Implementirano',
            'color' => 'bg-purple-100 text-purple-800'
        ]
    ];

    return $statuses[$status] ?? [
        'text' => $status,
        'color' => 'bg-gray-100 text-gray-800'
    ];
    }
}