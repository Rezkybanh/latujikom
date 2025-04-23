<?php
include 'koneksi.php';
session_start();
if ($_SESSION['role'] !== 'pemilik') {
    header("Location: index.php");
    exit();
}

$buku = mysqli_query($koneksi, "SELECT * FROM buku");
$transaksi = mysqli_query($koneksi, "SELECT transaksi.*, pengguna.nama_pengguna, buku.judul_buku FROM transaksi JOIN pengguna ON transaksi.id_pengguna = pengguna.id_pengguna JOIN buku ON transaksi.id_buku = buku.id_buku");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TOKO BUKU | OWNER</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <form action="logout.php" method="post" class="mt-3">
            <button type="submit" class="btn btn-danger" name="logout"><-- Logout</button>
        </form>
        <h2 class="mt-3 mb-3">Laporan Stok Buku</h2>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Penerbit</th>
                    <th>Stok</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($buku)): ?>
                <tr>
                    <td><?= $row['id_buku'] ?></td>
                    <td><?= $row['judul_buku'] ?></td>
                    <td><?= $row['penulis'] ?></td>
                    <td><?= $row['penerbit'] ?></td>
                    <td><?= $row['stok_buku'] ?></td>
                    <td>Rp.<?= number_format($row['harga_buku'], 2) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <h2 class="mt-5 mb-3">Laporan Transaksi </h2>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>ID Transaksi</th>
                    <th>Nama Pembeli</th>
                    <th>Judul Buku</th>
                    <th>Jumlah</th>
                    <th>Total Harga</th>
                    <th>Tanggal Transaksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($transaksi)): ?>
                <tr>
                    <td><?= $row['id_transaksi'] ?></td>
                    <td><?= $row['nama_pengguna'] ?></td>
                    <td><?= $row['judul_buku'] ?></td>
                    <td><?= $row['jumlah'] ?></td>
                    <td>Rp.<?= number_format($row['total_harga'],2) ?></td>
                    <td> <?= $row['tanggal_transaksi'] ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>