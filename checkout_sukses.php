<?php
session_start();
require "config/koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$pesanan_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$query = mysqli_query($koneksi, "SELECT * FROM pesanan WHERE id = $pesanan_id AND user_id = $user_id");
$pesanan = mysqli_fetch_assoc($query);

if (!$pesanan) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil - Furniturery Store</title>
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
                <span class="admin-icon">&#128100;</span>
                <span class="admin-name"><?php echo $_SESSION['user_username']; ?></span>
            </div>
        </div>
    </header>

    <section class="checkout-sukses-section">
        <div class="container checkout-sukses-box">
            <div class="checkout-sukses-icon">&#10003;</div>
            <h1>Pesanan Berhasil Dibuat!</h1>
            <p>Terima kasih, <?php echo htmlspecialchars($pesanan['nama_penerima']); ?>. Pesananmu sudah kami terima.</p>

            <div class="checkout-sukses-detail">
                <p><strong>No. Pesanan:</strong> #<?php echo $pesanan['id']; ?></p>
                <p><strong>Status:</strong> <?php echo $pesanan['status']; ?></p>
                <p><strong>Metode Bayar:</strong> <?php echo $pesanan['metode_bayar']; ?></p>
                <p><strong>Total:</strong> Rp <?php echo number_format($pesanan['total'], 0, ',', '.'); ?></p>
            </div>

            <p class="checkout-sukses-info">Admin akan segera menghubungimu untuk konfirmasi pembayaran dan pengiriman.</p>

            <a href="index.php" class="btn-primary">Kembali ke Beranda</a>
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