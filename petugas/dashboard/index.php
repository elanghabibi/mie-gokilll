<?php
session_start();

include './../../services/domain.php';
include './../../services/middleware/petugas.php';
include './../../services/helpers.php';
include "./../../config/koneksi.php";


// Statistik
// Total Menu
$result = $conn->query("
    SELECT COUNT(*) as total
    FROM menu
");
$totalMenu = $result->fetch_assoc()['total'];


// Total Makanan
$result = $conn->query("
    SELECT COUNT(*) as total
    FROM menu
    WHERE kategori='makanan'
");
$totalMakanan = $result->fetch_assoc()['total'];


// Total Minuman
$result = $conn->query("
    SELECT COUNT(*) as total
    FROM menu
    WHERE kategori='minuman'
");
$totalMinuman = $result->fetch_assoc()['total'];


// Total Cemilan
$result = $conn->query("
    SELECT COUNT(*) as total
    FROM menu
    WHERE kategori='cemilan'
");
$totalCemilan = $result->fetch_assoc()['total'];


// Total Best Seller
$result = $conn->query("
    SELECT COUNT(*) as total
    FROM menu
    WHERE terlaris=1
");
$totalBestSeller = $result->fetch_assoc()['total'];

?>

<?php 
// Data Best Seller
$stmt = $conn->prepare("
    SELECT *
    FROM menu
    WHERE terlaris=1
    ORDER BY created_at DESC
    LIMIT 5
");

$stmt->execute();

$bestSellerMenus = $stmt->get_result();



$stmt = $conn->prepare("
    SELECT *
    FROM menu
    ORDER BY created_at DESC
    LIMIT 5
");

$stmt->execute();

$newMenus = $stmt->get_result();

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
                    Kelola menu dan pantau performa Mie Gokilll dari sini.
                </p>

            </section>

            <!-- Statistik -->
            <section class="grid grid-cols-4 max-md:grid-cols-2 gap-6">

                <div class="bg-white border-4 border-black p-5 shadow-[8px_8px_0px_#000]">
                    <p class="font-space-mono text-sm">
                        TOTAL MENU
                    </p>

                    <h3 class="font-bricolage text-5xl font-black mt-2">
                        <?= $totalMenu ?>
                    </h3>
                </div>

                <div class="bg-white border-4 border-black p-5 shadow-[8px_8px_0px_#000]">
                    <p class="font-space-mono text-sm">
                        MAKANAN
                    </p>

                    <h3 class="font-bricolage text-5xl font-black mt-2">
                        <?= $totalMakanan ?>
                    </h3>
                </div>

                <div class="bg-white border-4 border-black p-5 shadow-[8px_8px_0px_#000]">
                    <p class="font-space-mono text-sm">
                        MINUMAN
                    </p>

                    <h3 class="font-bricolage text-5xl font-black mt-2">
                        <?= $totalMinuman ?>
                    </h3>
                </div>

                <div class="bg-white border-4 border-black p-5 shadow-[8px_8px_0px_#000]">
                    <p class="font-space-mono text-sm">
                        BEST SELLER
                    </p>

                    <h3 class="font-bricolage text-5xl font-black mt-2">
                        <?= $totalBestSeller ?>
                    </h3>
                </div>

            </section>

            <!-- Grid -->
            <section class="grid grid-cols-3 gap-6">

                <!-- Best Seller -->
                <div class="col-span-2 max-md:col-span-3 bg-white border-4 border-black p-6 shadow-[8px_8px_0px_#000]">

                    <div class="flex justify-between items-center mb-6">

                        <h3 class="font-bricolage text-3xl max-md:text-xl font-black">
                            Pesanan Terbaru!
                        </h3>
                    </div>

                    <div class="space-y-4">

                        <?php while($menu = $bestSellerMenus->fetch_assoc()): ?>
                        <div class="border-4 border-black p-4 flex justify-between">
                            <span class="font-bricolage font-bold">
                                <?= $menu['nama_menu'] ?>
                            </span>

                            <span class="font-space-mono">
                                <?= formatHarga($menu['harga']) ?>
                            </span>
                        </div>
                        <?php endwhile;?>
                    </div>

                </div>

                <!-- Aktivitas -->
                <div class="max-md:hidden bg-white border-4 border-black shadow-[8px_8px_0px_#000]">

                    <img src="./../../src/img/komik.jpg" alt="" class="grayscale object-cover w-full h-full">

                </div>

            </section>
            
            <!-- Tabel -->
            <section class="max-md:mb-20 w-full bg-white border-4 border-black p-6 shadow-[8px_8px_0px_#000]">

                <div class="flex justify-between items-center mb-6">

                    <h3 class="font-bricolage text-3xl max-md:text-xl font-black">
                        Menu Terbaru
                    </h3>

                    <a href="<?= $domain . 'admin/menu/tambah.php' ?>"
                        class="px-4 py-2 bg-black text-white border-4 border-black shadow-[4px_4px_0px_#000] hover:translate-x-1 hover:translate-y-1 hover:shadow-none transition-all"
                    >
                        <i class="bx bx-plus"></i>
                    </a>

                </div>

                <div class="w-full overflow-x-auto">
                    <table class="min-w-[900px] w-max text-sm text-left">

                        <thead>

                            <tr class="border-b-4 border-black">
                                <th class="text-left py-4 px-4">Nama Menu</th>
                                <th class="text-left py-4 px-4">Kategori</th>
                                <th class="text-left py-4 px-4">Harga</th>
                                <th class="text-left py-4 px-4">Status</th>
                                <th class="text-left py-4 px-4">Aksi</th>
                            </tr>

                        </thead>

                        <tbody>
                            <?php while($menu = $newMenus->fetch_assoc()): ?>

                            <tr class="border-b-2 border-black">

                                <td class="p-4">
                                    <div class="flex items-center gap-2 h-fit">
                                        <?= htmlspecialchars($menu['nama_menu']) ?>
                                        <?php if ($menu['terlaris'] == 1): ?>
                                            <span class="flex items-center"><i class="bxf bx-star text-yellow-500"></i></span>    
                                        <?php endif ?>
                                    </div>
                                </td>

                                <td class="p-4">
                                    <?= ucfirst($menu['kategori']) ?>
                                </td>

                                <td class="p-4">
                                    Rp<?= number_format($menu['harga'], 0, ',', '.') ?>
                                </td>

                                <td class="p-4">

                                    <?php if($menu['tersedia']): ?>
                                        <span class="px-2 py-1 border-2 border-black">
                                            Tersedia
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 bg-black text-white border-2 border-black">
                                            Habis
                                        </span>
                                    <?php endif; ?>

                                </td>

                                <td class="p-4">
                                    <div class="flex items-center gap-2">
                                        <a href="<?= $domain . 'admin/menu/detail.php?id=' . $menu['id'] ?>" class="flex items-center justify-center bg-white border-black border-2 p-1 w-fit h-fit">
                                            <i class="bx bx-info-circle"></i>
                                        </a>
    
                                        <a href="<?= $domain . 'admin/menu/edit.php?id=' . $menu['id'] ?>" class="flex items-center justify-center bg-white border-black border-2 p-1 w-fit h-fit">
                                            <i class="bx bx-edit"></i>
                                        </a>
    
                                        <form
                                            action="<?= $domain . 'admin/menu/hapus.php' ?>"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus menu ini?')"
                                        >
                                            <input
                                                type="hidden"
                                                name="id"
                                                value="<?= $menu['id'] ?>"
                                            >
    
                                            <button type="submit" class="cursor-pointer flex items-center justify-center bg-white border-black border-2 p-1 w-fit h-fit"><i class="bx bx-trash"></i></button>
                                        </form>
                                    </div>
                                </td>

                            </tr>

                            <?php endwhile; ?>

                            </tbody>

                    </table>
                </div>

            </section>

        </main>

    </div>

</div>
<?php include './../../includes/toast.php'; ?>
</body>
<script src="./../../src/js/script.js"></script>
</html>