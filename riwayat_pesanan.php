<?php
session_start();
require "config/koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$query = mysqli_query($koneksi, "SELECT * FROM pesanan WHERE user_id = $user_id ORDER BY tanggal DESC");
$pesanan_list = [];
while ($row = mysqli_fetch_assoc($query)) {
    $detail_query = mysqli_query($koneksi, "SELECT * FROM pesanan_detail WHERE pesanan_id = " . $row['id']);
    $row['detail'] = [];
    while ($d = mysqli_fetch_assoc($detail_query)) {
        $row['detail'][] = $d;
    }
    $pesanan_list[] = $row;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - Furniturery Store</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <header class="navbar">
        <div class="container navbar-inner">
            <div class="navbar-left">
                <button class="hamburger-btn" onclick="document.getElementById('mainMenu').classList.toggle('open')">&#9776;</button>
                <a href="index.php" class="logo">Furniturery<span>Store</span></a>

                <nav id="mainMenu" class="admin-dropdown">
                    <a href="index.php">Home</a>
                    <a href="katalog_produk.php">Katalog</a>
                    <a href="tentang.php">Tentang</a>
                    <a href="kontak.php">Kontak</a>
                    <a href="keranjang.php">Keranjang</a>
                    <a href="riwayat_pesanan.php">Riwayat Pesanan</a>
                    <a href="logout.php">Logout</a>
                </nav>
            </div>

            <div class="admin-info">
                <a href="keranjang.php" class="keranjang-icon">&#128722;</a>
                <span class="admin-icon">&#128100;</span>
                <span class="admin-name"><?php echo $_SESSION['user_username']; ?></span>
            </div>
        </div>
    </header>

    <section class="page-header">
        <div class="container">
            <h1>Riwayat Pesanan</h1>
            <p>Pantau status pesanan kamu di sini</p>
        </div>
    </section>

    <section class="pesanan-section">
        <div class="container">
            <?php if (empty($pesanan_list)): ?>
                <p style="text-align:center; color:#5a5a5a; padding: 60px 0;">
                    Kamu belum memiliki pesanan. <a href="katalog_produk.php" style="color:#b07d4f;">Mulai belanja</a>
                </p>
            <?php else: ?>
                <?php foreach ($pesanan_list as $p): ?>
                <div class="pesanan-card">
                    <div class="pesanan-card-header">
                        <div>
                            <h3>Pesanan #<?php echo $p['id']; ?></h3>
                            <p class="pesanan-meta"><?php echo date('d M Y, H:i', strtotime($p['tanggal'])); ?></p>
                        </div>
                        <span class="pesanan-status status-<?php echo strtolower(str_replace(' ', '-', $p['status'])); ?>">
                            <?php echo $p['status']; ?>
                        </span>
                    </div>

                    <div class="pesanan-card-body">
                        <div class="pesanan-info-grid">
                            <p><strong>Penerima:</strong> <?php echo htmlspecialchars($p['nama_penerima']); ?></p>
                            <p><strong>No. HP:</strong> <?php echo htmlspecialchars($p['no_hp']); ?></p>
                            <p><strong>Metode Bayar:</strong> <?php echo htmlspecialchars($p['metode_bayar']); ?></p>
                            <p class="pesanan-alamat"><strong>Alamat:</strong> <?php echo htmlspecialchars($p['alamat']); ?></p>
                            <?php if (!empty($p['catatan'])): ?>
                                <p class="pesanan-alamat"><strong>Catatan:</strong> <?php echo htmlspecialchars($p['catatan']); ?></p>
                            <?php endif; ?>
                        </div>

                        <table class="pesanan-detail-table">
                            <tr>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                            <?php foreach ($p['detail'] as $d): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($d['nama_produk']); ?></td>
                                <td>Rp <?php echo number_format($d['harga'], 0, ',', '.'); ?></td>
                                <td><?php echo $d['jumlah']; ?></td>
                                <td>Rp <?php echo number_format($d['subtotal'], 0, ',', '.'); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </table>

                        <div class="pesanan-footer">
                            <p class="pesanan-total">Total: Rp <?php echo number_format($p['total'], 0, ',', '.'); ?></p>
                            <div class="riwayat-status-info">
                                <?php
                                $status = $p['status'];
                                $steps = ['Menunggu Konfirmasi', 'Diproses', 'Dikirim', 'Selesai'];
                                $current = array_search($status, $steps);
                                if ($status === 'Dibatalkan') {
                                    echo '<span class="pesanan-status status-dibatalkan">Pesanan Dibatalkan</span>';
                                } else {
                                    foreach ($steps as $i => $step) {
                                        $active = ($i <= $current) ? 'active' : '';
                                        echo "<span class=\"step-item $active\">$step</span>";
                                        if ($i < count($steps) - 1) echo '<span class="step-arrow">›</span>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 Furniturery Store. Semua hak dilindungi.</p>
        </div>
    </footer>

    <script>
        document.addEventListener('click', function(e) {
            var menu = document.getElementById('mainMenu');
            var btn = document.querySelector('.hamburger-btn');
            if (menu && !menu.contains(e.target) && !btn.contains(e.target)) {
                menu.classList.remove('open');
            }
        });
    </script>

</body>
</html>
