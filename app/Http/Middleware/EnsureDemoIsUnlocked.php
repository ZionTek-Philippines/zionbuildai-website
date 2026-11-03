<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Gates the deeper demo modules behind the "Unlock the full demo" form.
 *
 * Until the visitor submits the form, a gated page renders only its top (title, tabs and
 * KPI tiles) with the unlock form below; the rest is never rendered, so it is not in the
 * page source either. Pages read the shared $isDemoLocked flag to decide where to stop.
 */
class EnsureDemoIsUnlocked
{
    public const SESSION_KEY = 'demo_unlocked';

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        View::share('isDemoLocked', ! $request->session()->get(self::SESSION_KEY));

        return $next($request);
    }
}
