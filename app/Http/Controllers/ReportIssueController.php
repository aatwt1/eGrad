<?php

namespace App\Http\Controllers;

use App\Models\ReportedIssue;
use App\Models\LocalCommunity;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReportIssueController extends Controller
{
    public function index()
    {
        $reportedIssues = ReportedIssue::with([
                'comments.user',
                'localCommunity',
                'attachments'
            ])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('reported-issues.index', compact('reportedIssues'));
    }

    public function create()
    {
        // Dohvati sve mjesne zajednice za dropdown
        $localCommunities = LocalCommunity::orderBy('name')->get();
        
        return view('reported-issues.create', compact('localCommunities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'location' => 'required|string|max:255',
            'local_community_id' => 'required|exists:local_communities,id',
            'attachments.*' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf,doc,docx',
        ]);

        // Kreiraj problem
        $issue = ReportedIssue::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'location' => $validated['location'],
            'status' => 'pending',
            'user_id' => auth()->id(),
            'local_community_id' => $validated['local_community_id'],
        ]);

        // Upload priloga ako postoje
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments/issues', 'public');
                
                $issue->attachments()->create([
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        return redirect()
            ->route('reported-issues.index')
            ->with('success', 'Problem je uspješno prijavljen! Pratite status u listi prijava.');
    }

    public function storeComment(Request $request, ReportedIssue $reportedIssue)
    {
        $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $reportedIssue->comments()->create([
            'content' => $request->content,
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'Komentar je dodan.');
    }

    public function show(ReportedIssue $reportedIssue)
    {
    // Autorizacija koristeći Policy
    $this->authorize('view', $reportedIssue);
    
    // Učitaj sve relacije
    $reportedIssue->load(['localCommunity', 'comments.user', 'attachments']);
    
    return view('reported-issues.show', compact('reportedIssue'));
    }

    use AuthorizesRequests;
}