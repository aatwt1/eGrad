<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::where('user_id', Auth::id())
            ->withCount('comments')
            ->withCount('attachments')
            ->latest();

        // Filter po statusu
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }

        $events = $query->paginate(10);
        $stats = $this->getStats();

        return view('events.index', compact('events', 'stats'));
    }

    private function getStats()
    {
        return [
            'total' => Event::where('user_id', Auth::id())->count(),
            'pending' => Event::where('user_id', Auth::id())->where('status', 'pending')->count(),
            'approved' => Event::where('user_id', Auth::id())->where('status', 'approved')->count(),
            'rejected' => Event::where('user_id', Auth::id())->where('status', 'rejected')->count(),
        ];
    }

    public function storeComment(Request $request, Event $event)
    {
        // Provjera autorizacije
        if ($event->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Nemate dozvolu za komentiranje ovog događaja.');
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $event->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return back()->with('success', 'Komentar dodan.');
    }

    public function destroyComment(Comment $comment)
    {
        if (Auth::id() === $comment->user_id || Auth::user()->isAdmin()) {
            $comment->delete();
            return back()->with('success', 'Komentar obrisan.');
        }

        return back()->with('error', 'Nemate dozvolu za brisanje komentara.');
    }
}