<?php
session_start();
require "config/koneksi.php";

$error = "";
$activeRole = isset($_POST['role']) ? $_POST['role'] : 'user';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $role = $_POST['role'];
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    if ($role == 'admin') {
        $query = mysqli_query($koneksi, "SELECT * FROM admin WHERE username = '$username'");
        $akun = mysqli_fetch_assoc($query);

        if ($akun && $akun['password'] == $password) {
            $_SESSION['admin_id'] = $akun['id'];
            $_SESSION['admin_username'] = $akun['username'];
            header("Location: admin/dashboard.php");
            exit;
        } else {
            $error = "Username atau password admin salah.";
        }
    } else {
        $query = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$username'");
        $akun = mysqli_fetch_assoc($query);

        if ($akun && $akun['password'] == $password) {
            $_SESSION['user_id'] = $akun['id'];
            $_SESSION['user_username'] = $akun['username'];
            header("Location: index.php");
            exit;
        } else {
            $error = "Username atau password salah.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Furniturery Store</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="login-body">

    <div class="login-wrapper">
        <div class="login-card">

            <div class="login-logo">
                <a href="index.php">Furniturery<span>Store</span></a>
                <p>Masuk ke Akun</p>
            </div>

            <?php if ($error): ?>
                <p style="color: #c0392b; font-size: 14px; margin-bottom: 16px;"><?php echo $error; ?></p>
            <?php endif; ?>

            <div class="login-tabs">
                <button type="button" class="login-tab <?php echo $activeRole == 'user' ? 'active' : ''; ?>" onclick="switchTab('user', this)">Login User</button>
                <button type="button" class="login-tab <?php echo $activeRole == 'admin' ? 'active' : ''; ?>" onclick="switchTab('admin', this)">Login Admin</button>
            </div>

            <form action="login.php" method="POST" class="login-form" id="form-user" style="<?php echo $activeRole == 'admin' ? 'display:none;' : ''; ?>">
                <input type="hidden" name="role" value="user">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" placeholder="Masukkan username" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Masukkan password" required>
                </div>
                <button type="submit" class="btn-primary login-btn">Masuk</button>
                <p class="login-register-link">Belum punya akun? <a href="daftar.php">Daftar di sini</a></p>
            </form>

            <form action="login.php" method="POST" class="login-form" id="form-admin" style="<?php echo $activeRole == 'admin' ? '' : 'display:none;'; ?>">
                <input type="hidden" name="role" value="admin">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" placeholder="Masukkan username admin" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Masukkan password" required>
                </div>
                <button type="submit" class="btn-primary login-btn">Masuk</button>
            </form>

        </div>
    </div>

    <script>
        function switchTab(role, element) {
            document.getElementById('form-user').style.display = role === 'user' ? 'block' : 'none';
            document.getElementById('form-admin').style.display = role === 'admin' ? 'block' : 'none';
            document.querySelectorAll('.login-tab').forEach(btn => btn.classList.remove('active'));
            if(element) element.classList.add('active');
        }
    </script>

</body>

</html>