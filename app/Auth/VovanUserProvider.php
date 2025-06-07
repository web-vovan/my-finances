<?php

namespace App\Auth;

use App\Adapters\VovanDB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;

class VovanUserProvider implements UserProvider
{
    public function retrieveById($identifier)
    {
        $user = VovanDB::selectFirst("
            SELECT *
            FROM users
            WHERE id = " . $identifier . "
        ");
        
        return $user ? new VovanUser($user) : null;
    }

    public function retrieveByToken($identifier, $token) {
        $user = VovanDB::selectFirst("
            SELECT *
            FROM users
            WHERE id = " . $identifier . "
            AND remember_token = '" . $token ."'
        ");

        return $user ? new VovanUser($user) : null;
    }
    public function updateRememberToken(Authenticatable $user, $token) { /* если нужно */ }

    public function retrieveByCredentials(array $credentials)
    {
        $user = VovanDB::selectFirst("
            SELECT *
            FROM users
            WHERE login ='" .  $credentials['login'] . "'
        ");

        if (is_null($user)) {
            return null;
        }

        return new VovanUser($user);
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return Hash::check($credentials['password'], $user->getAuthPassword());
    }
}