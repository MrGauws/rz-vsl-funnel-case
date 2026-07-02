<?php

namespace App\Services;

use App\Support\Store;

class OrderService
{
    private Store $store;

    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    public function createFromWebhook(array $payload): array
    {
        $data = $this->store->all();
        $marketplace = $payload['marketplace'] ?? 'clickbank';
        $transactionId = $payload['transaction_id'] ?? ('demo-' . time());

        foreach ($data['orders'] as $order) {
            if ($order['marketplace'] === $marketplace && $order['transaction_id'] === $transactionId) {
                return ['order' => $order, 'access' => $this->findAccessByOrderId($order['id']), 'duplicate' => true];
            }
        }

        $order = $this->store->push('orders', [
            'marketplace' => $marketplace,
            'transaction_id' => $transactionId,
            'email' => $payload['email'] ?? 'customer@example.com',
            'product_code' => $payload['product_code'] ?? 'VSL-PRO',
            'amount_cents' => (int)($payload['amount_cents'] ?? 4900),
            'currency' => $payload['currency'] ?? 'USD',
            'status' => $payload['status'] ?? 'approved',
            'affiliate_id' => $payload['affiliate_id'] ?? null,
            'click_id' => $payload['click_id'] ?? null,
            'raw_payload' => $payload,
        ]);

        $access = $this->store->push('access', [
            'order_id' => $order['id'],
            'email' => $order['email'],
            'product_code' => $order['product_code'],
            'access_token' => hash('sha256', $order['email'] . $order['transaction_id'] . microtime(true)),
        ]);

        return ['order' => $order, 'access' => $access, 'duplicate' => false];
    }

    public function latestAccess(): ?array
    {
        $access = $this->store->all()['access'];

        return $access ? $access[count($access) - 1] : null;
    }

    private function findAccessByOrderId(int $orderId): ?array
    {
        foreach ($this->store->all()['access'] as $access) {
            if ((int)$access['order_id'] === $orderId) {
                return $access;
            }
        }

        return null;
    }
}

