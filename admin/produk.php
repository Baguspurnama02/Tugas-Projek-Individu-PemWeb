<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}
require "../config/koneksi.php";

if (isset($_POST['simpan_produk'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $kategori_id = (int)$_POST['kategori_id'];
    $harga = (int)$_POST['harga'];
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $material = mysqli_real_escape_string($koneksi, $_POST['material']);
    $dimensi = mysqli_real_escape_string($koneksi, $_POST['dimensi']);
    $warna = mysqli_real_escape_string($koneksi, $_POST['warna']);
    $stok = mysqli_real_escape_string($koneksi, $_POST['stok']);
    $admin_id = $_SESSION['admin_id'];

    $gambar = "default.jpg";
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $gambar = "produk_" . time() . "." . $ext;
        move_uploaded_file($_FILES['gambar']['tmp_name'], "../assets/images/" . $gambar);
    }

    mysqli_query($koneksi, "INSERT INTO produk (nama, kategori_id, dikelola_oleh, harga, deskripsi, gambar, material, dimensi, warna, stok) VALUES ('$nama', $kategori_id, $admin_id, $harga, '$deskripsi', '$gambar', '$material', '$dimensi', '$warna', '$stok')");
    header("Location: produk.php");
    exit;
}

if (isset($_POST['update_produk'])) {
    $id = (int)$_POST['id'];
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $kategori_id = (int)$_POST['kategori_id'];
    $harga = (int)$_POST['harga'];
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $material = mysqli_real_escape_string($koneksi, $_POST['material']);
    $dimensi = mysqli_real_escape_string($koneksi, $_POST['dimensi']);
    $warna = mysqli_real_escape_string($koneksi, $_POST['warna']);
    $stok = mysqli_real_escape_string($koneksi, $_POST['stok']);

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $gambar = "produk_" . time() . "." . $ext;
        move_uploaded_file($_FILES['gambar']['tmp_name'], "../assets/images/" . $gambar);
        mysqli_query($koneksi, "UPDATE produk SET nama='$nama', kategori_id=$kategori_id, harga=$harga, deskripsi='$deskripsi', material='$material', dimensi='$dimensi', warna='$warna', stok='$stok', gambar='$gambar' WHERE id=$id");
    } else {
        mysqli_query($koneksi, "UPDATE produk SET nama='$nama', kategori_id=$kategori_id, harga=$harga, deskripsi='$deskripsi', material='$material', dimensi='$dimensi', warna='$warna', stok='$stok' WHERE id=$id");
    }

    header("Location: produk.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id_hapus = (int)$_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM produk WHERE id = $id_hapus");
    header("Location: produk.php");
    exit;
}

$query = mysqli_query($koneksi, "SELECT p.*, k.nama_kategori FROM produk p LEFT JOIN kategori k ON p.kategori_id = k.id ORDER BY p.id ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk - Furniturery Store</title>
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
            <h1>Kelola Produk</h1>
            <p>Tambah, edit, atau hapus produk furniture</p>
            <button type="button" class="btn-primary" style="margin-top: 16px; border: none; cursor: pointer;" onclick="document.getElementById('modalTambah').classList.add('open')">+ Tambah Produk</button>
        </div>
    </section>

    <section class="pesan-section">
        <div class="container">
            <table class="pesan-table">
                <tr>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($query)): ?>
                <tr>
                    <td><?php echo $row['nama']; ?></td>
                    <td><?php echo $row['nama_kategori']; ?></td>
                    <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                    <td><?php echo $row['stok']; ?></td>
                    <td>
                        <button type="button" class="btn-edit-produk" onclick="bukaEdit(<?php echo htmlspecialchars(json_encode($row), ENT_QUOTES); ?>)">Edit</button>
                        <button type="button" class="btn-hapus-pesan" onclick="confirmHapus(<?php echo $row['id']; ?>)">Hapus</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </section>
        <div id="modalKonfirmasi" class="pesan-modal-overlay">
        <div class="pesan-modal pesan-modal-confirm">
            <h3>Hapus Produk?</h3>
            <p class="pesan-modal-text">Produk ini akan dihapus secara permanen dan tidak dapat dikembalikan.</p>
            <div class="confirm-actions">
                <button type="button" class="btn-secondary" onclick="document.getElementById('modalKonfirmasi').classList.remove('open')">Batal</button>
                <a id="linkHapus" href="#" class="btn-hapus-confirm">Ya, Hapus</a>
            </div>
        </div>
    </div>

    <script>
        function confirmHapus(id) {
            document.getElementById('linkHapus').href = 'produk.php?hapus=' + id;
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
        <div id="modalTambah" class="pesan-modal-overlay">
        <div class="pesan-modal form-modal">
            <button class="pesan-modal-close" onclick="document.getElementById('modalTambah').classList.remove('open')">&times;</button>
            <h3>Tambah Produk Baru</h3>

            <form action="produk.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Nama Produk</label>
                    <input type="text" name="nama" required>
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori_id" required>
                        <?php
                        $kategoriQuery = mysqli_query($koneksi, "SELECT * FROM kategori");
                        while ($kat = mysqli_fetch_assoc($kategoriQuery)):
                        ?>
                        <option value="<?php echo $kat['id']; ?>"><?php echo $kat['nama_kategori']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Harga (Rp)</label>
                    <input type="number" name="harga" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label>Material</label>
                    <input type="text" name="material" required>
                </div>
                <div class="form-group">
                    <label>Dimensi</label>
                    <input type="text" name="dimensi" required>
                </div>
                <div class="form-group">
                    <label>Warna</label>
                    <input type="text" name="warna" required>
                </div>
                <div class="form-group">
                    <label>Stok</label>
                    <input type="text" name="stok" required>
                </div>
                <div class="form-group">
                    <label>Foto Produk</label>
                    <input type="file" name="gambar" accept="image/*" required>
                </div>
                <button type="submit" name="simpan_produk" class="btn-primary" style="width:100%; border:none;">Simpan Produk</button>
            </form>
        </div>
    </div>

    <div id="modalEdit" class="pesan-modal-overlay">
        <div class="pesan-modal form-modal">
            <button class="pesan-modal-close" onclick="document.getElementById('modalEdit').classList.remove('open')">&times;</button>
            <h3>Edit Produk</h3>

            <form action="produk.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" id="edit-id">
                <div class="form-group">
                    <label>Nama Produk</label>
                    <input type="text" name="nama" id="edit-nama" required>
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori_id" id="edit-kategori_id" required>
                        <?php
                        $kategoriQuery2 = mysqli_query($koneksi, "SELECT * FROM kategori");
                        while ($kat = mysqli_fetch_assoc($kategoriQuery2)):
                        ?>
                        <option value="<?php echo $kat['id']; ?>"><?php echo $kat['nama_kategori']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Harga (Rp)</label>
                    <input type="number" name="harga" id="edit-harga" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" id="edit-deskripsi" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label>Material</label>
                    <input type="text" name="material" id="edit-material" required>
                </div>
                <div class="form-group">
                    <label>Dimensi</label>
                    <input type="text" name="dimensi" id="edit-dimensi" required>
                </div>
                <div class="form-group">
                    <label>Warna</label>
                    <input type="text" name="warna" id="edit-warna" required>
                </div>
                <div class="form-group">
                    <label>Stok</label>
                    <input type="text" name="stok" id="edit-stok" required>
                </div>
                <div class="form-group">
                    <label>Foto Produk (biarkan kosong jika tidak ingin mengganti)</label>
                    <input type="file" name="gambar" accept="image/*">
                </div>
                <button type="submit" name="update_produk" class="btn-primary" style="width:100%; border:none;">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    <script>
        function bukaEdit(data) {
            document.getElementById('edit-id').value = data.id;
            document.getElementById('edit-nama').value = data.nama;
            document.getElementById('edit-kategori_id').value = data.kategori_id;
            document.getElementById('edit-harga').value = data.harga;
            document.getElementById('edit-deskripsi').value = data.deskripsi;
            document.getElementById('edit-material').value = data.material;
            document.getElementById('edit-dimensi').value = data.dimensi;
            document.getElementById('edit-warna').value = data.warna;
            document.getElementById('edit-stok').value = data.stok;
            document.getElementById('modalEdit').classList.add('open');
        }
    </script>
    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 Furniturery Store. Semua hak dilindungi.</p>
        </div>
    </footer>

</body>
</html>