<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgencyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté.');
        }

        if (Auth::user()->role !== 'AGENCE') {
            abort(403, 'Accès réservé aux agences.');
        }

        return $next($request);
    }
}
