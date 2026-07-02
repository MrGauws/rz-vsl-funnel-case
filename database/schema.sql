CREATE TABLE funnel_events (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(64) NOT NULL,
    event_name VARCHAR(80) NOT NULL,
    path VARCHAR(255) NOT NULL,
    variant VARCHAR(16) NULL,
    affiliate_id VARCHAR(80) NULL,
    click_id VARCHAR(120) NULL,
    campaign VARCHAR(120) NULL,
    metadata JSON NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_session_created (session_id, created_at),
    INDEX idx_event_created (event_name, created_at)
);

CREATE TABLE orders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    marketplace VARCHAR(40) NOT NULL,
    transaction_id VARCHAR(120) NOT NULL,
    email VARCHAR(255) NOT NULL,
    product_code VARCHAR(80) NOT NULL,
    amount_cents INT NOT NULL,
    currency CHAR(3) NOT NULL DEFAULT 'USD',
    status VARCHAR(40) NOT NULL,
    affiliate_id VARCHAR(80) NULL,
    click_id VARCHAR(120) NULL,
    raw_payload JSON NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_marketplace_transaction (marketplace, transaction_id),
    INDEX idx_email (email),
    INDEX idx_status_created (status, created_at)
);

CREATE TABLE member_access (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    email VARCHAR(255) NOT NULL,
    access_token CHAR(64) NOT NULL,
    product_code VARCHAR(80) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_access_token (access_token),
    INDEX idx_member_email (email),
    CONSTRAINT fk_member_access_order FOREIGN KEY (order_id) REFERENCES orders(id)
);

