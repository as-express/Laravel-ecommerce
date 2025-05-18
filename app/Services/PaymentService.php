<?php

namespace App\Services;

use YooKassa\Client;

class PaymentService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setAuth(
            '1090115',
            'test_IMtWogcOKHJFye3akw1QxSdbX1wmVR9hzJTzk3jXlt0'
        );
    }

    public function makePayment($amount)
    {
        $payment = $this->client->createPayment(
            [
                'amount' => [
                    'value' => $amount,
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
