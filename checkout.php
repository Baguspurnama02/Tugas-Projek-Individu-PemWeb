<?php
session_start();
require "config/koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil isi keranjang user
$query = mysqli_query($koneksi, "SELECT k.*, p.nama, p.harga, p.gambar FROM keranjang k LEFT JOIN produk p ON k.produk_id = p.id WHERE k.user_id = $user_id ORDER BY k.tanggal DESC");

$total = 0;
$items = [];
while ($row = mysqli_fetch_assoc($query)) {
    $subtotal = $row['harga'] * $row['jumlah'];
    $row['subtotal'] = $subtotal;
    $total += $subtotal;
    $items[] = $row;
}

// Kalau keranjang kosong, gak boleh checkout
if (empty($items)) {
    header("Location: keranjang.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_penerima = mysqli_real_escape_string($koneksi, trim($_POST['nama_penerima']));
    $no_hp = mysqli_real_escape_string($koneksi, trim($_POST['no_hp']));
    $alamat = mysqli_real_escape_string($koneksi, trim($_POST['alamat']));
    $metode_bayar = mysqli_real_escape_string($koneksi, $_POST['metode_bayar']);
    $catatan = mysqli_real_escape_string($koneksi, trim($_POST['catatan']));

    if ($nama_penerima === '' || $no_hp === '' || $alamat === '') {
        $error = "Nama, No. HP, dan Alamat wajib diisi.";
    } else {
        // Simpan ke tabel pesanan
        mysqli_query($koneksi, "INSERT INTO pesanan (user_id, nama_penerima, no_hp, alamat, metode_bayar, catatan, total, status, tanggal)
            VALUES ($user_id, '$nama_penerima', '$no_hp', '$alamat', '$metode_bayar', '$catatan', $total, 'Menunggu Konfirmasi', NOW())");

        $pesanan_id = mysqli_insert_id($koneksi);

        // Simpan detail produk
        foreach ($items as $item) {
            $produk_id = (int)$item['produk_id'];
            $nama_produk = mysqli_real_escape_string($koneksi, $item['nama']);
            $harga = (int)$item['harga'];
            $jumlah = (int)$item['jumlah'];
            $subtotal = (int)$item['subtotal'];

            mysqli_query($koneksi, "INSERT INTO pesanan_detail (pesanan_id, produk_id, nama_produk, harga, jumlah, subtotal)
                VALUES ($pesanan_id, $produk_id, '$nama_produk', $harga, $jumlah, $subtotal)");
        }

        // Kosongkan keranjang user
        mysqli_query($koneksi, "DELETE FROM keranjang WHERE user_id = $user_id");

        header("Location: checkout_sukses.php?id=" . $pesanan_id);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Furniturery Store</title>
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
            <h1>Checkout</h1>
            <p>Lengkapi data pengiriman untuk menyelesaikan pesanan</p>
        </div>
    </section>

    <section class="checkout-section">
        <div class="container checkout-grid">

            <div class="checkout-form-wrap">
                <?php if ($error): ?>
                    <p class="checkout-error"><?php echo $error; ?></p>
                <?php endif; ?>

                <form method="POST" class="checkout-form">
                    <label for="nama_penerima">Nama Penerima</label>
                    <input type="text" id="nama_penerima" name="nama_penerima" value="<?php echo isset($_POST['nama_penerima']) ? htmlspecialchars($_POST['nama_penerima']) : ''; ?>" required>

                    <label for="no_hp">No. HP</label>
                    <input type="text" id="no_hp" name="no_hp" value="<?php echo isset($_POST['no_hp']) ? htmlspecialchars($_POST['no_hp']) : ''; ?>" required>

                    <label for="alamat">Alamat Lengkap</label>
                    <textarea id="alamat" name="alamat" rows="3" required><?php echo isset($_POST['alamat']) ? htmlspecialchars($_POST['alamat']) : ''; ?></textarea>

                    <label for="metode_bayar">Metode Pembayaran</label>
                    <select id="metode_bayar" name="metode_bayar" required>
                        <option value="Transfer Bank">Transfer Bank</option>
                        <option value="COD">COD (Bayar di Tempat)</option>
                    </select>

                    <label for="catatan">Catatan (opsional)</label>
                    <textarea id="catatan" name="catatan" rows="2"><?php echo isset($_POST['catatan']) ? htmlspecialchars($_POST['catatan']) : ''; ?></textarea>

                    <button type="submit" class="btn-primary checkout-submit">Buat Pesanan</button>
                </form>
            </div>

            <div class="checkout-summary">
                <h3>Ringkasan Pesanan</h3>
                <?php foreach ($items as $item): ?>
                    <div class="checkout-summary-item">
                        <img src="assets/images/<?php echo $item['gambar']; ?>" alt="<?php echo $item['nama']; ?>">
                        <div class="checkout-summary-info">
                            <p class="checkout-summary-name"><?php echo $item['nama']; ?></p>
                            <p class="checkout-summary-qty"><?php echo $item['jumlah']; ?> x Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></p>
                        </div>
                        <p class="checkout-summary-subtotal">Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></p>
                    </div>
                <?php endforeach; ?>
                <div class="checkout-summary-total">
                    <span>Total</span>
                    <span>Rp <?php echo number_format($total, 0, ',', '.'); ?></span>
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