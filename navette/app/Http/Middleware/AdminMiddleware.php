<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        if (Auth::user()->role !== 'ADMIN') {
            abort(403, 'Accès non autorisé. Seuls les administrateurs peuvent accéder à cette page.');
        }

        return $next($request);
    }
}







