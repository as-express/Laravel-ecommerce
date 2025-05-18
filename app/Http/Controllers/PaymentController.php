<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'amount' => 'required|numeric',
        ]);

        $payment = $this->paymentService->makePayment($data['amount']);
        return response()->json($payment);
    }
}
