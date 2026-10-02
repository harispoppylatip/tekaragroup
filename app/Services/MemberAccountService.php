<?php

namespace App\Services;

use App\Http\Requests\MemberProfileRequest;
use App\Models\Member;
use App\Models\User;
use App\UserRole;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Keeps a member's public profile and their login account in sync.
 */
class MemberAccountService
{
    public function __construct(private PublicFileStore $files) {}

    /**
     * Profile data from the form, with the photo and CV file stored or removed.
     *
     * @return array<string, mixed>
     */
    public function profileFromRequest(MemberProfileRequest $request, ?Member $member = null): array
    {
        return [
            ...$request->profile(),
            'photo' => $this->files->replace($member?->photo, $request->file('photo'), $request->boolean('remove_photo'), 'members'),
            'cv_file' => $this->files->replace($member?->cv_file, $request->file('cv_file'), $request->boolean('remove_cv_file'), 'cv'),
        ];
    }

    /**
     * Create a member together with a login account that starts with the default password.
     *
     * @param  array<string, mixed>  $profile
     */
    public function create(array $profile, string $loginEmail, UserRole $role = UserRole::Member): Member
    {
        return DB::transaction(function () use ($profile, $loginEmail, $role): Member {
            $user = User::create([
                'name' => $profile['name'],
                'email' => $loginEmail,
                'password' => config('tekara.default_password'),
                'role' => $role,
                'must_change_password' => true,
            ]);

            return Member::create([
                ...$profile,
                'user_id' => $user->id,
                'slug' => $this->uniqueSlug($profile['name']),
                'sort_order' => $profile['sort_order'] ?? (int) Member::max('sort_order') + 1,
            ]);
        });
    }

    /**
     * Update the profile and mirror the name, login email, and role onto the account.
     *
     * Members without an account (older data) get one created here.
     *
     * @param  array<string, mixed>  $profile
     */
    public function update(Member $member, array $profile, ?string $loginEmail = null, ?UserRole $role = null): Member
    {
        return DB::transaction(function () use ($member, $profile, $loginEmail, $role): Member {
            $member->update($profile);

            if ($member->user === null && $loginEmail !== null) {
                $user = User::create([
                    'name' => $member->name,
                    'email' => $loginEmail,
                    'password' => config('tekara.default_password'),
                    'role' => $role ?? UserRole::Member,
                    'must_change_password' => true,
                ]);
                $member->user()->associate($user)->save();

                return $member;
            }

            $member->user?->update(array_filter([
                'name' => $member->name,
                'email' => $loginEmail,
                'role' => $role,
            ], fn (mixed $value): bool => $value !== null));

            return $member;
        });
    }

    /**
     * Delete the member, their account, and the files they uploaded.
     */
    public function delete(Member $member): void
    {
        DB::transaction(function () use ($member): void {
            $user = $member->user;

            $member->delete();
            $user?->delete();
        });

        $this->files->delete($member->photo);
        $this->files->delete($member->cv_file);
    }

    /**
     * Put the account back on the default password and ask for a new one at next login.
     */
    public function resetPassword(Member $member): void
    {
        $member->user?->update([
            'password' => config('tekara.default_password'),
            'must_change_password' => true,
        ]);
    }

    /**
     * Slug from the name, with a numeric suffix when it is already taken.
     */
    public function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'anggota';
        $slug = $base;
        $suffix = 2;

        while (Member::query()->where('slug', $slug)->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }
}
