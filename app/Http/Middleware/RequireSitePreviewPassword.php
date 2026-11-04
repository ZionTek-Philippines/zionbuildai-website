<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Keeps the homepage private behind a temporary password until the marketing launch.
 *
 * Visitors who enter the password (see SitePreviewController) stay unlocked for their session.
 * The gate is off when config('app.preview_password_hash') is empty.
 */
class RequireSitePreviewPassword
{
    public const SESSION_KEY = 'site_preview_unlocked';

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (blank(config('app.preview_password_hash')) || $request->session()->get(self::SESSION_KEY)) {
            return $next($request);
        }

        return response()
            ->view('preview-gate')
            ->header('X-Robots-Tag', 'noindex, nofollow')
            ->header('Cache-Control', 'no-store');
    }
}
