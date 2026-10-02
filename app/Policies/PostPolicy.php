<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Every signed-in account sees the news list.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Every signed-in account may write news.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Admins edit any post; others edit what they wrote.
     */
    public function update(User $user, Post $post): bool
    {
        return $user->isAdmin() || $post->user_id === $user->id;
    }

    /**
     * Same rule as editing.
     */
    public function delete(User $user, Post $post): bool
    {
        return $this->update($user, $post);
    }
}
