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
 *
 * DEMO_UNLOCK_FORM_ENABLED=false turns the form off and opens these modules to everyone.
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
        View::share('isDemoLocked', self::isLocked());

        return $next($request);
    }

    /**
     * Whether the gated modules still need the unlock form for the current visitor.
     */
    public static function isLocked(): bool
    {
        return config('app.demo_unlock_form_enabled') && ! session(self::SESSION_KEY);
    }
}
