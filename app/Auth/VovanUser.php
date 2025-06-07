<?php

namespace App\Auth;

use Illuminate\Contracts\Auth\Authenticatable;

class VovanUser implements Authenticatable
{
    protected array $attributes;

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function getAuthIdentifierName()
    {
        return 'id'; // имя ключа
    }

    public function getAuthIdentifier()
    {
        return $this->attributes['id'];
    }

    public function getAuthPassword()
    {
        return $this->attributes['password'] ?? null;
    }

    public function getRememberToken()
    {
        return $this->attributes['remember_token'] ?? null;
    }

    public function setRememberToken($value)
    {
        $this->attributes['remember_token'] = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    public function toArray()
    {
        return $this->attributes;
    }

    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }
}