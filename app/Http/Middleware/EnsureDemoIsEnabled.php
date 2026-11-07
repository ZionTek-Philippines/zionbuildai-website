<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Hides the "Try it!" live demo (every /demo route) when DEMO_DISABLED is true.
 */
class EnsureDemoIsEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless(config('app.demo_enabled'), 404);

        return $next($request);
    }
}
