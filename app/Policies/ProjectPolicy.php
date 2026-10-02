<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Every signed-in account sees the project list.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Admins and members with a profile may add projects.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->member !== null;
    }

    /**
     * Admins edit any project; members edit projects they worked on.
     */
    public function update(User $user, Project $project): bool
    {
        return $user->isAdmin() || $this->isOnTeam($user, $project);
    }

    /**
     * Same rule as editing.
     */
    public function delete(User $user, Project $project): bool
    {
        return $this->update($user, $project);
    }

    /**
     * Whether the user's member profile is attached to the project.
     */
    private function isOnTeam(User $user, Project $project): bool
    {
        $memberId = $user->member?->id;

        return $memberId !== null && $project->members()->whereKey($memberId)->exists();
    }
}
