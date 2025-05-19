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
            'test_IMtWogcOKHJFye3akw1QxSdbX1wmVR9hzJTzk3jXlt0',
            // config(
            // 'yookassa.shop_id'),
            //  config('yookassa.secret_key'));

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
}
