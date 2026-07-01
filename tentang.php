<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang - Furniturery Store</title>
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

            <div class="admin-info">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="keranjang.php" class="keranjang-icon">&#128722;</a>
                <?php endif; ?>
                <span class="admin-icon">&#128100;</span>
                <span class="admin-name"><?php echo isset($_SESSION['user_id']) ? $_SESSION['user_username'] : 'Tamu'; ?></span>
            </div>
        </div>
    </header>

    <section class="page-header">
        <div class="container">
            <h1>Tentang Furniturery Store</h1>
            <p>Mengenal lebih dekat toko furniture online kami</p>
        </div>
    </section>
        <section class="about-content">
        <div class="container about-inner">
            <div class="about-text">
                <h2>Tentang Kami</h2>
                <p>
                    Furniturery Store adalah toko furniture online yang hadir untuk memudahkan
                    pelanggan dalam menemukan dan memilih produk furniture berkualitas tanpa harus
                    datang langsung ke toko fisik.
                </p>
                <p>
                    Kami berkomitmen menghadirkan pengalaman belanja yang praktis, informatif, dan
                    nyaman bagi setiap pelanggan.
                </p>
            </div>
            <div class="about-image">
                <img src="assets/images/hero-furniture.jpg" alt="Tentang Furniturery Store">
            </div>
        </div>
    </section>

    <section class="visi-misi">
        <div class="container">
            <div class="vm-grid">
                <div class="vm-card">
                    <h3>Visi</h3>
                    <p>Menjadi platform toko furniture online terpercaya yang menghadirkan kemudahan belanja bagi setiap pelanggan.</p>
                </div>
                <div class="vm-card">
                    <h3>Misi</h3>
                    <p>Menyediakan katalog produk yang lengkap, memberikan pelayanan terbaik, serta terus berinovasi mengikuti kebutuhan pelanggan.</p>
                </div>
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