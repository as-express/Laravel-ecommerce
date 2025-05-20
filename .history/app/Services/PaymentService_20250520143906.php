<?php

namespace App\Services;

use App\Models\Order;
use YooKassa\Client;

class PaymentService
{
    protected $client;
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->client = new Client();
        $this->client->setAuth(
            '1090115',
            'test_IMtWogcOKHJFye3akw1QxSdbX1wmVR9hzJTzk3jXlt0'
        );

        $this->orderService = $orderService;
    }

    public function makePayment($userId, $orderId)
    {
        $order = $this->orderService->getOrder($userId, $orderId);
        $payment = $this->client->createPayment(
            [
                'amount' => [
                    'value' => $order->total_price,
                    'currency' => 'RUB',
                ],
                'confirmation' => [
                    'type' => 'redirect',
                    'return_url' => 'https://github.com/as-express',
                ],
                'capture' => true,
                'description' => 'Оплата заказа',
            ],
            uniqid('', true)
        );

        $paymentId = $payment->getId();
        $order->payment_id = $paymentId;

        $order->save();
        return $payment;
    }

    public function handle($request)
    {
        $data = $request->all();
        $userId = $request->user()->id;

        dd($userId);


        $event = $data['event'] ?? null;
        $object = $data['object'] ?? null;

        if (!$event || !$object || !isset($object['id'])) {
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        $paymentId = $object['id'];
        $order = Order::where('payment_id', $paymentId)->first();

        $order->discount  = 100;
        $order->save();


        dd($paymentId);


        switch ($event) {
            case 'payment.waiting_for_capture':
                $order->status = 'WAITING_FOR_CAPTURE';
                break;

            case 'payment.succeeded':
                $order->status = 'SUCCEEDED';
                break;

            case 'payment.canceled':
                $order->status = 'CANCELED';
                break;

            case 'refund.succeeded':
                $order->status = 'REFUNDED';
                break;

            default:
                return 'UNKNOWN';
                break;
        }

        $order->save();

        return 'ok';
    }
}
