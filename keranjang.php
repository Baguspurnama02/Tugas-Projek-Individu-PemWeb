<?php
session_start();
require "config/koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['tambah'])) {
    $produk_id = (int)$_GET['tambah'];
    $cek = mysqli_query($koneksi, "SELECT * FROM keranjang WHERE user_id = $user_id AND produk_id = $produk_id");
    if (mysqli_num_rows($cek) > 0) {
        mysqli_query($koneksi, "UPDATE keranjang SET jumlah = jumlah + 1 WHERE user_id = $user_id AND produk_id = $produk_id");
    } else {
        mysqli_query($koneksi, "INSERT INTO keranjang (user_id, produk_id, jumlah, tanggal) VALUES ($user_id, $produk_id, 1, NOW())");
    }
    header("Location: keranjang.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id_hapus = (int)$_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM keranjang WHERE id = $id_hapus AND user_id = $user_id");
    header("Location: keranjang.php");
    exit;
}

if (isset($_GET['kurang'])) {
    $id_kurang = (int)$_GET['kurang'];
    $cek = mysqli_query($koneksi, "SELECT * FROM keranjang WHERE id = $id_kurang AND user_id = $user_id");
    $row_cek = mysqli_fetch_assoc($cek);
    if ($row_cek && $row_cek['jumlah'] > 1) {
        mysqli_query($koneksi, "UPDATE keranjang SET jumlah = jumlah - 1 WHERE id = $id_kurang AND user_id = $user_id");
    } else {
        mysqli_query($koneksi, "DELETE FROM keranjang WHERE id = $id_kurang AND user_id = $user_id");
    }
    header("Location: keranjang.php");
    exit;
}

$query = mysqli_query($koneksi, "SELECT k.*, p.nama, p.harga, p.gambar FROM keranjang k LEFT JOIN produk p ON k.produk_id = p.id WHERE k.user_id = $user_id ORDER BY k.tanggal DESC");

$total = 0;
$items = [];
while ($row = mysqli_fetch_assoc($query)) {
    $subtotal = $row['harga'] * $row['jumlah'];
    $row['subtotal'] = $subtotal;
    $total += $subtotal;
    $items[] = $row;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang - Furniturery Store</title>
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

    <section class="page-header">
        <div class="container">
            <h1>Keranjang Belanja</h1>
            <p>Daftar produk yang ingin kamu pesan</p>
        </div>
    </section>

    <section class="keranjang-section">
        <div class="container">
            <?php if (empty($items)): ?>
                <p style="text-align:center; color:#5a5a5a; padding: 40px 0;">Keranjang kamu masih kosong. <a href="katalog_produk.php" style="color:#b07d4f;">Lihat Katalog</a></p>
            <?php else: ?>
                <div class="keranjang-list">
                    <?php foreach ($items as $item): ?>
                    <div class="keranjang-item">
                        <img src="assets/images/<?php echo $item['gambar']; ?>" alt="<?php echo $item['nama']; ?>">
                        <div class="keranjang-info">
                            <h3><?php echo $item['nama']; ?></h3>
                            <p class="price">Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></p>
                        </div>
                        <div class="keranjang-qty">
                            <a href="keranjang.php?kurang=<?php echo $item['id']; ?>" class="qty-btn">-</a>
                            <span><?php echo $item['jumlah']; ?></span>
                            <a href="keranjang.php?tambah=<?php echo $item['produk_id']; ?>" class="qty-btn">+</a>
                        </div>
                        <div class="keranjang-subtotal">
                            <p>Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></p>
                            <a href="keranjang.php?hapus=<?php echo $item['id']; ?>" class="hapus-keranjang">Hapus</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="keranjang-total">
                    <h3>Total: <span>Rp <?php echo number_format($total, 0, ',', '.'); ?></span></h3>
                    <a href="checkout.php" class="btn-primary">Lanjut Ke Checkout</a>
                </div>
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