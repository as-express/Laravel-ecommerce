<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Exceptions\ErrorException;

class JwtAuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        try {
            $data = $request->validated();
            $file = $request->file("image");

            $result = $this->authService->register($data, $file);

            return response()->json($result, 201);
        } catch (ErrorException $err) {
            return $err->throw($request);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $data = $request->validated();
            $result = $this->authService->login($data);

            return response()->json($result, 200);
        } catch (ErrorException $err) {
            return $err->throw($request);
        }
    }

    public function refresh(Request $request)
    {
        try {
            $result = $this->authService->refresh();
            return response()->json($result, 200);
        } catch (ErrorException $err) {
            return $err->throw($request);
        }
    }

    public function logout()
    {
        $this->authService->logout();
        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);
    }
}
