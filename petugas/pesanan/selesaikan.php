<?php

session_start();

include './../../config/koneksi.php';
include './../../services/domain.php';
include './../../services/middleware/petugas.php';

if (!isset($_GET['id'])) {
    header("Location: " . $domain . "petugas/pesanan/proses-pesanan.php");
    exit;
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("
    UPDATE pesanan
    SET status = 'selesai'
    WHERE id_pesanan = ?
    AND status = 'diproses'
");

$stmt->bind_param("i", $id);

if ($stmt->execute()) {

    $_SESSION['success'] = "Pesanan berhasil diselesaikan.";

    header("Location: " . $domain . "petugas/pesanan/proses-pesanan.php");
    exit;
}

$_SESSION['error'] = "Gagal menyelesaikan pesanan.";

header("Location: " . $domain . "petugas/pesanan/proses-pesanan.php");
exit;