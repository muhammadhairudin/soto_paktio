CREATE TABLE orders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(120) NOT NULL,
    customer_phone VARCHAR(40) NOT NULL,
    order_type ENUM('makan_di_tempat', 'bungkus') NOT NULL DEFAULT 'bungkus',
    notes TEXT NULL,
    total_amount INT UNSIGNED NOT NULL DEFAULT 0,
    status ENUM('baru', 'diproses', 'selesai', 'batal') NOT NULL DEFAULT 'baru',
    created_at DATETIME NOT NULL,
    INDEX idx_orders_created_at (created_at),
    INDEX idx_orders_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE order_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    menu_id VARCHAR(80) NOT NULL,
    item_name VARCHAR(160) NOT NULL,
    category VARCHAR(80) NOT NULL,
    unit_price INT UNSIGNED NOT NULL,
    quantity INT UNSIGNED NOT NULL,
    subtotal INT UNSIGNED NOT NULL,
    CONSTRAINT fk_order_items_order
        FOREIGN KEY (order_id) REFERENCES orders(id)
        ON DELETE CASCADE,
    INDEX idx_order_items_order_id (order_id),
    INDEX idx_order_items_menu_id (menu_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
