<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Furniturery Store</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <header class="navbar">
        <div class="container navbar-inner">
            <div class="navbar-left">
                <button class="hamburger-btn" onclick="document.getElementById('adminMenu').classList.toggle('open')">&#9776;</button>
                <a href="../index.php" class="logo">Furniturery<span>Store</span></a>

                <nav id="adminMenu" class="admin-dropdown">
                    <a href="dashboard.php">Dashboard</a>
                    <a href="produk.php">Kelola Produk</a>
                    <a href="pesan.php">Pesan</a>
                    <a href="pesanan.php">Pesanan</a>
                    <a href="../logout.php">Logout</a>
                </nav>
            </div>

            <div class="admin-info">
                <span class="admin-icon">&#128100;</span>
                <span class="admin-name"><?php echo $_SESSION['admin_username']; ?></span>
            </div>
        </div>
    </header>

    <section class="page-header">
        <div class="container">
            <h1>Dashboard Admin</h1>
            <p>Selamat datang, <?php echo $_SESSION['admin_username']; ?></p>
        </div>
    </section>

    <section class="dashboard-menu">
        <div class="container">
            <div class="dashboard-grid">
                <a href="produk.php" class="dashboard-card">
                    <h3>Kelola Produk</h3>
                    <p>Tambah, edit, atau hapus produk furniture</p>
                </a>
                <a href="pesan.php" class="dashboard-card">
                    <h3>Pesan Masuk</h3>
                    <p>Lihat pesan dari pelanggan melalui form kontak</p>
                </a>
                <a href="pesanan.php" class="dashboard-card">
                    <h3>Pesanan Masuk</h3>
                    <p>Lihat dan kelola pesanan dari pelanggan</p>
                </a>
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
            var menu = document.getElementById('adminMenu');
            var btn = document.querySelector('.hamburger-btn');
            if (menu && !menu.contains(e.target) && !btn.contains(e.target)) {
                menu.classList.remove('open');
            }
        });
    </script>

</body>
</html>