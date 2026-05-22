<?php

declare(strict_types=1);

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/menu.php';

function sanitize_text(?string $value): string
{
    return trim(strip_tags((string) $value));
}

function calculate_cart(array $postedItems): array
{
    $lookup = menu_lookup();
    $items = [];
    $total = 0;

    foreach ($postedItems as $id => $quantity) {
        $qty = max(0, (int) $quantity);

        if ($qty === 0 || !isset($lookup[$id])) {
            continue;
        }

        $menu = $lookup[$id];
        $subtotal = $menu['price'] * $qty;
        $total += $subtotal;
        $items[] = [
            'menu_id' => $id,
            'name' => $menu['name'],
            'category' => $menu['category'],
            'unit_price' => $menu['price'],
            'quantity' => $qty,
            'subtotal' => $subtotal,
        ];
    }

    return ['items' => $items, 'total' => $total];
}

function create_order(array $payload): int
{
    $cart = calculate_cart($payload['items'] ?? []);

    if ($cart['items'] === []) {
        throw new RuntimeException('Pilih minimal satu menu.');
    }

    $customerName = sanitize_text($payload['customer_name'] ?? '');
    $customerPhone = sanitize_text($payload['customer_phone'] ?? '');
    $orderType = sanitize_text($payload['order_type'] ?? 'bungkus');
    $notes = sanitize_text($payload['notes'] ?? '');

    if ($customerName === '' || $customerPhone === '') {
        throw new RuntimeException('Nama dan nomor HP wajib diisi.');
    }

    if (!in_array($orderType, ['makan_di_tempat', 'bungkus'], true)) {
        $orderType = 'bungkus';
    }

    $pdo = db();
    $pdo->beginTransaction();

    try {
        $stmt = $pdo->prepare(
            'INSERT INTO orders (customer_name, customer_phone, order_type, notes, total_amount, status, created_at)
             VALUES (:customer_name, :customer_phone, :order_type, :notes, :total_amount, :status, NOW())'
        );
        $stmt->execute([
            ':customer_name' => $customerName,
            ':customer_phone' => $customerPhone,
            ':order_type' => $orderType,
            ':notes' => $notes,
            ':total_amount' => $cart['total'],
            ':status' => 'baru',
        ]);

        $orderId = (int) $pdo->lastInsertId();
        $itemStmt = $pdo->prepare(
            'INSERT INTO order_items (order_id, menu_id, item_name, category, unit_price, quantity, subtotal)
             VALUES (:order_id, :menu_id, :item_name, :category, :unit_price, :quantity, :subtotal)'
        );

        foreach ($cart['items'] as $item) {
            $itemStmt->execute([
                ':order_id' => $orderId,
                ':menu_id' => $item['menu_id'],
                ':item_name' => $item['name'],
                ':category' => $item['category'],
                ':unit_price' => $item['unit_price'],
                ':quantity' => $item['quantity'],
                ':subtotal' => $item['subtotal'],
            ]);
        }

        $pdo->commit();
        return $orderId;
    } catch (Throwable $exception) {
        $pdo->rollBack();
        throw $exception;
    }
}

function update_order_status(int $orderId, string $status): void
{
    if (!in_array($status, ['baru', 'diproses', 'selesai', 'batal'], true)) {
        return;
    }

    $stmt = db()->prepare('UPDATE orders SET status = :status WHERE id = :id');
    $stmt->execute([':status' => $status, ':id' => $orderId]);
}

function finance_summary(?string $from = null, ?string $to = null): array
{
    $pdo = db();
    $where = 'WHERE o.status != "batal"';
    $params = [];

    if ($from) {
        $where .= ' AND DATE(o.created_at) >= :from_date';
        $params[':from_date'] = $from;
    }

    if ($to) {
        $where .= ' AND DATE(o.created_at) <= :to_date';
        $params[':to_date'] = $to;
    }

    $summary = $pdo->prepare(
        "SELECT COUNT(*) AS order_count, COALESCE(SUM(total_amount), 0) AS revenue
         FROM orders o {$where}"
    );
    $summary->execute($params);

    $topItems = $pdo->prepare(
        "SELECT oi.item_name, SUM(oi.quantity) AS sold_qty, SUM(oi.subtotal) AS revenue
         FROM order_items oi
         INNER JOIN orders o ON o.id = oi.order_id
         {$where}
         GROUP BY oi.item_name
         ORDER BY sold_qty DESC, revenue DESC
         LIMIT 8"
    );
    $topItems->execute($params);

    $orders = $pdo->prepare(
        "SELECT id, customer_name, customer_phone, order_type, total_amount, status, created_at
         FROM orders o
         {$where}
         ORDER BY created_at DESC
         LIMIT 50"
    );
    $orders->execute($params);

    return [
        'summary' => $summary->fetch(),
        'top_items' => $topItems->fetchAll(),
        'orders' => $orders->fetchAll(),
    ];
}
