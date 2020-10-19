<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    public function update($user, array $input)
    {
        if (!$user->can('edit-account', User::class)) {
            abort(403);
        }

        Validator::make($input, [
            'name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:50', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'min:6', 'confirmed'],
            'avatar' => ['nullable', 'image', 'max:2000', 'dimensions:min_width=250'],
        ])->validateWithBag('updateProfileInformation');

        if ($input['email'] !== $user->email && $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $userInput = [
                'name' => $input['name'],
                'email' => $input['email'],
            ];

            if (!empty($input['password'])) {
                $userInput['password'] = bcrypt($input['password']);
            }

            if (!empty($input['avatar'])) {
                $userInput['avatar'] = $input['avatar']->storePublicly('avatars/' . date('Ym'), 'public');
                if (!empty($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }
            }

            $user->forceFill($userInput)->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    protected function updateVerifiedUser(User $user, array $input)
    {
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
