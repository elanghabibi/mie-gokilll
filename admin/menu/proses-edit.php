<?php
session_start();

include './../../services/domain.php';
include './../../services/auth-nologin.php';
include './../../services/helpers.php';
include "./../../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = (int) $_POST['id'];
$nama_menu = trim($_POST['nama_menu']);
$harga = (int) preg_replace('/[^0-9]/', '', $_POST['harga']);
$kategori = trim($_POST['kategori']);
$deskripsi = trim($_POST['deskripsi']);

$tersedia = isset($_POST['tersedia']) ? 1 : 0;
$terlaris = isset($_POST['terlaris']) ? 1 : 0;

$fotoLama = $_POST['foto_lama'];

/*
|--------------------------------------------------------------------------
| VALIDASI
|--------------------------------------------------------------------------
*/

if (
    empty($nama_menu) ||
    empty($harga) ||
    empty($kategori)
) {
    $_SESSION['error'] = 'Semua field wajib diisi.';
    header('Location: edit.php?id=' . $id);
    exit;
}

$allowedKategori = ['makanan', 'minuman', 'cemilan'];

if (!in_array($kategori, $allowedKategori)) {
    $_SESSION['error'] = 'Kategori tidak valid.';
    header('Location: edit.php?id=' . $id);
    exit;
}

$fotoBaru = $fotoLama;

/*
|--------------------------------------------------------------------------
| UPLOAD FOTO BARU (JIKA ADA)
|--------------------------------------------------------------------------
*/

if (
    isset($_FILES['foto']) &&
    $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE
) {

    if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = 'Gagal mengupload gambar.';
        header('Location: edit.php?id=' . $id);
        exit;
    }

    $uploadDir = './../../uploads/';

    $fileName = $_FILES['foto']['name'];
    $tmpName = $_FILES['foto']['tmp_name'];

    $extension = strtolower(
        pathinfo($fileName, PATHINFO_EXTENSION)
    );

    $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($extension, $allowedExt)) {
        $_SESSION['error'] = 'Format gambar harus JPG, JPEG, PNG atau WEBP.';
        header('Location: edit.php?id=' . $id);
        exit;
    }

    $newFileName =
        time() . '_' . $_FILES['foto']['name'];

    $destination = $uploadDir . $newFileName;

    if (!move_uploaded_file($tmpName, $destination)) {
        $_SESSION['error'] = 'Gagal menyimpan gambar.';
        header('Location: edit.php?id=' . $id);
        exit;
    }

    // hapus foto lama
    if (
        !empty($fotoLama) &&
        file_exists($uploadDir . $fotoLama)
    ) {
        unlink($uploadDir . $fotoLama);
    }

    $fotoBaru = $newFileName;
}

/*
|--------------------------------------------------------------------------
| UPDATE DATABASE
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    UPDATE menu
    SET
        nama_menu = ?,
        harga = ?,
        kategori = ?,
        deskripsi = ?,
        foto = ?,
        tersedia = ?,
        terlaris = ?
    WHERE id = ?
");

$stmt->bind_param(
    'sisssiii',
    $nama_menu,
    $harga,
    $kategori,
    $deskripsi,
    $fotoBaru,
    $tersedia,
    $terlaris,
    $id
);

if ($stmt->execute()) {

    $_SESSION['success'] = 'Menu berhasil diperbarui.';

    header('Location: index.php');
    exit;
}

$_SESSION['error'] = 'Gagal memperbarui menu.';
header('Location: edit.php?id=' . $id);
exit;
?>