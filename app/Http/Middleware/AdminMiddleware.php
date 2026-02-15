<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Provjera da li je korisnik ulogovan i da li je admin (role = 1)
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->role != 1) {
            abort(403, 'Pristup dozvoljen samo administratorima.');
        }

        return $next($request);
    }
}