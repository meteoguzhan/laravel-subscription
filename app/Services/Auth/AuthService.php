<?php

namespace App\Services\Auth;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService implements AuthServiceInterface
{
    public function login(LoginRequest $loginRequest)
    {
        $credentials = $loginRequest->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            abort(401, 'Invalid credentials');
        }

        $user = Auth::user();
        return $user->createToken('authToken')->plainTextToken;
    }

    public function register(RegisterRequest $registerRequest): User
    {
        $data = $registerRequest->validated();
        $data['password'] = Hash::make($data['password']);

        return User::create($data);
    }
}
