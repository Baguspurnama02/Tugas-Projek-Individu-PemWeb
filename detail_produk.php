<?php
session_start();
require "config/koneksi.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id === 0) {
    header("Location: katalog_produk.php");
    exit;
}

$query = mysqli_query($koneksi, "SELECT * FROM produk WHERE id = $id");
$p = mysqli_fetch_assoc($query);

if (!$p) {
    header("Location: katalog_produk.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $p['nama']; ?> - Furniturery Store</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <header class="navbar">
        <div class="container navbar-inner">
            <a href="index.php" class="logo">Furniturery<span>Store</span></a>
            <nav class="nav-links">
                <a href="index.php">Home</a>
                <a href="katalog_produk.php" class="active">Katalog</a>
                <a href="tentang.php">Tentang</a>
                <a href="kontak.php">Kontak</a>
            </nav>
        </div>
    </header>

    <section class="breadcrumb-section">
        <div class="container">
            <a href="index.php">Home</a> /
            <a href="katalog_produk.php">Katalog</a> /
            <span><?php echo $p['nama']; ?></span>
        </div>
    </section>

    <section class="detail-section">
        <div class="container detail-inner">

            <div class="detail-image">
                <img src="assets/images/<?php echo $p['gambar']; ?>" alt="<?php echo $p['nama']; ?>">
            </div>

            <div class="detail-info">
                <h1><?php echo $p['nama']; ?></h1>
                <p class="detail-price">Rp <?php echo number_format($p['harga'], 0, ',', '.'); ?></p>

                <p class="detail-desc"><?php echo $p['deskripsi']; ?></p>

                <table class="detail-spec">
                    <tr>
                        <td>Material</td>
                        <td><?php echo $p['material']; ?></td>
                    </tr>
                    <tr>
                        <td>Dimensi</td>
                        <td><?php echo $p['dimensi']; ?></td>
                    </tr>
                    <tr>
                        <td>Warna</td>
                        <td><?php echo $p['warna']; ?></td>
                    </tr>
                    <tr>
                        <td>Stok</td>
                        <td><?php echo $p['stok']; ?></td>
                    </tr>
                </table>

                <div class="detail-actions">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="keranjang.php?tambah=<?php echo $p['id']; ?>" class="btn-primary">+ Tambah ke Keranjang</a>
                    <?php else: ?>
                        <a href="login.php" class="btn-primary">Login untuk Tambah ke Keranjang</a>
                    <?php endif; ?>
                    <a href="katalog_produk.php" class="btn-secondary">Kembali ke Katalog</a>
                </div>
            </div>

        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 Furniturery Store. Semua hak dilindungi.</p>
        </div>
    </footer>

</body>
</html>