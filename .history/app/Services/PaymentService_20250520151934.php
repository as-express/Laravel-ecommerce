<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;
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

        Log::info("Handle called by user ID: {$userId}", ['data' => $data]);

        $event = $data['event'] ?? null;
        $object = $data['object'] ?? null;

        if (!$event || !$object || !isset($object['id'])) {
            Log::warning("Invalid payload received", ['payload' => $data]);
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        $paymentId = $object['id'];

        $order = Order::where('payment_id', $paymentId)->first();

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $order->discount = 100;
        $order->save();

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
                Log::warning("Unknown event type received", ['event' => $event]);
                return 'UNKNOWN';
        }

        $order->save();
        Log::info("Order updated", ['order_id' => $order->id, 'status' => $order->status]);

        return 'ok';
    }
}
