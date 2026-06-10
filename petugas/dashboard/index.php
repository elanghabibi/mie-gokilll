<?php
session_start();

include './../../services/domain.php';
include './../../services/middleware/petugas.php';
include './../../services/helpers.php';
include "./../../config/koneksi.php";
?>

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

<?php 
$stmt = $conn->prepare("
    SELECT *
    FROM pesanan
    WHERE status='menunggu_konfirmasi'
    ORDER BY created_at ASC
    LIMIT 5
");

$stmt->execute();

$newPesanan = $stmt->get_result();

                               
// $stmt = $conn->prepare("               
//     SELECT *
//     FROM pesanan                                               
//     ORDER BY crea ted_at DESC
//     LIMIT 5                                                                                                                       
// ");           

// $stmt->execute();

// $newPesanan = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Mie Gokilll</title>
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
<body class="w-full h-screen overflow-hidden bg-zinc-100">
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
        <main class="min-w-0 w-full flex-1 overflow-y-auto p-8 space-y-8 max-md:p-4 max-md:space-y-4">

            <!-- Welcome -->
            <section class="bg-white border-4 border-black p-6 shadow-[8px_8px_0px_#000]">

                <h2 class="font-bricolage text-4xl font-black">
                    Halo <?= ucfirst(explode(' ', $_SESSION['nama_lengkap'])[0]) ?>!
                </h2>

                <p class="font-space-mono mt-2">
                    Kelola pesanan Mie Gokilll dari sini.
                </p>

            </section>

            <!-- Statistik -->
            <section class="flex flex-col gap-4">
                <h2 class="text-4xl font-bold font-bricolage">STATISTIK PESANAN</h2>

                <div class="grid grid-cols-4 max-md:grid-cols-2 gap-6">
                    <div class="bg-white border-4 border-black p-5 shadow-[8px_8px_0px_#000]">
                        <p class="font-space-mono text-sm">
                            MENUNGGU
                        </p>
    
                        <h3 class="font-bricolage text-5xl font-black mt-2">
                            <?= $menunggu ?>
                        </h3>
                    </div>
    
                    <div class="bg-white border-4 border-black p-5 shadow-[8px_8px_0px_#000]">
                        <p class="font-space-mono text-sm">
                            DIPROSES
                        </p>
    
                        <h3 class="font-bricolage text-5xl font-black mt-2">
                            <?= $diproses ?>
                        </h3>
                    </div>
    
                    <div class="bg-white border-4 border-black p-5 shadow-[8px_8px_0px_#000]">
                        <p class="font-space-mono text-sm">
                            SELESAI
                        </p>
    
                        <h3 class="font-bricolage text-5xl font-black mt-2">
                            <?= $selesai ?>
                        </h3>
                    </div>
    
                    <div class="bg-white border-4 border-black p-5 shadow-[8px_8px_0px_#000]">
                        <p class="font-space-mono text-sm">
                            DIBATALKAN
                        </p>
    
                        <h3 class="font-bricolage text-5xl font-black mt-2">
                            <?= $dibatalkan ?>
                        </h3>
                    </div>
                </div>


            </section>

            <!-- Grid -->
            <section class="grid grid-cols-3 gap-6">

                <!-- Best Seller -->
                <div class="col-span-2 max-md:col-span-3 bg-white border-4 border-black p-6 shadow-[8px_8px_0px_#000]">

                    <div class="flex justify-between items-center mb-6">

                        <h3 class="font-bricolage text-3xl max-md:text-xl font-black">
                            Pesanan Masuk
                        </h3>
                    </div>

                    <div class="space-y-4">

                        <?php while($pesanan = $newPesanan->fetch_assoc()): ?>
                        <div class="border-4 border-black p-4">
                            <div class="flex justify-between">
                                <span class="font-bricolage font-bold text-xl">
                                    <?= $pesanan['nama_pelanggan'] ?>
                                </span>

                                <span class="font-space-mono">
                                    Meja <?= $pesanan['nomor_meja'] ?>
                                </span>
                            </div>

                            <div class="mt-2 text-sm font-space-mono">
                                <?= formatHarga($pesanan['total_harga']) ?>
                            </div>
                        </div>
                        <?php endwhile;?>
                    </div>

                </div>

                <!-- Aktivitas -->
                <div class="max-md:hidden bg-white border-4 border-black shadow-[8px_8px_0px_#000]">

                    <img src="./../../src/img/komik.jpg" alt="" class="grayscale object-cover w-full h-full">

                </div>

            </section>
            
        </main>

    </div>

</div>
<?php include './../../includes/toast.php'; ?>
</body>
<script src="./../../src/js/script.js"></script>
</html>