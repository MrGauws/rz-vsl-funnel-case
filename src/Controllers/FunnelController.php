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
            ? 'Keep the launch moving without breaking attribution'
            : 'A small funnel built around the checks that actually matter';
        $subhead = $variant === 'B'
            ? 'Variant B leads with speed and tracking. Same funnel, different angle, so the team can compare message fit without changing checkout logic.'
            : 'Variant A leads with launch quality: video, CTA timing, checkout handoff, webhook, access, and the messy details that decide if a funnel is ready.';

        View::render('VSL Funnel Case', '
            <section class="hero">
                <div class="copy">
                    <p class="eyebrow">Interview build / direct-response funnel</p>
                    <h1>' . htmlspecialchars($headline, ENT_QUOTES, 'UTF-8') . '</h1>
                    <p class="lede">' . htmlspecialchars($subhead, ENT_QUOTES, 'UTF-8') . '</p>
                    <div class="metrics">
                        <span><strong>Variant</strong> ' . htmlspecialchars($variant, ENT_QUOTES, 'UTF-8') . '</span>
                        <span><strong>CTA</strong> 8s demo / 10m live</span>
                        <span><strong>Params</strong> aff + click + campaign</span>
                    </div>
                </div>
                <div class="video-shell">
                    <div class="video">
                        <div class="player-top"><span>VSL_MASTER_v3.mp4</span><span>02:14 / 18:36</span></div>
                        <div class="play">Play</div>
                        <div>
                            <strong>Sales video placeholder</strong>
                            <p>Real build would use a CDN/player, poster image, captions and mobile bandwidth checks.</p>
                        </div>
                    </div>
                    <div class="progress"><span id="watch-progress"></span></div>
                    <p class="muted">The buy button is intentionally delayed, the same pattern used on long-form VSL pages.</p>
                </div>
            </section>
            <section class="band">
                <div class="section-head">
                    <div>
                        <p class="eyebrow">Current path</p>
                        <h2>From click to access</h2>
                    </div>
                    <a class="text-link" href="/qa">Open launch QA</a>
                </div>
                <div class="flow">
                    <span>Traffic</span><span>VSL</span><span>Checkout</span><span>Webhook</span><span>Upsell</span><span>Members</span>
                </div>
                <div class="cta-row">
                    <a class="primary hidden" id="delayed-cta" href="/checkout?' . htmlspecialchars($query, ENT_QUOTES, 'UTF-8') . '">Continue to checkout</a>
                    <p class="muted">Watch the progress bar: the CTA appears after the demo delay.</p>
                </div>
            </section>
        ', ['variant' => $variant, 'query' => $query]);
    }

    public function checkout(): void
    {
        $this->tracking->track('checkout_start');
        $query = $this->tracking->queryString();

        View::render('Mock Checkout', '
            <section class="panel">
                <p class="eyebrow">Marketplace handoff</p>
                <h1>Checkout with attribution intact</h1>
                <p>The important bit here is not the form. It is keeping affiliate, click and campaign values alive before the buyer leaves for ClickBank or Digistore24.</p>
                <form method="post" action="/webhook/purchase?' . htmlspecialchars($query, ENT_QUOTES, 'UTF-8') . '">
                    <label>Email <input name="email" type="email" value="candidate@example.com" required></label>
                    <input type="hidden" name="marketplace" value="clickbank">
                    <input type="hidden" name="transaction_id" value="txn_' . time() . '">
                    <input type="hidden" name="product_code" value="VSL-PRO">
                    <input type="hidden" name="amount_cents" value="4900">
                    <button class="primary" type="submit">Approve test purchase</button>
                </form>
                <div class="debug-strip"><span>affiliate_id</span><code>' . htmlspecialchars($_GET['affiliate_id'] ?? 'none', ENT_QUOTES, 'UTF-8') . '</code><span>click_id</span><code>' . htmlspecialchars($_GET['click_id'] ?? 'none', ENT_QUOTES, 'UTF-8') . '</code></div>
                <a class="text-link" href="/upsell?' . htmlspecialchars($query, ENT_QUOTES, 'UTF-8') . '">Skip to upsell demo</a>
            </section>
        ', ['query' => $query]);
    }

    public function upsell(): void
    {
        $this->tracking->track('upsell_view');
        $query = $this->tracking->queryString();

        View::render('Upsell', '
            <section class="offer">
                <p class="eyebrow">One-click offer</p>
                <h1>Add the implementation sprint</h1>
                <p>A simple AOV step after the first purchase. Accept goes straight to the thank-you page; decline sends the buyer to a lower-friction downsell.</p>
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
                <h1>Try the launch checklist instead</h1>
                <p>This gives the funnel another chance to convert without blocking the buyer from getting access.</p>
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
                <h1>Access is ready</h1>
                <p>The purchase webhook has created the access record. The thank-you page now points the customer to the member area.</p>
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
                <p>' . ($isAuthorized ? 'The access token matches the latest approved order.' : 'Run the checkout simulation first to generate a real access token.') . '</p>
                <div class="module-grid">
                    <article><span>01</span><strong>Pre-launch QA</strong><p>Video, audio, CTA timing, mobile layout and console checks.</p></article>
                    <article><span>02</span><strong>Attribution</strong><p>Affiliate, click and campaign params across the whole path.</p></article>
                    <article><span>03</span><strong>Fulfilment</strong><p>Webhook validation, order storage and member access creation.</p></article>
                </div>
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
