<?php

namespace App\Guards;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

class JwtGuard implements Guard
{
    protected $request;
    protected $provider;
    protected $user;


    public function __construct(UserProvider $provider, Request $request)
    {
        $this->provider = $provider;
        $this->request = $request;
    }

    public function hasUser(){

    }

    public function user()
    {
        if (!is_null($this->user)) {
            return $this->user;
        }

        $token = $this->request->bearerToken();

        if (!$token) {
            return null;
        }

        try {
            $decoded = JWT::decode($token, new Key(config('jwt.secret'), 'HS256'));
            $this->user = $this->provider->retrieveById($decoded->sub);
        } catch (\Exception $e) {
            return null;
        }

        return $this->user;
    }

    public function validate(array $credentials = [])
    {
        return !is_null($this->user());
    }

    public function check()
    {
        return !is_null($this->user());
    }

    public function guest()
    {
        return !$this->check();
    }

    public function id()
    {
        return $this->user() ? $this->user()->getAuthIdentifier() : null;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }
}
