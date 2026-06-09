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
    SET status = 'dibatalkan'
    WHERE id_pesanan = ?
    AND status IN ('menunggu_konfirmasi','diproses')
");

$stmt->bind_param("i", $id);

if ($stmt->execute()) {

    $_SESSION['success'] = "Pesanan berhasil dibatalkan.";

    header("Location: " . $domain . "petugas/pesanan/proses-pesanan.php");
    exit;
}

$_SESSION['error'] = "Gagal membatalkan pesanan.";

header("Location: " . $domain . "petugas/pesanan/proses-pesanan.php");
exit;