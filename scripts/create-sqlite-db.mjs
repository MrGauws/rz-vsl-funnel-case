import { DatabaseSync } from 'node:sqlite';
import { existsSync, readFileSync, rmSync } from 'node:fs';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const dbPath = resolve(root, 'storage', 'funnel.sqlite');
const jsonPath = resolve(root, 'storage', 'funnel.json');
const schemaPath = resolve(root, 'database', 'schema.sqlite.sql');

if (existsSync(dbPath)) {
  rmSync(dbPath);
}

const db = new DatabaseSync(dbPath);
db.exec('PRAGMA foreign_keys = ON;');
db.exec(readFileSync(schemaPath, 'utf8'));

const fallback = {
  events: [
    {
      session_id: 'demo-session',
      event_name: 'vsl_view',
      path: '/vsl?affiliate_id=demo-affiliate&click_id=click-123&campaign=interview-prep',
      variant: 'A',
      affiliate_id: 'demo-affiliate',
      click_id: 'click-123',
      campaign: 'interview-prep',
      metadata: { source: 'seed' },
      created_at: new Date().toISOString(),
    },
  ],
  orders: [
    {
      marketplace: 'clickbank',
      transaction_id: 'txn_seed_demo',
      email: 'candidate@example.com',
      product_code: 'VSL-PRO',
      amount_cents: 4900,
      currency: 'USD',
      status: 'approved',
      affiliate_id: 'demo-affiliate',
      click_id: 'click-123',
      raw_payload: { source: 'seed' },
      created_at: new Date().toISOString(),
    },
  ],
  access: [
    {
      order_id: 1,
      email: 'candidate@example.com',
      access_token: 'seed-demo-access-token',
      product_code: 'VSL-PRO',
      created_at: new Date().toISOString(),
    },
  ],
};

const data = existsSync(jsonPath)
  ? JSON.parse(readFileSync(jsonPath, 'utf8'))
  : fallback;

const insertEvent = db.prepare(`
  INSERT INTO funnel_events (
    session_id, event_name, path, variant, affiliate_id, click_id, campaign, metadata, created_at
  ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
`);

const insertOrder = db.prepare(`
  INSERT INTO orders (
    id, marketplace, transaction_id, email, product_code, amount_cents, currency, status,
    affiliate_id, click_id, raw_payload, created_at
  ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
`);

const insertAccess = db.prepare(`
  INSERT INTO member_access (
    id, order_id, email, access_token, product_code, created_at
  ) VALUES (?, ?, ?, ?, ?, ?)
`);

for (const event of data.events ?? fallback.events) {
  insertEvent.run(
    event.session_id,
    event.event_name,
    event.path,
    event.variant ?? null,
    event.affiliate_id ?? null,
    event.click_id ?? null,
    event.campaign ?? null,
    JSON.stringify(event.metadata ?? {}),
    event.created_at ?? new Date().toISOString(),
  );
}

for (const order of data.orders ?? fallback.orders) {
  insertOrder.run(
    order.id ?? null,
    order.marketplace,
    order.transaction_id,
    order.email,
    order.product_code,
    Number(order.amount_cents),
    order.currency ?? 'USD',
    order.status,
    order.affiliate_id ?? null,
    order.click_id ?? null,
    JSON.stringify(order.raw_payload ?? {}),
    order.created_at ?? new Date().toISOString(),
  );
}

for (const access of data.access ?? fallback.access) {
  insertAccess.run(
    access.id ?? null,
    access.order_id,
    access.email,
    access.access_token,
    access.product_code,
    access.created_at ?? new Date().toISOString(),
  );
}

db.close();
console.log(`Created ${dbPath}`);

