<?php
include 'koneksi.php';
session_start();
if ($_SESSION['role'] !== 'pembeli') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_buku = $_POST['id_buku'];
    $jumlah = $_POST['jumlah'];

    $buku_query = "SELECT * FROM buku where id_buku = '$id_buku'";
    $buku_result = mysqli_query($koneksi, $buku_query);
    $buku  = mysqli_fetch_assoc($buku_result);

    if ($buku) {
        //hitung total harga
        $total_harga = $jumlah * $buku['harga_buku'];

        //kurangi stok buku
        $stok_baru = $buku['stok_buku'] - $jumlah;
        mysqli_query($koneksi, "UPDATE buku SET stok_buku = '$stok_baru' where id_buku = '$id_buku'");

        //simpan transaksi (pastikan $_session['id_pengguna'] ada)
        if (isset($_SESSION['user_id'])) {
            $id_pengguna = $_SESSION['user_id'];
            mysqli_query($koneksi, "INSERT INTO transaksi (id_pengguna, id_buku, jumlah, total_harga) VALUES ('$id_pengguna', '$id_buku', '$jumlah', '$total_harga')");
            echo "pembelian berhasil";
        } else {
            echo "ID pengguna tidak ditemukan!";
        }
    } else {
        echo "Buku tidak ditemukan!";
    }
}

//ambil daftar buku
$buku = mysqli_query($koneksi, "SELECT * FROM buku");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TOKO BUKU | PEMBELI</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>

<body>

    <div class="container">
        <form action="logout.php" method="post" class="mt-3">
            <button type="submit" class="btn btn-danger" name="logout"><-- Logout</button>
        </form>
        <h2 class="mt-3 mb-3">Daftar Buku</h2>
        <table class="table mt-3">
            <thead>
                <tr>
                    <td>ID Buku</td>
                    <td>Judul</td>
                    <td>Penulis</td>
                    <td>Penerbit</td>
                    <td>Stok</td>
                    <td>Harga</td>
                    <td>Aksi</td>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($buku)) : ?>
                    <td><?= $row['id_buku'] ?></td>
                    <td><?= $row['judul_buku'] ?></td>
                    <td><?= $row['penulis'] ?></td>
                    <td><?= $row['penerbit'] ?></td>
                    <td><?= $row['stok_buku'] ?></td>
                    <td>Rp.<?= number_format($row['harga_buku'], 2) ?></td>
                    <td>
                        <form action="" method="post">
                            <input type="hidden" name="id_buku" value="<?= $row['id_buku'] ?>">
                            <input type="number" name="jumlah" id="jumlah" value="1" min="1" max="<?= $row['stok_buku'] ?>">
                            <button type="submit" class="btn btn-primary">BELI</button>
                        </form>
                    </td>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>