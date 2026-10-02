<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class PasswordController extends Controller
{
    /**
     * Show the change-password form.
     */
    public function edit(Request $request): View
    {
        return view('panel.password', [
            'isFirstLogin' => $request->user()->must_change_password,
        ]);
    }

    /**
     * Replace the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8),
                Rule::notIn([config('tekara.default_password')]),
            ],
        ], [
            'password.not_in' => 'Sandi baru tidak boleh sama dengan sandi awal.',
        ], [
            'current_password' => 'sandi saat ini',
            'password' => 'sandi baru',
        ]);

        $request->user()->update([
            'password' => $validated['password'],
            'must_change_password' => false,
        ]);

        return redirect()->route('panel.dashboard')->with('status', 'Sandi berhasil diganti.');
    }
}
