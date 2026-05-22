<?php

declare(strict_types=1);

require_once __DIR__ . '/app/orders.php';

$dbReady = db_available();
$success = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($dbReady) {
        try {
            $orderId = create_order($_POST);
            $success = 'Pesanan #' . $orderId . ' berhasil masuk. Kami akan menyiapkannya.';
        } catch (Throwable $exception) {
            $error = $exception->getMessage();
        }
    } else {
        $cart = calculate_cart($_POST['items'] ?? []);
        $success = $cart['items'] === []
            ? null
            : 'Mode demo: pesanan berhasil dihitung senilai ' . rupiah($cart['total']) . '. Aktifkan database untuk menyimpan pesanan.';
        $error = $cart['items'] === [] ? 'Pilih minimal satu menu.' : null;
    }
}

$grouped = [];
foreach (menu_items() as $item) {
    $grouped[$item['category']][] = $item;
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Soto Pak Tio - Pesan Online</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <header class="site-header">
        <a class="brand" href="index.php" aria-label="Soto Pak Tio">
            <span class="brand-mark">PT</span>
            <span>
                <strong>Pak Tio</strong>
                <small>Soto Semarang & Soto Kwali</small>
            </span>
        </a>
        <nav>
            <a href="#pesan">Pesan</a>
            <a href="#menu">Menu</a>
            <a href="admin.php">Admin</a>
        </nav>
    </header>

    <main>
        <section class="hero">
            <div class="hero-copy">
                <h1>Soto hangat Pak Tio, siap makan di tempat atau bungkus.</h1>
                <p>Pesan Soto Semarang, Soto Kwali, gorengan, sate, dan minuman langsung dari halaman ini. Total belanja dihitung otomatis.</p>
                <div class="hero-actions">
                    <a class="button primary" href="#pesan">Mulai Pesan</a>
                    <a class="button ghost" href="https://wa.me/6282156317654" target="_blank" rel="noopener">WhatsApp</a>
                </div>
                <dl class="quick-info">
                    <div><dt>Jam</dt><dd>06.00 - 13.00</dd></div>
                    <div><dt>Lokasi</dt><dd>Jl. Pemuda, Kuala Pembuang</dd></div>
                    <div><dt>Kontak</dt><dd>0821-5631-7654</dd></div>
                </dl>
            </div>
            <div class="hero-poster">
                <img src="assets/spanduk-didalam.png" alt="Daftar menu Soto Pak Tio">
            </div>
        </section>

        <section id="pesan" class="order-section">
            <div class="section-heading">
                <h2>Form Pemesanan</h2>
                <p>Pilih jumlah menu, isi nama dan nomor HP, lalu kirim pesanan.</p>
            </div>

            <?php if ($success): ?>
                <div class="notice success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="notice error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if (!$dbReady): ?>
                <div class="notice warning">Database belum aktif. Halaman tetap bisa dicoba dalam mode demo.</div>
            <?php endif; ?>

            <form method="post" class="order-layout">
                <div class="menu-picker" id="menu">
                    <?php foreach ($grouped as $category => $items): ?>
                        <section class="menu-group">
                            <h3><?= htmlspecialchars($category) ?></h3>
                            <div class="menu-list">
                                <?php foreach ($items as $item): ?>
                                    <label class="menu-row">
                                        <span>
                                            <strong><?= htmlspecialchars($item['name']) ?></strong>
                                            <small><?= rupiah($item['price']) ?></small>
                                        </span>
                                        <input
                                            type="number"
                                            name="items[<?= htmlspecialchars($item['id']) ?>]"
                                            min="0"
                                            max="99"
                                            value="0"
                                            data-price="<?= (int) $item['price'] ?>"
                                            data-name="<?= htmlspecialchars($item['name']) ?>"
                                        >
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endforeach; ?>
                </div>

                <aside class="checkout-panel">
                    <h3>Data Pesanan</h3>
                    <label>Nama Pemesan
                        <input type="text" name="customer_name" placeholder="Nama pelanggan" required>
                    </label>
                    <label>Nomor HP
                        <input type="tel" name="customer_phone" placeholder="08..." required>
                    </label>
                    <fieldset>
                        <legend>Tipe Pesanan</legend>
                        <label class="radio"><input type="radio" name="order_type" value="makan_di_tempat" checked> Makan di tempat</label>
                        <label class="radio"><input type="radio" name="order_type" value="bungkus"> Bungkus</label>
                    </fieldset>
                    <label>Catatan
                        <textarea name="notes" rows="3" placeholder="Contoh: tanpa sambal, ambil jam 09.00"></textarea>
                    </label>
                    <div class="total-box">
                        <span>Total</span>
                        <strong id="cartTotal">Rp 0</strong>
                    </div>
                    <ul class="cart-preview" id="cartPreview"></ul>
                    <button class="button primary full" type="submit">Kirim Pesanan</button>
                </aside>
            </form>
        </section>
    </main>

    <footer>
        <span>Soto Pak Tio</span>
        <span>Menu sederhana, rasa yang kami jaga setiap hari.</span>
    </footer>

    <script src="assets/app.js"></script>
</body>
</html>
