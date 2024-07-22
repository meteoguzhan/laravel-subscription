<?php

namespace App\Services\Auth;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;

interface AuthServiceInterface
{
    public function login(LoginRequest $loginRequest);

    public function register(RegisterRequest $registerRequest);
}
