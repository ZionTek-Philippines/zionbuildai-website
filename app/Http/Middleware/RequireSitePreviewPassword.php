<?php

namespace App\Http\Middleware;

use App\Models\PreviewPassword;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Keeps the homepage private behind per-client passwords until the marketing launch.
 *
 * Passwords are managed in the admin panel (Homepage Passwords). A visitor who enters one
 * (see SitePreviewController) stays in for their session, but only while that password is
 * still active and not expired: switching it off locks them out on their next page load.
 * The whole gate is on when SITE_PREVIEW_ENABLED is true.
 */
class RequireSitePreviewPassword
{
    public const SESSION_KEY = 'site_preview_password_id';

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! self::isEnabled() || self::hasUsablePassword($request)) {
            return $next($request);
        }

        $request->session()->forget(self::SESSION_KEY);

        return response()
            ->view('preview-gate')
            ->header('X-Robots-Tag', 'noindex, nofollow')
            ->header('Cache-Control', 'no-store');
    }

    /**
     * Whether the homepage currently asks for a password.
     */
    public static function isEnabled(): bool
    {
        return (bool) config('app.preview.enabled');
    }

    private static function hasUsablePassword(Request $request): bool
    {
        $previewPasswordId = $request->session()->get(self::SESSION_KEY);

        return $previewPasswordId !== null
            && (bool) PreviewPassword::find($previewPasswordId)?->isUsable();
    }
}
