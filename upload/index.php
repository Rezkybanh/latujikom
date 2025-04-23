<?php
include 'koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_pengguna = $_POST['username'];
    $kata_sandi = $_POST['kata_sandi'];

    $query = "SELECT * FROM pengguna WHERE nama_pengguna = '$nama_pengguna'";
    $result = mysqli_query($koneksi, $query);
    $pengguna = mysqli_fetch_assoc($result);

    if ($pengguna && password_verify( $kata_sandi, $pengguna['kata_sandi'])) {
        $_SESSION['user_id'] = $pengguna['id_pengguna'];
        $_SESSION['role'] = $pengguna['peran'];

        if ($pengguna['peran'] === 'admin') {
            header("Location: admin.php");
        } elseif ($pengguna['peran'] === 'pemilik') {
            header("Location: owner.php");
        } else {
            header("Location: pembeli.php");
        }
        exit();
    }else {
        echo "Nama Pengguna atau Kata Sandi Salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN | TOKO BUKU</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2 class="text-center">LOGIN</h2>
        <form action="" method="post">
            <div class="mb-3">
                <label for="username" class="form-label ">USERNAME</label>
                <input type="text" name="username" id="username" required class="form-control">
            </div>
            <div class="mb-3">
                <label for="kata_sandi" class="form-label">PASSWORD</label>
                <input type="password" name="kata_sandi" id="kata_sandi" required class="form-control">
            </div>
            <button type="submit" class="btn btn-primary w-100">LOGIN</button>
        </form>
    </div>
</body>
</html>