<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\MemberProfileRequest;
use App\Models\Member;
use App\Services\MemberAccountService;
use App\UserRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function __construct(private MemberAccountService $accounts) {}

    /**
     * List every member with their account.
     */
    public function index(): View
    {
        Gate::authorize('viewAny', Member::class);

        return view('panel.members.index', [
            'members' => Member::query()->with('user')->withCount('projects')->orderBy('sort_order')->get(),
        ]);
    }

    /**
     * Show the form for a new member.
     */
    public function create(): View
    {
        Gate::authorize('create', Member::class);

        return view('panel.members.form', ['member' => new Member]);
    }

    /**
     * Create the member and their login account.
     */
    public function store(MemberProfileRequest $request): RedirectResponse
    {
        Gate::authorize('create', Member::class);

        $member = $this->accounts->create(
            $this->accounts->profileFromRequest($request),
            $request->string('login_email')->toString(),
            $request->accountRole() ?? UserRole::Member,
        );

        return redirect()->route('panel.members.index')->with(
            'status',
            "{$member->name} ditambahkan. Akun masuk: {$member->user->email} dengan sandi awal ".config('tekara.default_password').'.'
        );
    }

    /**
     * Show the form for editing a member.
     */
    public function edit(Member $member): View
    {
        Gate::authorize('update', $member);

        return view('panel.members.form', ['member' => $member->load('user')]);
    }

    /**
     * Update the member and keep their account in sync.
     */
    public function update(MemberProfileRequest $request, Member $member): RedirectResponse
    {
        Gate::authorize('update', $member);

        $role = $member->user_id === $request->user()->id ? null : $request->accountRole();

        $this->accounts->update(
            $member,
            $this->accounts->profileFromRequest($request, $member),
            $request->filled('login_email') ? $request->string('login_email')->toString() : null,
            $role,
        );

        return redirect()->route('panel.members.index')->with('status', "Data {$member->name} disimpan.");
    }

    /**
     * Delete the member and their account.
     */
    public function destroy(Member $member): RedirectResponse
    {
        Gate::authorize('delete', $member);

        $this->accounts->delete($member);

        return redirect()->route('panel.members.index')->with('status', "{$member->name} dan akunnya dihapus.");
    }

    /**
     * Put the member's account back on the default password.
     */
    public function resetPassword(Member $member): RedirectResponse
    {
        Gate::authorize('delete', $member);

        $this->accounts->resetPassword($member);

        return back()->with('status', "Sandi {$member->name} dikembalikan ke ".config('tekara.default_password').'. Ia wajib menggantinya saat masuk.');
    }
}
