<?php

namespace App\Http\Controllers;

use App\Http\Middleware\EnsureDemoIsUnlocked;
use App\Http\Requests\StoreDemoLeadRequest;
use App\Models\DemoLead;
use Illuminate\Http\RedirectResponse;

class DemoLeadController extends Controller
{
    /**
     * Save the lead and unlock the gated demo modules for this visitor's session.
     */
    public function store(StoreDemoLeadRequest $request): RedirectResponse
    {
        DemoLead::create($request->safe()->except('website'));

        $request->session()->put(EnsureDemoIsUnlocked::SESSION_KEY, true);

        return redirect()->back();
    }
}
