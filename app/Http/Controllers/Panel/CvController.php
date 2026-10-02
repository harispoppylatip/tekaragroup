<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\MemberProfileRequest;
use App\Services\MemberAccountService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CvController extends Controller
{
    /**
     * Show the signed-in member's own CV form.
     */
    public function edit(Request $request): View
    {
        $member = $request->user()->member;

        abort_if($member === null, 404, 'Akun ini belum terhubung dengan profil anggota.');

        return view('panel.cv', ['member' => $member]);
    }

    /**
     * Save the signed-in member's own CV.
     */
    public function update(MemberProfileRequest $request, MemberAccountService $accounts): RedirectResponse
    {
        $member = $request->user()->member;

        abort_if($member === null, 404);
        Gate::authorize('update', $member);

        $accounts->update($member, $accounts->profileFromRequest($request, $member));

        return redirect()->route('panel.cv.edit')->with('status', 'CV berhasil disimpan.');
    }
}
