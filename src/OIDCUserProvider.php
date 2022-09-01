<?php

namespace Package\Oidc;

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
            'id' => $credentials['sub'],
            'name' => $credentials['name'],
            'email' => $credentials['email'],
            'rules' => $credentials['urn:zitadel:iam:org:project:roles'],
            'gender' => $credentials['gender'],
            'locale' => $credentials['locale'],
            'picture' => $credentials['picture'],
            'preferred_username' => $credentials['preferred_username'],
            'updated_at' => $credentials['updated_at'],
        ]);
    }

    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        return true;
    }
}