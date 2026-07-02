# VSL Funnel Mini-Case

This is a compact full-stack interview case for a performance marketing / direct response funnel developer role.

It demonstrates:

- VSL landing page with timed CTA behavior.
- A/B variant assignment and query parameter preservation.
- Mock ClickBank/Digistore24 checkout handoff.
- Purchase webhook that creates an order and member access.
- Upsell, downsell, thank-you, and members pages.
- Admin telemetry view for events, orders, and access grants.
- SQL schema for a production database design.
- Launch QA checklist focused on revenue-critical failures.

## Stack

- PHP 7.4 compatible.
- Plain PHP router/controllers/services to keep the demo dependency-free.
- File-backed demo storage in `storage/funnel.json`.
- Production SQL shape documented in `database/schema.sql`.
- HTML, CSS, and JavaScript.

## Run locally

```bash
php -S localhost:8080 -t public
```

Open:

```text
http://localhost:8080
```

## Demo flow

1. Start at `/vsl`.
2. Wait 8 seconds for the CTA to appear.
3. Continue to `/checkout`.
4. Submit the mock purchase form.
5. The app posts to `/webhook/purchase`.
6. The webhook creates an order and a member access token.
7. Continue through `/upsell`, `/downsell`, `/thank-you`, and `/members`.
8. Visit `/admin` to show tracked events and orders.
9. Visit `/qa` to discuss the launch checklist.

## Interview talking points

I would describe this project like this:

> I built a small revenue-critical funnel flow. The important parts are not only the pages, but the full customer journey: preserving affiliate tracking, avoiding broken checkout paths, handling a marketplace webhook, granting access only after purchase, and checking the whole path before launch.

Key engineering choices:

- I kept the runtime dependency-free so the case can run anywhere with PHP.
- I separated controllers, services, support classes, public assets, storage, and SQL.
- I treated tracking parameters as first-class data because affiliate funnels depend on attribution.
- I made the webhook idempotent by checking marketplace plus transaction ID.
- I included a QA checklist because the role specifically mentions no broken videos, audio, or checkout flows.

## What I would improve in production

- Use Laravel, Symfony, or CodeIgniter routing and middleware.
- Store orders/events/member access in MySQL or PostgreSQL.
- Verify marketplace webhook signatures.
- Add automated feature tests for every funnel route.
- Add server-side logging, alerting, and dashboarding.
- Use Cloudflare cache rules for static assets only.
- Add real A/B testing analytics and conversion reporting.
- Use a real video CDN/player and test mobile bandwidth.

## Useful curl webhook test

```bash
curl -X POST http://localhost:8080/webhook/purchase ^
  -H "Content-Type: application/json" ^
  -d "{\"marketplace\":\"digistore24\",\"transaction_id\":\"txn_api_demo\",\"email\":\"buyer@example.com\",\"product_code\":\"VSL-PRO\",\"amount_cents\":4900,\"status\":\"approved\",\"affiliate_id\":\"aff-42\",\"click_id\":\"click-99\"}"
```

