<?php

namespace App\Services;

use App\Events\UserLoggedIn;
use App\Models\User;
use App\Exceptions\ErrorException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    public function register($data, $image)
    {
        $isExist = User::where('email', $data['email'])->first();
        if ($isExist) {
            throw new ErrorException('Email already exists', 400);
        }

        if ($image) {
            $url = Storage::disk('s3')->put('images', $image);
            $data['avatar'] = $url;
        }
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);
        $token = JWTAuth::fromUser($user);

        event(new UserLoggedIn($user->id));
        return [
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token,
        ];
    }

    public function login($data)
    {
        $user = User::where('email', $data['email'])->first();
        if (!$user) {
            throw new ErrorException('Email does not exist', 409);
        }
        if (!Hash::check($data['password'], $user->password)) {
            throw new ErrorException('Password is incorrect', 401);
        }

        $token = JWTAuth::fromUser($user);
        return [
            'message' => 'User logged in successfully',
            'token' => $token,
        ];
    }

    public function refresh()
    {
        $token = Auth::refresh();
        return [
            'message' => 'Token refreshed successfully',
            'token' => $token,
        ];
    }

    public function logout()
    {
        Auth::logout();
    }
}
