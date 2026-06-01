<?php

session_start();
include './config/koneksi.php';
include './services/domain.php';

$stmt = $conn->prepare("
    SELECT * 
    FROM menu
    WHERE terlaris = 1
    ORDER BY created_at DESC
    LIMIT 5
");
$stmt->execute();
$bestSellerMenus = $stmt->get_result();

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);

$limit = 8; // jumlah data per halaman
$offset = ($page - 1) * $limit;

$result = $conn->query("SELECT COUNT(*) as total FROM menu");
$totalData = $result->fetch_assoc()['total'];

$totalPages = ceil($totalData / $limit);

$stmt = $conn->prepare("
    SELECT *
    FROM menu
    ORDER BY created_at DESC
    LIMIT ? OFFSET ?
");

$stmt->bind_param('ii', $limit, $offset);
$stmt->execute();

$allMenus = $stmt->get_result();

function cutText($text, $limit = 100)
{
    if (strlen($text) > $limit) {
        return substr($text, 0, $limit) . '...';
    }
    return $text;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mie Gokilll</title>
    <link rel="stylesheet" href="src/css/style.css">
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
<body class="bg-gray-100 overflow-x-hidden">
    <?php include './includes/guest/header.php' ?>

    <section class="px-20 py-8 mb-8 max-md:p-4 space-y-6">
        <h2 class="flex items-center text-4xl gap-2"><i class="bxf bx-star"></i><span class="font-semibold font-bricolage italic">BEST SELLER!!!</span></h2>

        <!-- CONTAINER MENU -->
        <div class="grid grid-cols-4 max-md:grid-cols-2 w-full p-4 max-md:p-0 gap-6">
            <?php if($bestSellerMenus->num_rows > 0): ?>
                <?php while ($menu = $bestSellerMenus->fetch_assoc()): ?>
                <!-- CARD MENU -->
                <a href="<?= './menu/detail.php?id=' . $menu['id'] ?>">
                    <div class="relative h-full p-4 border-4 border-gray-950 bg-gray-50 shadow-[8px_8px_0px_#000]  hover:translate-x-1 hover:translate-y-1 hover:shadow-none transition-all">
                        <?php if ($menu['tersedia'] === 0): ?>
                            <!-- Sold Out Overlays -->
                            <div
                                class="absolute inset-0 z-30 sold-out-cross pointer-events-none"
                                ></div>
                            <div
                            class="absolute inset-0 z-20 flex items-center justify-center rotate-[-12deg]"
                            >
                                <div
                                    class="bg-red-600 text-white border-4 border-gray-950 font-black text-5xl max-md:text-xl px-8 py-2 uppercase tracking-tighter shadow-[8px_8px_0px_#000]"
                                >
                                    HABIS!
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($menu['tersedia'] === 0): ?>
                            <div class="relative opacity-50 grayscale">
                        <?php endif ?>
                                <div class="w-full aspect-square overflow-hidden shrink-0">
                                    <img class="w-full h-full object-cover" src="<?= $menu['foto'] ? $domain . 'uploads/' . $menu['foto'] : $domain . 'src/img/placeholder-image.png' ?>" alt="<?= $menu['nama_menu'] ?>">
                                </div>

                                <div class="p-2 space-y-4">
                                    <div class="space-y-2">
                                        <h3 class="font-bricolage font-semibold text-2xl max-md:text-lg leading-6"><?= $menu['nama_menu'] ?></h3>
                                        <p class="text-xs"><?= ucfirst($menu['kategori']) ?></p>
                                    </div>    
                                    <p class="font-space-mono text-2xl max-md:text-lg leading-5">Rp<?= number_format($menu['harga'], 0, ',', '.') ?></p>
                                </div>

                                <div class="absolute font-bold border-2 border-black bg-yellow-300 rotate-30 max-md:rotate-0 -top-2 max-md:-top-4 -right-8 max-md:right-1/2 max-md:translate-x-1/2 px-4 py-2 max-md:px-3 max-md:py-1 max-md:text-sm font-bricolage">
                                    TERLARIS
                                </div>
                        <?php if ($menu['tersedia'] === 0): ?>
                            </div>
                        <?php endif ?>
                    </div>
                </a>
                <!-- END CARD MENU 1 -->
                 <?php endwhile; ?>
             <?php endif ?>
        </div>
    </section>

    <a href="./menu/" class="mx-auto block w-fit h-fit bg-white border-4 border-black shadow-[8px_8px_0px_#000] px-4 py-2">
        <div class="flex items-center gap-2 font-bricolage">
            <p>LIHAT SEMUA MENU</p>
            <i class="bx bx-arrow-right text-2xl"></i>
        </div>
    </a>

    <?php include './includes/guest/footer.php' ?>

</body>
</html>