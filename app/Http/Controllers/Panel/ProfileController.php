<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccountProfileRequest;
use App\Services\AccountProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show the signed-in account's own profile form.
     */
    public function edit(Request $request): View
    {
        return view('panel.profile', ['user' => $request->user()]);
    }

    /**
     * Save the signed-in account's own name, email, and photo.
     */
    public function update(AccountProfileRequest $request, AccountProfileService $accounts): RedirectResponse
    {
        $user = $request->user();

        $accounts->update($user, $accounts->profileFromRequest($request, $user));

        return redirect()->route('panel.profile.edit')->with('status', 'Profil Anda disimpan.');
    }
}
