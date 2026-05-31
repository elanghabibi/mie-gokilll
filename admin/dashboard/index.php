<?php
session_start();

include './../../services/domain.php';
include './../../services/auth-nologin.php';
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
<body class="h-screen overflow-hidden bg-zinc-100">

<div class="flex h-screen">

    <!-- SIDEBAR -->
    <aside class="w-72 h-screen shrink-0 bg-white border-r-4 border-black flex flex-col">

        <!-- Logo -->
        <div class="p-6 border-b-4 border-black">
            <div class="relative group">
                <a href="<?= $domain ?>" class="flex w-fit h-fit bg-white border-4 border-gray-950 px-4 py-2 relative z-10">
                    <h1
                    class="font-bricolage text-5xl font-black uppercase italic tracking-tighter text-gray-950"
                    >
                    MIE GOKILLL
                    </h1>
                </a>
                <div
                    class="absolute -bottom-4 left-8 w-6 h-6 bg-gray-950 speech-bubble-tail"
                ></div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 p-4 space-y-4">

            <a
                href="<?= $domain . 'admin/dashboard' ?>"
                class="block p-4 border-4 border-black bg-black text-white shadow-[6px_6px_0px_#000] hover:translate-x-1 hover:translate-y-1 hover:shadow-none transition-all"
            >
                <div class="flex items-center gap-3">
                    <i class="bx bx-home text-2xl"></i>
                    <span class="font-space-mono font-bold">
                        Dashboard
                    </span>
                </div>
            </a>

            <a
                href="<?= $domain . 'admin/menu' ?>"
                class="block p-4 border-4 border-black bg-white shadow-[6px_6px_0px_#000] hover:translate-x-1 hover:translate-y-1 hover:shadow-none transition-all"
            >
                <div class="flex items-center gap-3">
                    <i class="bx bx-food-menu text-2xl"></i>
                    <span class="font-space-mono font-bold">
                        Kelola Menu
                    </span>
                </div>
            </a>

        </nav>

        <!-- Footer Sidebar -->
        <div class="p-4 border-t-4 border-black">

            <div class="border-4 border-black p-4 bg-zinc-50 shadow-[6px_6px_0px_#000]">
                <p class="font-space-mono text-xs">
                    LOGIN SEBAGAI
                </p>

                <h3 class="font-bricolage text-xl font-black">
                    ADMIN
                </h3>
            </div>

        </div>

    </aside>

    <!-- CONTENT -->
    <div class="flex flex-col flex-1 h-screen">

        <!-- HEADER -->
        <header
            class="sticky top-0 z-50 h-20 shrink-0 bg-white border-b-4 border-black flex items-center justify-between px-8"
        >

            <div>
                <h2 class="font-bricolage text-3xl font-black">
                    Dashboard
                </h2>
            </div>

            <div class="flex items-center gap-4">

                <div class="font-space-mono text-sm">
                    Selamat datang, <?= ucfirst($_SESSION['username']) ?>
                </div>

                <form action="<?= $domain . 'logout.php' ?>" method="POST">
                    <button
                        type="submit"
                        class="cursor-pointer px-4 py-2 border-4 border-black bg-white shadow-[4px_4px_0px_#000] hover:translate-x-1 hover:translate-y-1 hover:shadow-none transition-all"
                    >
                        Logout
                    </button>
                </form>

            </div>

        </header>

        <!-- SCROLLABLE MAIN -->
        <main class="flex-1 overflow-y-auto p-8 space-y-8">

            <!-- Welcome -->
            <section class="bg-white border-4 border-black p-6 shadow-[8px_8px_0px_#000]">

                <h2 class="font-bricolage text-4xl font-black">
                    Halo <?= ucfirst($_SESSION['username']) ?>!
                </h2>

                <p class="font-space-mono mt-2">
                    Kelola menu dan pantau performa Mie Gokilll dari sini.
                </p>

            </section>

            <!-- Statistik -->
            <section class="grid grid-cols-4 gap-6">

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
                <div class="col-span-2 bg-white border-4 border-black p-6 shadow-[8px_8px_0px_#000]">

                    <div class="flex justify-between items-center mb-6">

                        <h3 class="font-bricolage text-3xl font-black">
                            Menu Terlaris
                        </h3>

                        <span class="px-3 py-1 bg-black text-white border-2 border-black font-space-mono">
                            TOP MENU
                        </span>

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
                <div class="bg-white border-4 border-black shadow-[8px_8px_0px_#000]">

                    <img src="./../../src/img/komik.jpg" alt="" class="grayscale object-cover w-full h-full">

                </div>

            </section>

            <!-- Tabel -->
            <section class="bg-white border-4 border-black p-6 shadow-[8px_8px_0px_#000]">

                <div class="flex justify-between items-center mb-6">

                    <h3 class="font-bricolage text-3xl font-black">
                        Menu Terbaru
                    </h3>

                    <a href="<?= $domain . 'admin/menu/tambah.php' ?>"
                        class="px-4 py-2 bg-black text-white border-4 border-black shadow-[4px_4px_0px_#000] hover:translate-x-1 hover:translate-y-1 hover:shadow-none transition-all"
                    >
                        + Tambah Menu
                    </a>

                </div>

                <table class="w-full">

                    <thead>

                        <tr class="border-b-4 border-black">
                            <th class="text-left py-4">Nama Menu</th>
                            <th class="text-left py-4">Kategori</th>
                            <th class="text-left py-4">Harga</th>
                            <th class="text-left py-4">Status</th>
                            <th class="text-left py-4">Aksi</th>
                        </tr>

                    </thead>

                    <tbody>
                        <?php while($menu = $newMenus->fetch_assoc()): ?>

                        <tr class="border-b-2 border-black">

                            <td class="py-4 flex items-center gap-2 h-fit">
                                <?= htmlspecialchars($menu['nama_menu']) ?>
                                <?php if ($menu['terlaris'] === 1): ?>
                                    <span class="flex items-center"><i class="bxf bx-star text-yellow-500"></i></span>    
                                <?php endif ?>
                            </td>

                            <td>
                                <?= ucfirst($menu['kategori']) ?>
                            </td>

                            <td>
                                Rp<?= number_format($menu['harga'], 0, ',', '.') ?>
                            </td>

                            <td>

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

                            <td class="flex items-center gap-2">
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
                            </td>

                        </tr>

                        <?php endwhile; ?>

                        </tbody>

                </table>

            </section>

        </main>

    </div>

</div>
<?php include './../../includes/toast.php'; ?>
</body>
<script src="./../../src/js/script.js"></script>
</html>