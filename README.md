# FunnelOps Launch Desk

Small full-stack case for a performance marketing / direct response funnel developer role.

I built it around the parts of a funnel that tend to cause real launch problems: broken video states, missing attribution, checkout handoff mistakes, duplicate webhooks, access creation, and unclear QA ownership.

It demonstrates:

- VSL landing page with timed CTA behavior.
- A/B variant assignment and query parameter preservation.
- Mock ClickBank / Digistore24 checkout handoff.
- Purchase webhook that creates an order and member access.
- Upsell, downsell, thank-you, and members pages.
- Admin telemetry view for events, orders, and access grants.
- SQL schema for a production database design.
- Launch QA checklist focused on revenue-critical failures.

## Stack

- PHP 7.4 compatible.
- Plain PHP router/controllers/services to keep the live demo dependency-free on this machine.
- Laravel-style routes in `routes/web.php`.
- Laravel migrations in `database/migrations`.
- Vue widget on the VSL page.
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

## Open the demo database in Beekeeper

Create/update the SQLite database file:

```bash
node scripts/create-sqlite-db.mjs
```

Then open Beekeeper Studio:

1. Click **New Connection**.
2. Select **SQLite**.
3. Choose this file:

```text
storage/funnel.sqlite
```

You should see:

- `funnel_events`
- `orders`
- `member_access`

The live PHP demo writes to `storage/funnel.json` to stay dependency-free on PHP 7.4. The SQLite file is included so the same data model can be inspected in Beekeeper during the interview. In production, this would be MySQL/PostgreSQL through Laravel migrations or a repository layer.

## Framework fit

The job ad mentions PHP frameworks and React/Vue. This repo includes a runnable PHP version plus the framework-facing pieces I would use in a production implementation:

- `routes/web.php` shows the Laravel route map.
- `database/migrations` contains Laravel migration classes.
- `docs/framework-port.md` explains the Laravel/CodeIgniter port.
- `public/assets/app.js` mounts a small Vue 3 launch-signal widget when Vue is available.

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

> I built a small revenue-critical funnel flow. The interesting part is not just rendering pages, but keeping the whole path intact: traffic parameters, VSL timing, checkout handoff, marketplace webhook, upsell/downsell routing, and member access.

Key engineering choices:

- I kept the runtime dependency-free so the case can run anywhere with PHP.
- I separated controllers, services, support classes, public assets, storage, and SQL so it is easy to talk through.
- I treated tracking parameters as first-class data because affiliate funnels depend on attribution.
- I made the webhook idempotent by checking marketplace + transaction ID.
- I included a QA checklist because the role specifically calls out broken videos, audio, checkout flows, and speed.

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
