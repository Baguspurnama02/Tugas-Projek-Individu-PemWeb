<?php
session_start();
require "config/koneksi.php";

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    $cek = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$username'");

    if (mysqli_num_rows($cek) > 0) {
        $error = "Username sudah digunakan, silakan pilih username lain.";
    } else {
        mysqli_query($koneksi, "INSERT INTO user (username, password) VALUES ('$username', '$password')");
        $success = "Pendaftaran berhasil! Silakan login.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Furniturery Store</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="login-body">

    <div class="login-wrapper">
        <div class="login-card">

            <div class="login-logo">
                <a href="index.php">Furniturery<span>Store</span></a>
                <p>Daftar Akun Baru</p>
            </div>

            <?php if ($error): ?>
                <p style="color: #c0392b; font-size: 14px; margin-bottom: 16px;"><?php echo $error; ?></p>
            <?php endif; ?>

            <?php if ($success): ?>
                <p style="color: #2e7d32; font-size: 14px; margin-bottom: 16px;"><?php echo $success; ?></p>
            <?php endif; ?>

            <form action="daftar.php" method="POST" class="login-form">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" placeholder="Buat username" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Buat password" required>
                </div>
                <button type="submit" class="btn-primary login-btn">Daftar</button>
                <p class="login-register-link">Sudah punya akun? <a href="login.php">Login di sini</a></p>
            </form>

        </div>
    </div>

</body>

</html>