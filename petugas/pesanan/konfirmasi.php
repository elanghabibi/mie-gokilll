<?php

session_start();

include './../../config/koneksi.php';
include './../../services/middleware/petugas.php';
include './../../services/domain.php';

if (!isset($_GET['id'])) {
    header("Location: " . $domain . "petugas/pesanan/proses-pesanan.php");
    exit;
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("
    UPDATE pesanan
    SET status = 'diproses'
    WHERE id_pesanan = ?
    AND status = 'menunggu_konfirmasi'
");

$stmt->bind_param("i", $id);

if ($stmt->execute()) {

    $_SESSION['success'] = "Pesanan berhasil dikonfirmasi dan sedang diproses.";

    header("Location: " . $domain . "petugas/pesanan/proses-pesanan.php");
    exit;
}

$_SESSION['error'] = "Gagal mengonfirmasi pesanan.";

header("Location: " . $domain . "petugas/pesanan/proses-pesanan.php");
exit;
?>