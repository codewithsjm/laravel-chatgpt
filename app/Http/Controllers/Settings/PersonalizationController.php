<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PersonalizationController extends Controller
{
    public function edit(): Response
    {
        return Inertia::render('settings/personalization');
    }

    public function update(Request $request): RedirectResponse
    {
        $request->user()->update($request->validate([
            'instructions' => ['nullable', 'string', 'max:1500'],
        ]));

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Saved. Swali will remember.']);

        return to_route('personalization.edit');
    }
}
