<?php

namespace App\Services;

use App\Support\Store;

class TrackingService
{
    private Store $store;

    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    public function track(string $eventName, array $metadata = []): void
    {
        $this->store->push('events', [
            'session_id' => session_id(),
            'event_name' => $eventName,
            'path' => $_SERVER['REQUEST_URI'] ?? '/',
            'variant' => $_GET['variant'] ?? $this->variant(),
            'affiliate_id' => $_GET['affiliate_id'] ?? null,
            'click_id' => $_GET['click_id'] ?? null,
            'campaign' => $_GET['campaign'] ?? null,
            'metadata' => $metadata,
        ]);
    }

    public function variant(): string
    {
        if (!isset($_SESSION['variant'])) {
            $_SESSION['variant'] = (crc32(session_id()) % 2 === 0) ? 'A' : 'B';
        }

        return $_GET['variant'] ?? $_SESSION['variant'];
    }

    public function queryString(): string
    {
        $allowed = ['affiliate_id', 'click_id', 'campaign', 'variant'];
        $params = [];

        foreach ($allowed as $key) {
            if (isset($_GET[$key]) && $_GET[$key] !== '') {
                $params[$key] = $_GET[$key];
            }
        }

        $params['variant'] = $this->variant();

        return http_build_query($params);
    }
}

