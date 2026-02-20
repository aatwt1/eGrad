<?php

namespace App\Http\Controllers;

use App\Models\ReportedIssue;
use App\Models\LocalCommunity;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;

class ReportIssueController extends Controller
{
    public function index()
    {
        if (auth()->user()->isAdmin()) {
            // Admin vidi SVE prijave problema
            $reportedIssues = ReportedIssue::with([
                'comments.user',
                'localCommunity',
                'attachments'
            ])
            ->latest()
            ->get();
        } else {
            // Običan korisnik vidi samo svoje prijave
            $reportedIssues = ReportedIssue::with([
                'comments.user',
                'localCommunity',
                'attachments'
            ])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
        }

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

        // Kreiraj 
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

    /**
     * Prikaži formu za uređivanje.
     */
    public function edit(ReportedIssue $reportedIssue)
    {
        // Autorizacija - samo admin ili vlasnik može uređivati
        $this->authorize('update', $reportedIssue);
        
        // Dohvati sve mjesne zajednice za dropdown
        $communities = LocalCommunity::orderBy('name')->get();
        
        return view('reported-issues.edit', compact('reportedIssue', 'communities'));
    }

    /**
     * Ažuriraj problem u bazi.
     */
    public function update(Request $request, ReportedIssue $reportedIssue)
    {
        // Autorizacija
        $this->authorize('update', $reportedIssue);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:pending,in_progress,resolved,closed,rejected',
            'location' => 'nullable|string|max:255',
            'local_community_id' => 'nullable|exists:local_communities,id',
            'attachments.*' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx',
            'remove_attachments' => 'nullable|array',
            'remove_attachments.*' => 'integer'
        ]);

        // Obrada priloga
        $currentAttachments = $reportedIssue->attachments()->get();
        
        // Ukloni označene priloge
        if ($request->has('remove_attachments')) {
            foreach ($request->remove_attachments as $attachmentId) {
                $attachment = $currentAttachments->find($attachmentId);
                if ($attachment) {
                    // Obriši fizički fajl
                    Storage::disk('public')->delete($attachment->path);
                    // Obriši iz baze
                    $attachment->delete();
                }
            }
        }
        
        // Dodaj nove priloge
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments/issues/' . $reportedIssue->id, 'public');
                
                $reportedIssue->attachments()->create([
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }
        
        // Ažuriraj osnovne podatke
        $reportedIssue->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'location' => $validated['location'],
            'local_community_id' => $validated['local_community_id'],
        ]);

        return redirect()
            ->route('reported-issues.show', $reportedIssue)
            ->with('success', 'Problem je uspješno ažuriran.');
    }

    /**
     * Prikaži stranicu za potvrdu brisanja.
     */
    public function delete(ReportedIssue $reportedIssue)
    {
        // Autorizacija
        $this->authorize('delete', $reportedIssue);
        
        // Učitaj komentare za prikaz
        $reportedIssue->load(['comments.user', 'attachments']);
        
        return view('reported-issues.delete', compact('reportedIssue'));
    }

    /**
     * Obriši iz baze.
     */
    public function destroy(ReportedIssue $reportedIssue)
    {
        // Autorizacija
        $this->authorize('delete', $reportedIssue);
        
        // Obriši sve priloge (fizički fajlovi)
        foreach ($reportedIssue->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->path);
        }
        
        // Obriši sve komentare (automatski preko cascade? ako ne, ručno)
        $reportedIssue->comments()->delete();
        
        // Obriši priloge iz baze
        $reportedIssue->attachments()->delete();
        
        // Obriši sam problem
        $reportedIssue->delete();

        return redirect()
            ->route('reported-issues.index')
            ->with('success', 'Problem je uspješno obrisan.');
    }

    use AuthorizesRequests;
}