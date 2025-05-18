<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorException;
use App\Http\Requests\CreateOrder;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function create(CreateOrder $request)
    {
        try {
            $data = $request->validated();
            $userId = $request->user()->id;
            $result = $this->orderService->create($userId, $data);

            return response()->json($result, 201);
        } catch (ErrorException $err) {
            return $err->throw($request);
        }
    }
}
