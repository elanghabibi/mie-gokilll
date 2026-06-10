<?php
session_start();

include './../../config/koneksi.php';
include './../../services/domain.php';
include './../../services/middleware/petugas.php';
include './../../services/helpers.php';

// Ambil pesanan yang belum selesai
$statusFilter = $_GET['status'] ?? 'all';

if ($statusFilter == 'all') {

    $query = $conn->query("
        SELECT *
        FROM pesanan
        ORDER BY created_at DESC
    ");

} else {

    $stmt = $conn->prepare("
        SELECT *
        FROM pesanan
        WHERE status = ?
        ORDER BY created_at DESC
    ");

    $stmt->bind_param("s", $statusFilter);
    $stmt->execute();

    $query = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesanan - Mie Gokilll</title>
    <link rel="stylesheet" href="./../../src/css/style.css">
    <link
      href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@700;800&amp;family=Inter:wght@400;500&amp;family=Space+Mono:wght@400;700&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@100..900&amp;family=Inter:wght@100..900&amp;family=Space+Mono:wght@100..900&amp;display=swap"
      rel="stylesheet"
    />
    <!-- Basic Icons -->
    <link href="https://cdn.boxicons.com/3.0.8/fonts/basic/boxicons.min.css" rel="stylesheet">
    <!-- Filled Icons -->
    <link href="https://cdn.boxicons.com/3.0.8/fonts/filled/boxicons-filled.min.css" rel="stylesheet">
    <!-- Brand Icons -->
    <link href="https://cdn.boxicons.com/3.0.8/fonts/brands/boxicons-brands.min.css" rel="stylesheet">
</head>
<body class="h-screen overflow-hidden bg-zinc-100">
<div
    id="backdrop"
    class="hidden fixed inset-0 bg-black/50 z-40 md:hidden"
></div>
<div class="flex h-screen">

    <!-- SIDEBAR -->
    <?php include './../../includes/petugas/sidebar.php'; ?>
    

    <!-- CONTENT -->
    <div class="flex flex-col flex-1 h-screen min-w-0">

        <!-- HEADER -->
        <?php include './../../includes/petugas/header.php'; ?>

        <!-- SCROLLABLE MAIN -->
        <main class="flex-1 overflow-y-auto p-8 space-y-8 max-md:p-4 max-md:space-y-4 min-w-0">
            
            <!-- INFO -->
            <div class="grid grid-cols-4 max-md:grid-cols-2 gap-6 mb-8">

                <?php

                $menunggu = $conn->query("
                    SELECT COUNT(*) total
                    FROM pesanan
                    WHERE status='menunggu_konfirmasi'
                ")->fetch_assoc()['total'];

                $diproses = $conn->query("
                    SELECT COUNT(*) total
                    FROM pesanan
                    WHERE status='diproses'
                ")->fetch_assoc()['total'];

                $selesai = $conn->query("
                    SELECT COUNT(*) total
                    FROM pesanan
                    WHERE status='selesai'
                ")->fetch_assoc()['total'];

                $dibatalkan = $conn->query("
                    SELECT COUNT(*) total
                    FROM pesanan
                    WHERE status='dibatalkan'
                ")->fetch_assoc()['total'];

                ?>

                <div class="bg-white border-4 border-black p-6 shadow-[8px_8px_0px_#000]">
                    <h3 class="font-black text-xl">
                        MENUNGGU
                    </h3>

                    <p class="text-5xl font-black mt-2">
                        <?= $menunggu ?>
                    </p>
                </div>

                <div class="bg-white border-4 border-black p-6 shadow-[8px_8px_0px_#000]">
                    <h3 class="font-black text-xl">
                        DIPROSES
                    </h3>

                    <p class="text-5xl font-black mt-2">
                        <?= $diproses ?>
                    </p>
                </div>

                <div class="bg-white border-4 border-black p-6 shadow-[8px_8px_0px_#000]">
                    <h3 class="font-black text-xl">
                        SELESAI
                    </h3>

                    <p class="text-5xl font-black mt-2">
                        <?= $selesai ?>
                    </p>
                </div>

                <div class="bg-white border-4 border-black p-6 shadow-[8px_8px_0px_#000]">
                    <h3 class="font-black text-xl">
                        DIBATALKAN
                    </h3>

                    <p class="text-5xl font-black mt-2">
                        <?= $dibatalkan ?>
                    </p>
                </div>

            </div>

            <!-- LIST PESANAN -->
            <div
                class="bg-white border-4 border-black shadow-[8px_8px_0px_#000]"
            >

                <div
                    class="border-b-4 flex flex-col gap-4 border-black p-4"
                >
                    <h2 class="text-3xl font-black font-[Bricolage_Grotesque]">
                        PESANAN MASUK
                    </h2>

                    <div class="flex flex-wrap gap-3 mb-6">
                        <a
                            href="?status=all"
                            class="border-4 border-black px-4 py-2 font-bricolage font-bold shadow-[4px_4px_0px_#000]
                            <?= $statusFilter == 'all'
                                ? 'bg-black text-white'
                                : 'bg-white text-black' ?>"
                        >
                            Semua
                        </a>

                        <a
                            href="?status=menunggu_konfirmasi"
                            class="border-4 border-black px-4 py-2 font-bricolage font-bold shadow-[4px_4px_0px_#000]
                            <?= $statusFilter == 'menunggu_konfirmasi'
                                ? 'bg-yellow-300'
                                : 'bg-white text-black' ?>"
                        >
                            Menunggu
                        </a>

                        <a
                            href="?status=diproses"
                            class="border-4 border-black px-4 py-2 font-bricolage font-bold shadow-[4px_4px_0px_#000]
                            <?= $statusFilter == 'diproses'
                                ? 'bg-blue-300'
                                : 'bg-white text-black' ?>"
                        >
                            Diproses
                        </a>

                        <a
                            href="?status=selesai"
                            class="border-4 border-black px-4 py-2 font-bricolage font-bold shadow-[4px_4px_0px_#000]
                            <?= $statusFilter == 'selesai'
                                ? 'bg-green-300'
                                : 'bg-white text-black' ?>"
                        >
                            Selesai
                        </a>

                        <a
                            href="?status=dibatalkan"
                            class="border-4 border-black px-4 py-2 font-bricolage font-bold shadow-[4px_4px_0px_#000]
                            <?= $statusFilter == 'dibatalkan'
                                ? 'bg-red-300'
                                : 'bg-white text-black' ?>"
                        >
                            Dibatalkan
                        </a>

                    </div>
                </div>

                <div class="p-6">

                    <div class="space-y-6">

                        <?php while($pesanan = $query->fetch_assoc()): ?>
                            <?php $waktu = waktuPesanan($pesanan['created_at']); ?>

                            <div
                                class="border-4 border-black p-5 bg-zinc-50 shadow-[4px_4px_0px_#000]"
                            >

                                <div class="flex flex-col md:flex-row md:justify-between gap-4">

                                    <div>

                                        <div class="flex items-center gap-3">

                                            <h3 class="font-black text-2xl">
                                                <?= htmlspecialchars($pesanan['nama_pelanggan']) ?>
                                            </h3>

                                            <?php if($pesanan['status'] == 'menunggu_konfirmasi'): ?>

                                                <span
                                                    class="px-2 py-1 border-2 border-black bg-yellow-300 text-xs font-bold"
                                                >
                                                    MENUNGGU
                                                </span>

                                            <?php elseif($pesanan['status'] == 'diproses'): ?>

                                                <span
                                                    class="px-2 py-1 border-2 border-black bg-blue-300 text-xs font-bold"
                                                >
                                                    DIPROSES
                                                </span>

                                            <?php elseif($pesanan['status'] == 'selesai'): ?>

                                                <span
                                                    class="px-2 py-1 border-2 border-black bg-green-300 text-xs font-bold"
                                                >
                                                    SELESAI
                                                </span>

                                            <?php elseif($pesanan['status'] == 'dibatalkan'): ?>

                                                <span
                                                    class="px-2 py-1 border-2 border-black bg-red-300 text-xs font-bold"
                                                >
                                                    DIBATALKAN
                                                </span>

                                            <?php endif; ?>

                                        </div>

                                        <div class="mt-3 space-y-1 text-sm font-mono">

                                            <p>
                                                Waktu Pesan :
                                                <?= $waktu['full'] ?>
                                            </p>

                                            <p>
                                                Nota :
                                                <?= $pesanan['nota_pesanan'] ?>
                                            </p>

                                            <p>
                                                Meja :
                                                <?= $pesanan['nomor_meja'] ?>
                                            </p>

                                            <p>
                                                Total :
                                                Rp<?= number_format($pesanan['total_harga'],0,',','.') ?>
                                            </p>

                                            <p>
                                                Pembayaran :
                                                <?= strtoupper($pesanan['metode_pembayaran']) ?>
                                            </p>

                                        </div>

                                    </div>

                                    <div class="flex md:flex-col justify-between items-end">
                                        <div
                                            class="flex flex-wrap gap-2 items-start"
                                        >

                                            <?php if($pesanan['status'] == 'menunggu_konfirmasi'): ?>

                                                <a
                                                    href="konfirmasi.php?id=<?= $pesanan['id_pesanan'] ?>"
                                                    class="
                                                    px-4 py-2
                                                    bg-white
                                                    border-4 border-black
                                                    shadow-[4px_4px_0px_#000]
                                                    hover:translate-x-1
                                                    hover:translate-y-1
                                                    hover:shadow-none
                                                    transition-all
                                                    font-bold
                                                    "
                                                >
                                                    KONFIRMASI
                                                </a>

                                            <?php endif; ?>

                                            <?php if($pesanan['status'] == 'diproses'): ?>

                                                <a
                                                    href="selesaikan.php?id=<?= $pesanan['id_pesanan'] ?>"
                                                    class="
                                                    px-4 py-2
                                                    bg-black
                                                    text-white
                                                    border-4 border-black
                                                    shadow-[4px_4px_0px_#000]
                                                    hover:translate-x-1
                                                    hover:translate-y-1
                                                    hover:shadow-none
                                                    transition-all
                                                    font-bold
                                                    "
                                                >
                                                    SELESAIKAN
                                                </a>

                                            <?php endif; ?>

                                            <?php if($pesanan['status'] != 'selesai' && $pesanan['status'] != 'dibatalkan'): ?>

                                                <a
                                                    href="batalkan.php?id=<?= $pesanan['id_pesanan'] ?>"
                                                    onclick="return confirm('Batalkan pesanan ini?')"
                                                    class="
                                                    px-4 py-2
                                                    bg-white
                                                    border-4 border-black
                                                    shadow-[4px_4px_0px_#000]
                                                    hover:translate-x-1
                                                    hover:translate-y-1
                                                    hover:shadow-none
                                                    transition-all
                                                    font-bold
                                                    "
                                                >
                                                    BATALKAN
                                                </a>

                                            <?php endif; ?>

                                        </div>

                                        <a
                                            href="<?= $domain . 'petugas/pesanan/struk.php?id=' . $pesanan['id_pesanan'] ?>"
                                            class="flex p-2 text-2xl bg-white border-4 border-black shadow-[4px_4px_0px_#000] hover:translate-x-1 hover:translate-y-1 hover:shadow-none transition-all font-bold"
                                        >
                                            <i class="bx bx-info-circle"></i>
                                        </a>
                                    </div>
                                    

                                </div>

                            </div>

                        <?php endwhile; ?>

                    </div>

                </div>

            </div>

            
        </main>

    </div>

</div>
<?php include './../../includes/toast.php'; ?>
</body>
<script src="./../../src/js/script.js"></script>
</html>