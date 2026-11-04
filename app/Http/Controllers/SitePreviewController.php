<?php

namespace App\Http\Controllers;

use App\Http\Middleware\RequireSitePreviewPassword;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SitePreviewController extends Controller
{
    /**
     * Unlock the private homepage for this visitor's session when the password matches.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(['password' => ['required', 'string', 'max:100']]);

        $hash = (string) config('app.preview_password_hash');

        if ($hash === '' || ! Hash::check($request->string('password')->value(), $hash)) {
            return back()->withErrors(['password' => 'That password is not correct.']);
        }

        $request->session()->regenerate();
        $request->session()->put(RequireSitePreviewPassword::SESSION_KEY, true);

        return redirect()->route('home');
    }
}
