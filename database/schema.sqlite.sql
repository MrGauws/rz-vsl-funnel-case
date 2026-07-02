CREATE TABLE IF NOT EXISTS funnel_events (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    session_id TEXT NOT NULL,
    event_name TEXT NOT NULL,
    path TEXT NOT NULL,
    variant TEXT,
    affiliate_id TEXT,
    click_id TEXT,
    campaign TEXT,
    metadata TEXT,
    created_at TEXT NOT NULL
);

CREATE INDEX IF NOT EXISTS idx_funnel_events_session_created
    ON funnel_events (session_id, created_at);

CREATE INDEX IF NOT EXISTS idx_funnel_events_event_created
    ON funnel_events (event_name, created_at);

CREATE TABLE IF NOT EXISTS orders (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    marketplace TEXT NOT NULL,
    transaction_id TEXT NOT NULL,
    email TEXT NOT NULL,
    product_code TEXT NOT NULL,
    amount_cents INTEGER NOT NULL,
    currency TEXT NOT NULL DEFAULT 'USD',
    status TEXT NOT NULL,
    affiliate_id TEXT,
    click_id TEXT,
    raw_payload TEXT NOT NULL,
    created_at TEXT NOT NULL,
    UNIQUE (marketplace, transaction_id)
);

CREATE INDEX IF NOT EXISTS idx_orders_email
    ON orders (email);

CREATE INDEX IF NOT EXISTS idx_orders_status_created
    ON orders (status, created_at);

CREATE TABLE IF NOT EXISTS member_access (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_id INTEGER NOT NULL,
    email TEXT NOT NULL,
    access_token TEXT NOT NULL UNIQUE,
    product_code TEXT NOT NULL,
    created_at TEXT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id)
);

CREATE INDEX IF NOT EXISTS idx_member_access_email
    ON member_access (email);

