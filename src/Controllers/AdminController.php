<?php

namespace App\Controllers;

use App\Support\Store;
use App\Support\View;

class AdminController
{
    private Store $store;

    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    public function dashboard(): void
    {
        $data = $this->store->all();
        $events = array_reverse(array_slice($data['events'], -12));
        $orders = array_reverse(array_slice($data['orders'], -5));
        $eventRows = '';
        $orderRows = '';

        foreach ($events as $event) {
            $eventRows .= '<tr><td>' . htmlspecialchars($event['event_name'], ENT_QUOTES, 'UTF-8') . '</td><td>' . htmlspecialchars($event['variant'] ?? '-', ENT_QUOTES, 'UTF-8') . '</td><td>' . htmlspecialchars($event['path'], ENT_QUOTES, 'UTF-8') . '</td></tr>';
        }

        foreach ($orders as $order) {
            $orderRows .= '<tr><td>' . htmlspecialchars($order['transaction_id'], ENT_QUOTES, 'UTF-8') . '</td><td>' . htmlspecialchars($order['email'], ENT_QUOTES, 'UTF-8') . '</td><td>' . htmlspecialchars($order['status'], ENT_QUOTES, 'UTF-8') . '</td></tr>';
        }

        View::render('Admin Dashboard', '
            <section class="panel wide">
                <p class="eyebrow">Operations view</p>
                <h1>Funnel telemetry</h1>
                <div class="stats">
                    <span><strong>' . count($data['events']) . '</strong> events</span>
                    <span><strong>' . count($data['orders']) . '</strong> orders</span>
                    <span><strong>' . count($data['access']) . '</strong> access grants</span>
                </div>
                <h2>Recent events</h2>
                <table><thead><tr><th>Event</th><th>Variant</th><th>Path</th></tr></thead><tbody>' . $eventRows . '</tbody></table>
                <h2>Recent orders</h2>
                <table><thead><tr><th>Transaction</th><th>Email</th><th>Status</th></tr></thead><tbody>' . $orderRows . '</tbody></table>
            </section>
        ');
    }

    public function qa(): void
    {
        View::render('Launch QA Checklist', '
            <section class="panel wide">
                <p class="eyebrow">Launch checklist</p>
                <h1>Revenue-critical QA</h1>
                <ul class="checklist columns">
                    <li>Video loads on desktop and mobile</li>
                    <li>Audio starts and controls are visible</li>
                    <li>Delayed CTA appears at correct watch time</li>
                    <li>Affiliate, click, campaign and variant params survive every redirect</li>
                    <li>Checkout button reaches the correct marketplace URL</li>
                    <li>Approved purchase webhook creates an order</li>
                    <li>Duplicate webhook is idempotent</li>
                    <li>Upsell accept and decline paths both work</li>
                    <li>Downsell accept and decline paths both work</li>
                    <li>Thank-you page links to members area</li>
                    <li>Member access is only granted after purchase</li>
                    <li>Console has no JavaScript errors</li>
                    <li>Network waterfall has no slow critical asset</li>
                    <li>Cloudflare caches static assets, not dynamic checkout/member pages</li>
                    <li>Server logs and webhook logs are checked before launch</li>
                    <li>Rollback commit or release is known before deploy</li>
                </ul>
            </section>
        ');
    }
}

