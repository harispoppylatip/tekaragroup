<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\View\View;

class MemberController extends Controller
{
    /**
     * Show a member's CV.
     */
    public function show(Member $member): View
    {
        $member->load('projects');

        return view('members.show', [
            'member' => $member,
            'otherMembers' => Member::query()
                ->whereKeyNot($member->getKey())
                ->orderBy('sort_order')
                ->get(),
        ]);
    }
}
