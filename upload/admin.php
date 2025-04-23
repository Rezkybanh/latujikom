<?php
include 'koneksi.php';
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

if (isset($_POST['tambah_buku'])) {
    $judul_buku = $_POST['judul_buku'];
    $penulis = $_POST['penulis'];
    $penerbit = $_POST['penerbit'];
    $stok_buku = $_POST['stok_buku'];
    $harga_buku = $_POST['harga_buku'];

    $query = "INSERT INTO buku (judul_buku, penulis, penerbit, stok_buku, harga_buku) VALUES ('$judul_buku', '$penulis', '$penerbit', '$stok_buku', '$harga_buku')";
    mysqli_query($koneksi, $query);
    header("Location: admin.php");
}

if (isset($_POST['edit_buku'])) {
    $id_buku = $_POST['id_buku'];
    $judul_buku = $_POST['judul_buku'];
    $penulis = $_POST['penulis'];
    $penerbit = $_POST['penerbit'];
    $stok_buku = $_POST['stok_buku'];
    $harga_buku = $_POST['harga_buku'];

    $query = "UPDATE buku set judul_buku = '$judul_buku', penulis = '$penulis', penerbit = '$penerbit', stok_buku = '$stok_buku', harga_buku = '$harga_buku' WHERE id_buku = '$id_buku'";
    mysqli_query($koneksi, $query);
    header("Location: admin.php");
}

if (isset($_GET['hapus_buku'])) {
    $id_buku = $_GET['hapus_buku'];
    $query = "DELETE FROM buku where id_buku = '$id_buku'";
    mysqli_query($koneksi, $query);
    header("Location: admin.php");
}

if (isset($_POST['tambah_pengguna'])) {
    $nama_pengguna = $_POST['nama_pengguna'];
    $kata_sandi = password_hash($_POST['kata_sandi'], PASSWORD_DEFAULT);
    $peran = $_POST['peran'];

    $query = "INSERT INTO pengguna (nama_pengguna,kata_sandi,peran) VALUES ('$nama_pengguna','$kata_sandi','$peran')";
    mysqli_query($koneksi, $query);
    header("Location: admin.php");
}

if (isset($_POST['edit_pengguna'])) {
    $id_pengguna = $_POST['id_pengguna'];
    $nama_pengguna = $_POST['nama_pengguna'];
    $peran = $_POST['peran'];

    $query = "UPDATE pengguna set nama_pengguna = '$nama_pengguna' , peran = '$peran' WHERE id_pengguna = '$id_pengguna'";
    mysqli_query($koneksi, $query);
    header("Location: admin.php");
}

if (isset($_GET['hapus_pengguna'])) {
    $id_pengguna = $_GET['hapus_pengguna'];
    $query = "DELETE FROM pengguna WHERE id_pengguna = '$id_pengguna'";
    mysqli_query($koneksi, $query);
    header("Location: admin.php");
}

$buku = mysqli_query($koneksi, "SELECT * FROM buku");
$pengguna = mysqli_query($koneksi, "SELECT * FROM pengguna");
$transaksi = mysqli_query($koneksi, "SELECT transaksi.*, pengguna.nama_pengguna, buku.judul_buku from transaksi join pengguna on transaksi.id_pengguna = pengguna.id_pengguna join buku on transaksi.id_buku = buku.id_buku");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TOKO BUKU | ADMIN</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>

<body>

    <div class="container">
        <form action="logout.php" method="post" class="mt-3">
            <button type="submit" class="btn btn-danger" name="logout"><-- Logout</button>
        </form>
        <h2 class="mt-3">DATA BUKU</h2>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tambahBukuModal">Tambah Buku</button>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>JUDUL</th>
                    <th>PENULIS</th>
                    <th>PENERBIT</th>
                    <th>STOK</th>
                    <th>HARGA</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($buku)): ?>
                    <tr>
                        <td><?= $row['id_buku'] ?? 'Data tidak ditemukan!' ?></td>
                        <td><?= $row['judul_buku'] ?? 'Data tidak ditemukan!' ?></td>
                        <td><?= $row['penulis'] ?? 'Data tidak ditemukan!' ?></td>
                        <td><?= $row['penerbit'] ?? 'Data tidak ditemukan!' ?></td>
                        <td><?= $row['stok_buku'] ?? 'Data tidak ditemukan!' ?></td>
                        <td>Rp.<?= number_format($row['harga_buku'], 2)  ?></td>
                        <td>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editBukuModal<?= $row['id_buku'] ?>">Edit</button>
                            <a href="admin.php?hapus_buku=<?= $row['id_buku'] ?>" class="btn btn-danger">Hapus Buku</a>
                        </td>
                    </tr>

                    <div class="modal fade" id="editBukuModal<?= $row['id_buku'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="" method="post">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Edit Buku</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="id_buku" value="<?= $row['id_buku'] ?? 'Data tidak ditemukan' ?>">
                                        <div class="mb-3">
                                            <label for="judul_buku" class="form-label">Judul Buku</label>
                                            <input type="text" name="judul_buku" id="judul_buku" class="form-control" value="<?= $row['judul_buku'] ?? 'Data tidak ditemukan' ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="penulis" class="form-label">Penulis</label>
                                            <input type="text" name="penulis" id="penulis" class="form-control" value="<?= $row['penulis'] ?? 'Data tidak ditemukan' ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="penerbit" class="form-label">Penerbit</label>
                                            <input type="text" name="penerbit" id="penerbit" class="form-control" value="<?= $row['penerbit'] ?? 'Data tidak ditemukan' ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="stok_buku" class="form-label">Stok Buku</label>
                                            <input type="number" name="stok_buku" id="stok_buku" class="form-control" value="<?= $row['stok_buku'] ?? 'Data tidak ditemukan' ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="harga_buku" class="form-label">Harga Buku</label>
                                            <input type="number" name="harga_buku" id="harga_buku" class="form-control" value="<?= $row['harga_buku'] ?? 'Data tidak ditemukan' ?>">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary" name="edit_buku">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </tbody>
        </table>
        <!-- Modal Table Pengguna -->
        <div class="modal fade" id="tambahBukuModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="" method="post">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Tambah Buku</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="judul_buku" class="form-lable">Judul Buku</label>
                                <input type="text" name="judul_buku" id="judul_buku" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="penulis" class="form-lable">Penulis</label>
                                <input type="text" name="penulis" id="penulis" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="penerbit" class="form-lable">Penerbit</label>
                                <input type="text" name="penerbit" id="penerbit" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="stok_buku" class="form-lable">Stok Buku</label>
                                <input type="number" name="stok_buku" id="stok_buku" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="harga_buku" class="form-lable">Harga Buku</label>
                                <input type="number" name="harga_buku" id="harga_buku" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success" name="tambah_buku">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- DATA PENGGUNA -->
        <h2 class="mt-3">DATA PENGGUNA</h2>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tambahPenggunaModal">Tambah Pengguna</button>
        <table class="table mt-3 text-center">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NAMA PENGGUNA</th>
                    <th>PERAN</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($pengguna)): ?>
                    <tr>
                        <td><?= $row['id_pengguna'] ?? 'Data tidak ditemukan!' ?></td>
                        <td><?= $row['nama_pengguna'] ?? 'Data tidak ditemukan!' ?></td>
                        <td><?= $row['peran'] ?? 'Data tidak ditemukan!' ?></td>
                        <td>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editPenggunaModal<?= $row['id_pengguna'] ?>">Edit</button>
                            <a href="admin.php?hapus_pengguna=<?= $row['id_pengguna'] ?>" class="btn btn-danger">Hapus Pengguna</a>
                        </td>
                    </tr>

                    <div class="modal fade" id="editPenggunaModal<?= $row['id_pengguna'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="" method="post">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Edit Pengguna</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="id_pengguna" value="<?= $row['id_pengguna'] ?? 'Data tidak ditemukan' ?>">
                                        <div class="mb-3">
                                            <label for="nama_pengguna" class="form-label">Nama Pengguna</label>
                                            <input type="text" name="nama_pengguna" id="nama_pengguna" class="form-control" value="<?= $row['nama_pengguna'] ?? 'Data tidak ditemukan' ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="peran" class="form-label">Peran</label>
                                            <select name="peran" id="peran" class="form-control">
                                                <option value="admin" <?= ($row['peran'] ?? '') == 'admin' ? 'selected' : '' ?>>Admin</option>
                                                <option value="pemilik" <?= ($row['peran'] ?? '') == 'pemilik' ? 'selected' : '' ?>>Owner</option>
                                                <option value="pembeli" <?= ($row['peran'] ?? '') == 'pembeli' ? 'selected' : '' ?>>Pembeli</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary" name="edit_pengguna">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </tbody>
        </table>
        <!-- Modal Table Pengguna -->
        <div class="modal fade" id="tambahPenggunaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="" method="post">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Tambah Pengguna</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nama_pengguna" class="form-lable">Judul Buku</label>
                                <input type="text" name="nama_pengguna" id="nama_pengguna" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="kata_sandi" class="form-lable">Kata Sandi</label>
                                <input type="text" name="kata_sandi" id="kata_sandi" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="peran" class="form-label">Peran</label>
                                <select name="peran" id="peran" class="form-control">
                                    <option value="admin" <?= ($row['peran'] ?? '') == 'admin' ? 'selected' : '' ?>>Admin</option>
                                    <option value="pemilik" <?= ($row['peran'] ?? '') == 'pemilik' ? 'selected' : '' ?>>Owner</option>
                                    <option value="pembeli" <?= ($row['peran'] ?? '') == 'pembeli' ? 'selected' : '' ?>>Pembeli</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success" name="tambah_pengguna">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <h2>Data Transaksi</h2>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>ID TRANSAKSI</th>
                    <th>NAMA PEMBELI</th>
                    <th>JUDUL BUKU</th>
                    <th>JUMLAH</th>
                    <th>TOTAL HARGA</th>
                    <th>TANGGAL</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($transaksi)) : ?>
                    <tr>
                    <td><?= $row['id_transaksi'] ?></td>
                    <td><?= $row['nama_pengguna'] ?></td>
                    <td><?= $row['judul_buku'] ?></td>
                    <td><?= $row['jumlah'] ?></td>
                    <td><?= number_format($row['total_harga'], 2) ?></td>
                    <td><?= $row['tanggal_transaksi'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="laporan.php" class="btn btn-primary mb-5">>--Cetak Laporan--<</a>
    </div>
    <script src="../js/bootstrap.min.js"></script>
</body>

</html>