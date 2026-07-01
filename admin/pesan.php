<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}
require "../config/koneksi.php";

if (isset($_GET['hapus'])) {
    $id_hapus = (int)$_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM pesan_kontak WHERE id = $id_hapus");
    header("Location: pesan.php");
    exit;
}

$query = mysqli_query($koneksi, "SELECT pk.*, u.username AS nama_user FROM pesan_kontak pk LEFT JOIN user u ON pk.user_id = u.id ORDER BY pk.tanggal DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Masuk - Furniturery Store</title>
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
            <h1>Pesan Masuk</h1>
            <p>Daftar pesan dari pelanggan melalui form kontak</p>
        </div>
    </section>

    <section class="pesan-section">
        <div class="container">
            <?php if (mysqli_num_rows($query) == 0): ?>
                <p style="text-align:center; color:#5a5a5a; padding: 40px 0;">Belum ada pesan masuk.</p>
            <?php else: ?>
                <table class="pesan-table">
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Pesan</th>
                        <th>Tanggal</th>
                        <th>Pengirim</th>
                        <th>Aksi</th>
                    </tr>
                    <?php $no = 0; while ($row = mysqli_fetch_assoc($query)): $no++; ?>
                    <tr class="pesan-row">
                        <td onclick="document.getElementById('modal-<?php echo $no; ?>').classList.add('open')"><?php echo $row['nama']; ?></td>
                        <td onclick="document.getElementById('modal-<?php echo $no; ?>').classList.add('open')"><?php echo $row['email']; ?></td>
                        <td onclick="document.getElementById('modal-<?php echo $no; ?>').classList.add('open')"><?php echo strlen($row['pesan']) > 100 ? substr($row['pesan'], 0, 100) . '...' : $row['pesan']; ?></td>
                        <td onclick="document.getElementById('modal-<?php echo $no; ?>').classList.add('open')"><?php echo date('d M Y, H:i', strtotime($row['tanggal'])); ?></td>
                        <td onclick="document.getElementById('modal-<?php echo $no; ?>').classList.add('open')"><?php echo $row['nama_user'] ? $row['nama_user'] : 'Tamu'; ?></td>
                        <td><button type="button" class="btn-hapus-pesan" onclick="confirmHapus(<?php echo $row['id']; ?>)">Hapus</button></td>
                    </tr>

                    <div id="modal-<?php echo $no; ?>" class="pesan-modal-overlay">
                        <div class="pesan-modal">
                            <button class="pesan-modal-close" onclick="document.getElementById('modal-<?php echo $no; ?>').classList.remove('open')">&times;</button>
                            <h3><?php echo $row['nama']; ?></h3>
                            <p class="pesan-modal-email"><?php echo $row['email']; ?></p>
                            <p class="pesan-modal-text"><?php echo $row['pesan']; ?></p>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </table>
            <?php endif; ?>
        </div>
    </section>

    <div id="modalKonfirmasi" class="pesan-modal-overlay">
        <div class="pesan-modal pesan-modal-confirm">
            <h3>Hapus Pesan?</h3>
            <p class="pesan-modal-text">Pesan ini akan dihapus secara permanen dan tidak dapat dikembalikan.</p>
            <div class="confirm-actions">
                <button type="button" class="btn-secondary" onclick="document.getElementById('modalKonfirmasi').classList.remove('open')">Batal</button>
                <a id="linkHapus" href="#" class="btn-hapus-confirm">Ya, Hapus</a>
            </div>
        </div>
    </div>

    <script>
        function confirmHapus(id) {
            document.getElementById('linkHapus').href = 'pesan.php?hapus=' + id;
            document.getElementById('modalKonfirmasi').classList.add('open');
        }
    </script>

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