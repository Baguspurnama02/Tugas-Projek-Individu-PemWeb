<?php
session_start();
require "config/koneksi.php";
$query = mysqli_query($koneksi, "SELECT * FROM produk ORDER BY id ASC LIMIT 4");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Furniturery Store</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <header class="navbar">
        <div class="container navbar-inner">
            <div class="navbar-left">
                <button class="hamburger-btn" onclick="document.getElementById('mainMenu').classList.toggle('open')">&#9776;</button>
                <a href="index.php" class="logo">Furniturery<span>Store</span></a>

                <nav id="mainMenu" class="admin-dropdown">
                    <?php if (isset($_SESSION['admin_id'])): ?>
                        <a href="admin/dashboard.php">Dashboard Admin</a>
                        <a href="admin/produk.php">Kelola Produk</a>
                        <a href="admin/pesan.php">Pesan</a>
                        <a href="logout.php">Logout</a>
                    <?php else: ?>
                        <a href="index.php">Home</a>
                        <a href="katalog_produk.php">Katalog</a>
                        <a href="tentang.php">Tentang</a>
                        <a href="kontak.php">Kontak</a>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="keranjang.php">Keranjang</a>
                            <a href="riwayat_pesanan.php">Riwayat Pesanan</a>
                            <a href="logout.php">Logout</a>
                        <?php else: ?>
                            <a href="login.php">Login</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </nav>
            </div>

            <?php if (isset($_SESSION['admin_id'])): ?>
                <div class="admin-info">
                    <span class="admin-icon">&#128100;</span>
                    <span class="admin-name"><?php echo $_SESSION['admin_username']; ?></span>
                </div>
            <?php else: ?>
                <div class="admin-info">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="keranjang.php" class="keranjang-icon">&#128722;</a>
                    <?php endif; ?>
                    <span class="admin-icon">&#128100;</span>
                    <span class="admin-name"><?php echo isset($_SESSION['user_id']) ? $_SESSION['user_username'] : 'Tamu'; ?></span>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <section class="hero">
        <div class="container hero-inner">
            <div class="hero-text">
                <h1>Hadirkan Kenyamanan ke Dalam Rumahmu</h1>
                <p>Temukan beragam furniture berkualitas dengan desain modern dan harga terbaik, hanya di Furniturery Store.</p>
                <a href="katalog_produk.php" class="btn-primary">Lihat Katalog</a>
            </div>
            <div class="hero-image">
                <img src="assets/images/hero-furniture.jpg" alt="Furniture Hero">
            </div>
        </div>
    </section>

    <section class="categories">
        <div class="container">
            <h2 class="section-title">Kategori Pilihan</h2>
            <div class="category-grid">
                <div class="category-card">
                    <img src="assets/images/kategori-sofa.jpg" alt="Sofa">
                    <h3>Sofa & Kursi</h3>
                </div>
                <div class="category-card">
                    <img src="assets/images/kategori-meja.jpg" alt="Meja">
                    <h3>Meja</h3>
                </div>
                <div class="category-card">
                    <img src="assets/images/kategori-lemari.jpg" alt="Lemari">
                    <h3>Lemari</h3>
                </div>
                <div class="category-card">
                    <img src="assets/images/kategori-kamar.jpg" alt="Kamar Tidur">
                    <h3>Set Kamar Tidur</h3>
                </div>
            </div>
        </div>
    </section>

    <section class="featured-products">
        <div class="container">
            <h2 class="section-title">Produk Unggulan</h2>
            <div class="product-grid">

                <?php while ($row = mysqli_fetch_assoc($query)): ?>
                <div class="product-card">
                    <img src="assets/images/<?php echo $row['gambar']; ?>" alt="<?php echo $row['nama']; ?>">
                    <div class="product-info">
                        <h3><?php echo $row['nama']; ?></h3>
                        <p class="price">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                        <a href="detail_produk.php?id=<?php echo $row['id']; ?>" class="btn-secondary">Lihat Detail</a>
                    </div>
                </div>
                <?php endwhile; ?>

            </div>
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