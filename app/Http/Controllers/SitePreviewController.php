<?php

namespace App\Http\Controllers;

use App\Http\Middleware\RequireSitePreviewPassword;
use App\Models\PreviewPassword;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SitePreviewController extends Controller
{
    /**
     * Unlock the private homepage for this visitor's session when they enter an active client password.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(['password' => ['required', 'string', 'max:100']]);

        $previewPassword = RequireSitePreviewPassword::isEnabled()
            ? PreviewPassword::findUsableMatching($request->string('password')->value())
            : null;

        if ($previewPassword === null) {
            return back()->withErrors(['password' => 'That password is not correct.']);
        }

        $previewPassword->recordUse();

        $request->session()->regenerate();
        $request->session()->put(RequireSitePreviewPassword::SESSION_KEY, $previewPassword->getKey());

        return redirect()->route('home');
    }
}
