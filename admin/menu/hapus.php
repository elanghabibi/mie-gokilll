<?php
session_start();

include './../../services/domain.php';
include './../../services/middleware/admin.php';
include './../../services/helpers.php';
include "./../../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $domain . 'admin/menu');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);

if ($id <= 0) {
    $_SESSION['error'] = 'ID menu tidak valid.';
    header('Location: ' . $domain . 'admin/menu');
    exit;
}

/*
|--------------------------------------------------------------------------
| Ambil Data Menu
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT foto
    FROM menu
    WHERE id = ?
");

$stmt->bind_param('i', $id);
$stmt->execute();

$menu = $stmt->get_result()->fetch_assoc();

if (!$menu) {
    $_SESSION['error'] = 'Menu tidak ditemukan.';
    header('Location: ' . $domain . 'admin/menu');
    exit;
}

/*
|--------------------------------------------------------------------------
| Hapus Foto
|--------------------------------------------------------------------------
*/

$fotoPath = './../../uploads/' . $menu['foto'];

if (
    !empty($menu['foto']) &&
    file_exists($fotoPath)
) {
    unlink($fotoPath);
}

/*
|--------------------------------------------------------------------------
| Hapus Data
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    DELETE FROM menu
    WHERE id = ?
");

$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    $_SESSION['success'] = 'Menu berhasil dihapus.';
} else {
    $_SESSION['error'] = 'Gagal menghapus menu.';
}

header('Location: ' . $domain . 'admin/menu');
exit;
?>