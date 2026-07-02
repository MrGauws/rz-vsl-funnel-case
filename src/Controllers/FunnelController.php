<?php

namespace App\Controllers;

use App\Services\OrderService;
use App\Services\TrackingService;
use App\Support\Response;
use App\Support\View;

class FunnelController
{
    private TrackingService $tracking;
    private OrderService $orders;

    public function __construct(TrackingService $tracking, OrderService $orders)
    {
        $this->tracking = $tracking;
        $this->orders = $orders;
    }

    public function vsl(): void
    {
        $this->tracking->track('vsl_view');
        $variant = $this->tracking->variant();
        $query = $this->tracking->queryString();
        $headline = $variant === 'B'
            ? 'Build a faster launch funnel without losing tracking'
            : 'Turn paid traffic into a clean, measurable funnel';

        View::render('VSL Funnel Case', '
            <section class="hero">
                <div class="copy">
                    <p class="eyebrow">Mini-case for a full-stack funnel developer interview</p>
                    <h1>' . htmlspecialchars($headline, ENT_QUOTES, 'UTF-8') . '</h1>
                    <p class="lede">A demo VSL funnel with delayed CTA, query tracking, mock checkout, upsell/downsell, webhook-created member access, and a launch QA checklist.</p>
                    <div class="metrics">
                        <span>Variant ' . htmlspecialchars($variant, ENT_QUOTES, 'UTF-8') . '</span>
                        <span>CTA delay: 8s demo / 10m production</span>
                        <span>Tracking preserved</span>
                    </div>
                </div>
                <div class="video-shell">
                    <div class="video">
                        <div class="play">▶</div>
                        <div>
                            <strong>VSL placeholder</strong>
                            <p>In production this would be an optimized video embed/CDN asset.</p>
                        </div>
                    </div>
                    <div class="progress"><span id="watch-progress"></span></div>
                    <p class="muted">CTA appears after enough watch time to mirror direct response VSL behavior.</p>
                </div>
            </section>
            <section class="band">
                <h2>Funnel flow</h2>
                <div class="flow">
                    <span>VSL</span><span>Checkout</span><span>Upsell</span><span>Downsell</span><span>Thank you</span><span>Members</span>
                </div>
                <a class="primary hidden" id="delayed-cta" href="/checkout?' . htmlspecialchars($query, ENT_QUOTES, 'UTF-8') . '">Unlock the training</a>
            </section>
        ', ['variant' => $variant, 'query' => $query]);
    }

    public function checkout(): void
    {
        $this->tracking->track('checkout_start');
        $query = $this->tracking->queryString();

        View::render('Mock Checkout', '
            <section class="panel">
                <p class="eyebrow">Mock ClickBank / Digistore24 checkout</p>
                <h1>Checkout handoff</h1>
                <p>This page simulates preserving affiliate and campaign parameters before the marketplace checkout.</p>
                <form method="post" action="/webhook/purchase?' . htmlspecialchars($query, ENT_QUOTES, 'UTF-8') . '">
                    <label>Email <input name="email" type="email" value="candidate@example.com" required></label>
                    <input type="hidden" name="marketplace" value="clickbank">
                    <input type="hidden" name="transaction_id" value="txn_' . time() . '">
                    <input type="hidden" name="product_code" value="VSL-PRO">
                    <input type="hidden" name="amount_cents" value="4900">
                    <button class="primary" type="submit">Simulate approved purchase</button>
                </form>
                <a href="/upsell?' . htmlspecialchars($query, ENT_QUOTES, 'UTF-8') . '">Skip to upsell demo</a>
            </section>
        ', ['query' => $query]);
    }

    public function upsell(): void
    {
        $this->tracking->track('upsell_view');
        $query = $this->tracking->queryString();

        View::render('Upsell', '
            <section class="offer">
                <p class="eyebrow">Post-purchase upsell</p>
                <h1>Add the implementation sprint</h1>
                <p>Shows average-order-value logic after initial purchase. Accept routes to thank-you; decline routes to downsell.</p>
                <div class="actions">
                    <a class="primary" href="/thank-you?' . htmlspecialchars($query, ENT_QUOTES, 'UTF-8') . '&upsell=accepted">Accept upsell</a>
                    <a class="secondary" href="/downsell?' . htmlspecialchars($query, ENT_QUOTES, 'UTF-8') . '&upsell=declined">No thanks</a>
                </div>
            </section>
        ', ['query' => $query]);
    }

    public function downsell(): void
    {
        $this->tracking->track('downsell_view');
        $query = $this->tracking->queryString();

        View::render('Downsell', '
            <section class="offer">
                <p class="eyebrow">Downsell</p>
                <h1>Try the lightweight checklist instead</h1>
                <p>Lower-friction offer after upsell decline.</p>
                <div class="actions">
                    <a class="primary" href="/thank-you?' . htmlspecialchars($query, ENT_QUOTES, 'UTF-8') . '&downsell=accepted">Accept downsell</a>
                    <a class="secondary" href="/thank-you?' . htmlspecialchars($query, ENT_QUOTES, 'UTF-8') . '&downsell=declined">Continue</a>
                </div>
            </section>
        ', ['query' => $query]);
    }

    public function thankYou(): void
    {
        $this->tracking->track('thank_you_view');
        $access = $this->orders->latestAccess();
        $token = $access['access_token'] ?? 'demo-token-created-after-webhook';

        View::render('Thank You', '
            <section class="panel">
                <p class="eyebrow">Purchase complete</p>
                <h1>Thank you page</h1>
                <p>The webhook creates member access and this page sends the customer into the protected area.</p>
                <a class="primary" href="/members?token=' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">Enter members area</a>
            </section>
        ');
    }

    public function members(): void
    {
        $this->tracking->track('members_view');
        $token = $_GET['token'] ?? null;
        $access = $this->orders->latestAccess();
        $isAuthorized = $token && $access && hash_equals($access['access_token'], $token);

        View::render('Members Area', '
            <section class="panel">
                <p class="eyebrow">Members area</p>
                <h1>' . ($isAuthorized ? 'Access granted' : 'Demo access area') . '</h1>
                <p>' . ($isAuthorized ? 'The token matches the latest approved purchase.' : 'Run the checkout simulation first to generate a real access token.') . '</p>
                <ul class="checklist">
                    <li>Module 1: Funnel QA mindset</li>
                    <li>Module 2: Speed and tracking checks</li>
                    <li>Module 3: Webhook and access creation</li>
                </ul>
            </section>
        ');
    }

    public function webhook(): void
    {
        $payload = $_POST ?: json_decode(file_get_contents('php://input') ?: '{}', true);
        $payload = is_array($payload) ? $payload : [];
        $payload['affiliate_id'] = $_GET['affiliate_id'] ?? ($payload['affiliate_id'] ?? null);
        $payload['click_id'] = $_GET['click_id'] ?? ($payload['click_id'] ?? null);
        $result = $this->orders->createFromWebhook($payload);
        $this->tracking->track('purchase_webhook', ['transaction_id' => $result['order']['transaction_id']]);

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && isset($_POST['email'])) {
            Response::redirect('/upsell?' . $this->tracking->queryString());
        }

        Response::json(['ok' => true] + $result);
    }
}

