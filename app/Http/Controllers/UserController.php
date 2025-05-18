<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorException;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }


    public function profile(Request $request)
    {
        try {
            $userId = $request->user()->id;
            $result = $this->userService->getProfile($userId);

            return response()->json($result, 200);
        } catch (ErrorException $err) {
            return $err->throw($request);
        }
    }

    public function favorite(Request $request)
    {
        try {
            $userId = $request->user()->id;
            $result = $this->userService->getFavorites($userId);

            return response()->json($result, 200);
        } catch (ErrorException $err) {
            return $err->throw($request);
        }
    }

    public function card(Request $request)
    {
        try {
            $userId = $request->user()->id;
            $result = $this->userService->getCard($userId);

            return response()->json($result, 200);
        } catch (ErrorException $err) {
            return $err->throw($request);
        }
    }

    public function orders(Request $request)
    {
        try {
            $userId = $request->user()->id;
            $result = $this->userService->getOrders($userId);

            return response()->json($result, 200);
        } catch (ErrorException $err) {
            return $err->throw($request);
        }
    }
}
