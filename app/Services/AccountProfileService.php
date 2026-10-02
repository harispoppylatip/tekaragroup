<?php

namespace App\Services;

use App\Http\Requests\AccountProfileRequest;
use App\Models\User;

class AccountProfileService
{
    public function __construct(private PublicFileStore $files) {}

    /**
     * Account columns plus the stored photo path.
     *
     * @return array<string, string|null>
     */
    public function profileFromRequest(AccountProfileRequest $request, User $user): array
    {
        return [
            ...$request->profile(),
            'photo' => $this->files->replace($user->photo, $request->file('photo'), $request->boolean('remove_photo'), 'avatars'),
        ];
    }

    /**
     * Save the account profile of the signed-in user.
     *
     * @param  array<string, string|null>  $profile
     */
    public function update(User $user, array $profile): User
    {
        $user->update($profile);

        return $user;
    }
}
