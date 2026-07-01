<?php
session_start();
require "config/koneksi.php";

$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $pesan = mysqli_real_escape_string($koneksi, $_POST['pesan']);
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "NULL";

    mysqli_query($koneksi, "INSERT INTO pesan_kontak (nama, email, pesan, tanggal, user_id, ditangani_oleh) VALUES ('$nama', '$email', '$pesan', NOW(), $user_id, NULL)");
    $success = "Pesan Anda berhasil dikirim. Kami akan segera menghubungi Anda.";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak - Furniturery Store</title>
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
            <h1>Contact Service</h1>
            <p>Ada permasalahan dengan produk? Silahkan hubungi kami segera.</p>
        </div>
    </section>
        <section class="contact-content">
        <div class="container contact-inner">

            <div class="contact-info">
                <h2>Informasi Kontak</h2>
                <div class="contact-item">
                    <strong>Alamat</strong>
                    <p>Jl. Sudirman No. 45, Jakarta Pusat, DKI Jakarta</p>
                </div>
                <div class="contact-item">
                    <strong>Telepon</strong>
                    <p>0812-9973-6007</p>
                </div>
                <div class="contact-item">
                    <strong>Email</strong>
                    <p>info@furniturerystore.com</p>
                </div>
                <div class="contact-item">
                    <strong>Jam Operasional</strong>
                    <p>Senin - Sabtu, 09.00 - 18.00 WIB</p>
                </div>
            </div>

            <div class="contact-form">
                <h2>Kirim Pesan</h2>
                <?php if ($success): ?>
                    <p style="color: #2e7d32; font-size: 14px; margin-bottom: 16px;"><?php echo $success; ?></p>
                <?php endif; ?>
                <form action="kontak.php" method="POST">
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" id="nama" name="nama" placeholder="Masukkan nama Anda" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Masukkan email Anda" required>
                    </div>
                    <div class="form-group">
                        <label for="pesan">Pesan</label>
                        <textarea id="pesan" name="pesan" rows="5" placeholder="Tulis pesan Anda di sini" required></textarea>
                    </div>
                    <button type="submit" class="btn-primary">Kirim Pesan</button>
                </form>
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