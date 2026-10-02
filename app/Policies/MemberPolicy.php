<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\User;

class MemberPolicy
{
    /**
     * Only admins manage the member list.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Only admins add members (and with them, new accounts).
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Admins edit anyone; members edit their own CV.
     */
    public function update(User $user, Member $member): bool
    {
        return $user->isAdmin() || $member->user_id === $user->id;
    }

    /**
     * Admins delete members, but never the member tied to their own account.
     */
    public function delete(User $user, Member $member): bool
    {
        return $user->isAdmin() && $member->user_id !== $user->id;
    }
}
