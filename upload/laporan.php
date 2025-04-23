<?php
include '../libraries/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

include 'koneksi.php';

//ambil data buku dan trasaksi
$buku = mysqli_query($koneksi, "SELECT * FROM buku");
$transaksi = mysqli_query($koneksi, "SELECT transaksi.*, pengguna.nama_pengguna, buku.judul_buku FROM transaksi JOIN  pengguna ON transaksi.id_pengguna = pengguna.id_pengguna JOIN buku ON transaksi.id_buku = buku.id_buku");

//inisialisasi DOMPDF
$dompdf = new Dompdf();

$html = '<h1>Laporan stok Buku</h1>';
$html .= '<table border="1" cellpadding="10" cellspacing="0">';
$html .= '<thead><tr><th>ID</th><th>Judul</th><th>Penulis</th><th>Penerbit</th><th>Stok</th><th>Harga</th></tr></thead><tbody>';

while ($row = mysqli_fetch_assoc($buku)) {
    $html .= '<tr><td>' .$row['id_buku']. '</td><td>' .$row['judul_buku']. '</td><td>' .$row['penulis']. '</td><td>' .$row['penerbit']. '</td><td>' .$row['stok_buku']. '</td><td>' .number_format($row['harga_buku'],2). '</td></tr>';
}

$html .= '</tbody></table>';

$html .= '<h2>Laporan Transaksi</h2>';
$html .= '<table border="1" cellpadding="10" cellspacing="0">';
$html .= '<thead><tr><td>Id Transaksi</td><td>Nama Pembeli</td><td>Judul Buku</td><td>Jumlah</td><td>Total Harga</td><td>Tanggal Transaksi</td></tr></thead><tbody>';

while ($row = mysqli_fetch_assoc($transaksi)) {
    $html .= '<tr><td>' .$row['id_transaksi']. '</td><td>' .$row['nama_pengguna']. '</td><td>' .$row['judul_buku']. '</td><td>' .$row['jumlah']. '</td><td>' .number_format($row['total_harga'],2). '</td><td>' .$row['tanggal_transaksi']. '</td></tr>';
}

$html .= '</tbody></table>';

//load html ke dompdf
$dompdf->loadHtml($html);

// set ukuan kertas dan orientasi
$dompdf->setPaper('A4', 'landscape');

//render pdf
$dompdf->render();

//tampilkan PDF ke browser
$dompdf->stream("Laporan Toko Buku.pdf");
?>