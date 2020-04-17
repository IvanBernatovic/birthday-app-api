<?php

namespace Tests;

use Illuminate\Contracts\Auth\Authenticatable;
use JWTAuth;

trait AttachJwtToken
{
    public function actingAs(Authenticatable $user, $driver = null)
    {
        $token = JWTAuth::fromUser($user);
        $this->withHeader('Authorization', 'Bearer ' . $token);

        return $this;
    }
}
