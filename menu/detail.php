<?php

session_start();
include './../config/koneksi.php';
include './../services/domain.php';
include './../services/helpers.php';

if (isset($_GET['id'])) {
    $stmt = $conn->prepare('SELECT * FROM menu WHERE id=?');
    $id_menu = $_GET['id'];
    $stmt->bind_param('i', $id_menu);
    $stmt->execute();
    $result = $stmt->get_result();
    $menu = $result->fetch_assoc();
}

$stmt = $conn->prepare("
    SELECT * 
    FROM menu
    WHERE terlaris = 1
    ORDER BY created_at DESC
    LIMIT 5
");
$stmt->execute();
$bestSellerMenus = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mie Gokilll</title>
    <link rel="stylesheet" href="./../src/css/style.css">
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
<body class="bg-gray-100">
    <?php include './../includes/guest/header.php' ?>

    <section class="px-20 py-8 max-md:px-4 max-md:py-4">
        <h1 class="text-3xl font-bricolage flex items-center gap-2 mb-4 italic"><a href="<?= $domain . 'menu/' ?>" class="flex items-center justify-center"><i class="bx bx-chevron-left"></i></a><span>Detail Menu</span></h1>

        <div class="flex gap-6 max-md:flex-col">

            <!-- Gambar -->
            <div class="relative w-96 max-md:w-full aspect-square shrink-0 overflow-hidden border-4 border-black shadow-[8px_8px_0px_#000]">
                <?php if ($menu['tersedia'] === 0): ?>
                    <!-- Sold Out Overlays -->
                    <div
                    class="absolute inset-0 z-30 sold-out-cross opacity-100 pointer-events-none"
                    ></div>
                    <div
                    class="absolute inset-0 z-20 flex items-center justify-center rotate-[-12deg]"
                    >
                        <div
                            class="bg-red-600 text-white border-4 border-gray-950 font-black text-5xl px-8 py-2 uppercase tracking-tighter shadow-[8px_8px_0px_#000]"
                        >
                            HABIS!
                        </div>
                    </div>
                <?php endif; ?>
                <img
                    class="w-full h-full object-cover <?= $menu['tersedia'] === 0 ? 'grayscale' : '' ?>"
                    src="<?= $menu['foto'] ? $domain . 'uploads/' . $menu['foto'] : $domain . 'src/img/placeholder-image.png' ?>"
                    alt="<?= $menu['nama_menu'] ?>"
                >
            </div>

            <!-- Detail -->
            <div class="flex-1 bg-gray-50 border-4 border-black p-6 shadow-[8px_8px_0px_#000]">
                
                <div class="flex max-md:flex-col justify-between items-start gap-4 max-md:gap-2">
                    <div class="space-y-3 max-md:space-y-2">
                        <?php if ($menu['terlaris'] === 1): ?>
                            <span class="inline-block px-3 py-1 text-xs font-bold border-2 border-black bg-yellow-300">
                                BEST SELLER
                            </span>
                        <?php endif ?>

                        <h1 class="text-4xl font-black font-bricolage">
                            <?= $menu['nama_menu'] ?>
                        </h1>

                        <p class="text-gray-600">
                            Kategori: <?= ucfirst($menu['kategori']) ?>
                        </p>
                    </div>

                    <div class="text-right max-md:text-left">
                        <p class="text-3xl font-black font-space-mono">
                            Rp<?= number_format($menu['harga'], 0, ',', '.') ?>
                        </p>
                        <p class="text-sm <?= $menu['tersedia'] === 1 ? 'text-green-600' : 'text-red-600' ?> font-medium">
                            <?= $menu['tersedia'] === 1 ? 'Tersedia' : 'Habis' ?>
                        </p>
                    </div>
                </div>

                <!-- NANTI TOLONG BIKIN ADMIN DASHBOARD -->

                <div class="mt-6">
                    <h2 class="font-bold text-lg">
                        Deskripsi
                    </h2>

                    <p class="mt-2 text-gray-700 leading-relaxed">
                        <?= $menu['deskripsi'] ?>
                    </p>
                </div>

            </div>

        </div>
    </section>

    <?php include './../includes/guest/footer.php' ?>
</body>
</html>