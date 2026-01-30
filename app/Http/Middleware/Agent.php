<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Agent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur est authentifié
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Vérifier si l'utilisateur a le rôle "agence"
        if (!auth()->user()->hasRole('agent')) {
             
            abort(403, 'Accès non autorisé. Seules les agents peuvent accéder à cette page.');
        }

        return $next($request);
    }
}