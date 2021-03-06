<?php

namespace Carpentree\Core\Services;

use Carpentree\Core\Models\LinkedSocialAccount;
use Carpentree\Core\Models\User;
use Illuminate\Auth\Events\Registered;
use Laravel\Socialite\Two\User as ProviderUser;

class SocialAccountsService
{
    /**
     * Find or create user instance by provider user instance and provider name.
     *
     * @param ProviderUser $providerUser
     * @param string $provider
     *
     * @return User
     */
    public function findOrCreate(ProviderUser $providerUser, string $provider): User
    {
        $linkedSocialAccount = LinkedSocialAccount::where('provider_name', $provider)
            ->where('provider_id', $providerUser->getId())
            ->first();

        if ($linkedSocialAccount) {
            return $linkedSocialAccount->user;
        } else {
            $user = null;

            if ($email = $providerUser->getEmail()) {
                /** @var User $user */
                $user = User::where('email', $email)->first();

                if (!$user) {
                    $user = User::create([
                        'first_name' => $providerUser->getName(),
                        'last_name' => $providerUser->getName(),
                        'email' => $providerUser->getEmail(),
                    ]);

                    event(new Registered($user));
                }

                $user->linkedSocialAccounts()->create([
                    'provider_id' => $providerUser->getId(),
                    'provider_name' => $provider,
                ]);
            }

            return $user;

        }
    }
}
