<?php
session_start();

include './../../services/domain.php';
include './../../services/middleware/admin.php';
include './../../services/helpers.php';
include "./../../config/koneksi.php";

$namaMenu = $_POST['nama_menu'];
$harga = $_POST['harga'];
$kategori = $_POST['kategori'];
$deskripsi = $_POST['deskripsi'];

$tersedia = isset($_POST['tersedia']) ? 1 : 0;
$terlaris = isset($_POST['terlaris']) ? 1 : 0;

// Upload Foto
$foto = time() . '_' . $_FILES['foto']['name'];

move_uploaded_file(
    $_FILES['foto']['tmp_name'],
    '../../uploads/' . $foto
);

$stmt = $conn->prepare("
    INSERT INTO menu
    (
        nama_menu,
        harga,
        kategori,
        deskripsi,
        foto,
        tersedia,
        terlaris
    )
    VALUES (?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    'sisssii',
    $namaMenu,
    $harga,
    $kategori,
    $deskripsi,
    $foto,
    $tersedia,
    $terlaris
);

$stmt->execute();

toast('success', 'Menu berhasil ditambahkan!');

header('Location: ' . $domain . 'admin/menu');
exit;
?>