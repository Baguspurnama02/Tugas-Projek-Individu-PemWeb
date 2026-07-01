<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}
require "../config/koneksi.php";

// Update status pesanan
if (isset($_POST['update_status'])) {
    $id_pesanan = (int)$_POST['pesanan_id'];
    $status_baru = mysqli_real_escape_string($koneksi, $_POST['status']);
    mysqli_query($koneksi, "UPDATE pesanan SET status = '$status_baru' WHERE id = $id_pesanan");
    header("Location: pesanan.php");
    exit;
}

// Hapus pesanan
if (isset($_GET['hapus'])) {
    $id_hapus = (int)$_GET['hapus'];
    
    // Hapus detail pesanan terlebih dahulu agar tidak jadi sampah
    mysqli_query($koneksi, "DELETE FROM pesanan_detail WHERE pesanan_id = $id_hapus");
    
    // Hapus data pesanan utama
    mysqli_query($koneksi, "DELETE FROM pesanan WHERE id = $id_hapus");
    
    header("Location: pesanan.php");
    exit;
}

$query = mysqli_query($koneksi, "SELECT p.*, u.username AS nama_user FROM pesanan p LEFT JOIN user u ON p.user_id = u.id ORDER BY p.tanggal DESC");
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
    <title>Pesanan Masuk - Furniturery Store</title>
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
            <h1>Pesanan Masuk</h1>
            <p>Daftar pesanan produk dari pelanggan</p>
        </div>
    </section>

    <section class="pesanan-section">
        <div class="container">
            <?php if (empty($pesanan_list)): ?>
                <p style="text-align:center; color:#5a5a5a; padding: 40px 0;">Belum ada pesanan masuk.</p>
            <?php else: ?>
                <?php foreach ($pesanan_list as $p): ?>
                <div class="pesanan-card">
                    <div class="pesanan-card-header">
                        <div>
                            <h3>#<?php echo $p['id']; ?> — <?php echo htmlspecialchars($p['nama_penerima']); ?></h3>
                            <p class="pesanan-meta">
                                Akun: <?php echo $p['nama_user'] ? htmlspecialchars($p['nama_user']) : 'Tamu'; ?>
                                &bull; <?php echo date('d M Y, H:i', strtotime($p['tanggal'])); ?>
                            </p>
                        </div>
                        <span class="pesanan-status status-<?php echo strtolower(str_replace(' ', '-', $p['status'])); ?>">
                            <?php echo $p['status']; ?>
                        </span>
                    </div>

                    <div class="pesanan-card-body">
                        <div class="pesanan-info-grid">
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

                            <div class="pesanan-actions">
                                <form method="POST" class="pesanan-status-form">
                                    <input type="hidden" name="pesanan_id" value="<?php echo $p['id']; ?>">
                                    <select name="status">
                                        <?php
                                        $statuses = ['Menunggu Konfirmasi', 'Diproses', 'Dikirim', 'Selesai', 'Dibatalkan'];
                                        foreach ($statuses as $s) {
                                            $selected = ($s === $p['status']) ? 'selected' : '';
                                            echo "<option value=\"$s\" $selected>$s</option>";
                                        }
                                        ?>
                                    </select>
                                    <button type="submit" name="update_status" class="btn-secondary">Update Status</button>
                                </form>
                                <button type="button" class="btn-hapus-pesan" onclick="confirmHapus(<?php echo $p['id']; ?>)">Hapus</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <div id="modalKonfirmasi" class="pesan-modal-overlay">
        <div class="pesan-modal pesan-modal-confirm">
            <h3>Hapus Pesanan?</h3>
            <p class="pesan-modal-text">Pesanan ini akan dihapus secara permanen dan tidak dapat dikembalikan.</p>
            <div class="confirm-actions">
                <button type="button" class="btn-secondary" onclick="document.getElementById('modalKonfirmasi').classList.remove('open')">Batal</button>
                <a id="linkHapus" href="#" class="btn-hapus-confirm">Ya, Hapus</a>
            </div>
        </div>
    </div>

    <script>
        function confirmHapus(id) {
            document.getElementById('linkHapus').href = 'pesanan.php?hapus=' + id;
            document.getElementById('modalKonfirmasi').classList.add('open');
        }

        document.addEventListener('click', function(e) {
            var menu = document.getElementById('adminMenu');
            var btn = document.querySelector('.hamburger-btn');
            if (menu && !menu.contains(e.target) && !btn.contains(e.target)) {
                menu.classList.remove('open');
            }
        });
    </script>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 Furniturery Store. Semua hak dilindungi.</p>
        </div>
    </footer>

</body>
</html>