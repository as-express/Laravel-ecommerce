<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorException;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function create(Request $request, $id)
    {
        try {

            $userId = $request->user()->id;
            $payment = $this->paymentService->makePayment($userId, $id);

            return response()->json($payment);
        } catch (ErrorException $err) {
            return $err->throw($request);
        }
    }

    public function handle(Request $request)
    {
        $result = $this->paymentService->handle($request);
    }
}
