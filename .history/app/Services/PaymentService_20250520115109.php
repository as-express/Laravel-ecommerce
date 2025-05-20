<?php

namespace App\Services;

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

        return $payment;
    }

    public function handle($request)
    {
        $data = $request->all();
        $userId = $request->user()->id;

        $event = $data['event'] ?? null;
        $object = $data['object'] ?? null;

        if (!$event || !$object || !isset($object['id'])) {
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        $paymentId = $object['id'];

        switch ($event) {
            case 'payment.waiting_for_capture':
                return 'WAITING_FOR_CAPTURE';
                break;

            case 'payment.succeeded':
                return 'SUCCEEDED';
                break;

            case 'payment.canceled':
                return 'CANCELED';
                break;

            case 'refund.succeeded':
                return 'REFUND_SUCCEEDED';
                break;

            default:
                return 'UNKNOWN';
                break;
        }

        $order->save();

        return 'ok';
    }
}
