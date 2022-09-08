<?php

namespace Package\Oidc;

use Illuminate\Support\Arr;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * Date: 2022/9/1
 * @author George
 * @package Package\Oidc
 */
class OIDCUserProvider implements UserProvider
{
    public function retrieveById($identifier)
    {
        return null;
    }

    public function retrieveByToken($identifier, $token)
    {
        return null;
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        return null;
    }

    /**
     * Date: 2022/9/1
     * @param array $credentials
     * @return Operator
     * @author George
     */
    public function retrieveByCredentials(array $credentials): Operator
    {
        return new Operator([
            'id' => Arr::get($credentials, 'sub'),
            'name' => Arr::get($credentials, 'name'),
            'email' => Arr::get($credentials, 'email'),
            'rules' => Arr::get($credentials, 'urn:zitadel:iam:org:project:roles'),
            'gender' => Arr::get($credentials, 'gender'),
            'locale' => Arr::get($credentials, 'locale'),
            'picture' => Arr::get($credentials, 'picture'),
            'preferred_username' => Arr::get($credentials, 'preferred_username'),
            'updated_at' => Arr::get($credentials, 'updated_at')
        ]);
    }

    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        return true;
    }
}