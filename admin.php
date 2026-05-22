<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/app/orders.php';

$config = app_config();
$dbReady = db_available();
$loginError = null;

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'login') {
    if (hash_equals($config['admin']['username'], (string) ($_POST['username'] ?? ''))
        && hash_equals($config['admin']['password'], (string) ($_POST['password'] ?? ''))) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin.php');
        exit;
    }

    $loginError = 'Username atau password salah.';
}

$loggedIn = (bool) ($_SESSION['admin_logged_in'] ?? false);

if ($loggedIn && $dbReady && $_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'status') {
    update_order_status((int) ($_POST['order_id'] ?? 0), (string) ($_POST['status'] ?? 'baru'));
    header('Location: admin.php');
    exit;
}

$from = $_GET['from'] ?? date('Y-m-01');
$to = $_GET['to'] ?? date('Y-m-d');
$report = null;

if ($loggedIn && $dbReady) {
    $report = finance_summary($from, $to);
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Soto Pak Tio</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body class="admin-body">
    <header class="site-header">
        <a class="brand" href="index.php">
            <span class="brand-mark">PT</span>
            <span>
                <strong>Admin Pak Tio</strong>
                <small>Laporan keuangan & pesanan</small>
            </span>
        </a>
        <nav>
            <a href="index.php">Halaman Pesan</a>
            <?php if ($loggedIn): ?><a href="admin.php?logout=1">Logout</a><?php endif; ?>
        </nav>
    </header>

    <main class="admin-main">
        <?php if (!$loggedIn): ?>
            <section class="login-panel">
                <h1>Masuk Admin</h1>
                <p>Gunakan akun dari file .env untuk membuka laporan.</p>
                <?php if ($loginError): ?><div class="notice error"><?= htmlspecialchars($loginError) ?></div><?php endif; ?>
                <form method="post">
                    <input type="hidden" name="action" value="login">
                    <label>Username <input type="text" name="username" required></label>
                    <label>Password <input type="password" name="password" required></label>
                    <button class="button primary full" type="submit">Masuk</button>
                </form>
            </section>
        <?php else: ?>
            <section class="dashboard-heading">
                <div>
                    <h1>Laporan Keuangan</h1>
                    <p>Ringkasan omzet, pesanan, dan menu terlaris berdasarkan transaksi tersimpan.</p>
                </div>
                <form class="date-filter" method="get">
                    <label>Dari <input type="date" name="from" value="<?= htmlspecialchars($from) ?>"></label>
                    <label>Sampai <input type="date" name="to" value="<?= htmlspecialchars($to) ?>"></label>
                    <button class="button ghost" type="submit">Filter</button>
                </form>
            </section>

            <?php if (!$dbReady): ?>
                <div class="notice warning">Database belum aktif. Buat database MySQL lalu isi .env agar laporan berjalan.</div>
            <?php else: ?>
                <section class="metric-grid">
                    <article class="metric">
                        <span>Total Omzet</span>
                        <strong><?= rupiah((int) $report['summary']['revenue']) ?></strong>
                    </article>
                    <article class="metric">
                        <span>Jumlah Pesanan</span>
                        <strong><?= (int) $report['summary']['order_count'] ?></strong>
                    </article>
                    <article class="metric">
                        <span>Rata-rata Nota</span>
                        <strong>
                            <?php
                            $count = max(1, (int) $report['summary']['order_count']);
                            echo rupiah((int) $report['summary']['revenue'] / $count);
                            ?>
                        </strong>
                    </article>
                </section>

                <section class="admin-grid">
                    <article class="report-panel">
                        <h2>Menu Terlaris</h2>
                        <table>
                            <thead><tr><th>Menu</th><th>Qty</th><th>Omzet</th></tr></thead>
                            <tbody>
                                <?php foreach ($report['top_items'] as $item): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['item_name']) ?></td>
                                        <td><?= (int) $item['sold_qty'] ?></td>
                                        <td><?= rupiah((int) $item['revenue']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </article>

                    <article class="report-panel">
                        <h2>Pesanan Terbaru</h2>
                        <div class="table-scroll">
                            <table>
                                <thead>
                                    <tr><th>ID</th><th>Pelanggan</th><th>Tipe</th><th>Total</th><th>Status</th><th>Aksi</th></tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($report['orders'] as $order): ?>
                                        <tr>
                                            <td>#<?= (int) $order['id'] ?></td>
                                            <td>
                                                <?= htmlspecialchars($order['customer_name']) ?><br>
                                                <small><?= htmlspecialchars($order['customer_phone']) ?></small>
                                            </td>
                                            <td><?= $order['order_type'] === 'makan_di_tempat' ? 'Makan di tempat' : 'Bungkus' ?></td>
                                            <td><?= rupiah((int) $order['total_amount']) ?></td>
                                            <td><span class="status <?= htmlspecialchars($order['status']) ?>"><?= htmlspecialchars($order['status']) ?></span></td>
                                            <td>
                                                <form method="post" class="status-form">
                                                    <input type="hidden" name="action" value="status">
                                                    <input type="hidden" name="order_id" value="<?= (int) $order['id'] ?>">
                                                    <select name="status">
                                                        <?php foreach (['baru', 'diproses', 'selesai', 'batal'] as $status): ?>
                                                            <option value="<?= $status ?>" <?= $order['status'] === $status ? 'selected' : '' ?>><?= $status ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <button type="submit">OK</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </article>
                </section>
            <?php endif; ?>
        <?php endif; ?>
    </main>
</body>
</html>
