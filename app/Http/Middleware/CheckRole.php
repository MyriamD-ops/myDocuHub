<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Usage dans les routes :
     *   ->middleware('role:formateur')
     *   ->middleware('role:stagiaire')
     *   ->middleware('role:formateur,stagiaire')  // plusieurs rôles acceptés
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user() || ! in_array($request->user()->role, $roles)) {
            abort(403, 'Accès réservé aux : ' . implode(', ', $roles) . '.');
        }

        return $next($request);
    }
}
