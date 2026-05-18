<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
public function handle(Request $request, Closure $next)
{
    if (!auth()->check() || !auth()->user()->admin) {
        return redirect('/tickets.index')->with('error', 'Você não tem permissão.');
    }

    return $next($request);
}
}
