<?php

include './config/koneksi.php';
session_start();

$stmt = $conn->prepare("
    SELECT * 
    FROM menu
    WHERE terlaris = 1
    ORDER BY created_at DESC
    LIMIT 5
");
$stmt->execute();
$bestSellerMenus = $stmt->get_result();


// Ambil semua menu
$stmt = $conn->prepare("
    SELECT *
    FROM menu
    ORDER BY created_at DESC
");
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
    <link href="https://cdn.boxicons.com/3.0.8/fonts/brands/boxicons-brands.min.css" rel="stylesheet"></section>
</head>
<body class="bg-gray-100">
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
                            <div class="opacity-50 cursor-not-allowed grayscale">
                        <?php endif ?>
                                <div class="w-full aspect-square overflow-hidden shrink-0">
                                    <img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDi2jYhjR0IvZgqNPCtUI4N2vLRvFUbre1ir4M3AtVSWLKvILY9SPmXK88DPrq82xs3dmRxa8ulCFrMg746TSPopGFht4D7VL5rhz1CYwuoVFn6pyHtgbFfALDTaLuiWi8eAGWseWCzcqilILrwCXhRrYZJPDBT2hlyLbP9m1Ul8N9nBRt4aJrVA4vEtw_edNgFdkXwmczqPUORfXgq06hNrPO-vNCphokQeebKCX40JZWAjit_FkeIjUPUg5ksB2rRbBItaKrBtqQ" alt="">
                                </div>

                                <div class="p-2 space-y-2">
                                    <h3 class="font-bricolage font-semibold text-2xl max-md:text-lg leading-5"><?= $menu['nama_menu'] ?></h3>
                                    <p class="font-space-mono text-2xl max-md:text-lg leading-5 font-semibold">Rp<?= number_format($menu['harga'], 0, ',', '.') ?></p>
                                </div>

                                <div class="absolute font-bold border-2 border-black bg-yellow-300 rotate-30 max-md:rotate-0 -top-2 max-md:-top-4 -right-8 max-md:right-1/2 max-md:translate-x-1/2 px-4 py-2 max-md:px-3 max-md:py-1 max-md:text-sm font-bricolage">
                                    BEST SELLER
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

    <!-- SEC SEMUA MENU -->
    <section class="px-20 py-8 max-md:p-4 space-y-4">
        <h2 class="flex items-center text-4xl gap-2"><i class="bxf bx-fork-spoon"></i><span class="font-semibold font-bricolage italic">SEMUA MENU</span></h2>

        <!-- CONTAINER MENU -->
        <div class="grid grid-cols-4 max-md:grid-cols-2 w-full p-4 max-md:p-0 gap-6">
            <?php if($allMenus->num_rows > 0): ?>
                <?php while ($menu = $allMenus->fetch_assoc()): ?>
                    <!-- CARD MENU 1 -->
                    <a href="<?= './menu/detail.php?id=' . $menu['id'] ?>">
                        <div class="relative h-full p-4 border-4 border-gray-950 bg-gray-50 shadow-[8px_8px_0px_#000]  hover:translate-x-1 hover:translate-y-1 hover:shadow-none transition-all">
                            <!-- Sold Out Overlays -->
                            <?php if ($menu['tersedia'] === 0): ?>
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
                                <div class="opacity-50 cursor-not-allowed grayscale">
                            <?php endif ?>
                                <div class="w-full aspect-square overflow-hidden shrink-0">
                                        <img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDi2jYhjR0IvZgqNPCtUI4N2vLRvFUbre1ir4M3AtVSWLKvILY9SPmXK88DPrq82xs3dmRxa8ulCFrMg746TSPopGFht4D7VL5rhz1CYwuoVFn6pyHtgbFfALDTaLuiWi8eAGWseWCzcqilILrwCXhRrYZJPDBT2hlyLbP9m1Ul8N9nBRt4aJrVA4vEtw_edNgFdkXwmczqPUORfXgq06hNrPO-vNCphokQeebKCX40JZWAjit_FkeIjUPUg5ksB2rRbBItaKrBtqQ" alt="">
                                </div>
                
                                <div class="p-2 space-y-2">
                                    <h3 class="font-bricolage font-semibold text-2xl max-md:text-lg leading-5"><?= $menu['nama_menu'] ?></h3>
                                    <p class="font-space-mono text-2xl max-md:text-lg leading-5 font-semibold">Rp<?= number_format($menu['harga'], 0, ',', '.') ?></p>
                                </div>
                                
                                <?php if ($menu['terlaris'] === 1): ?>
                                <div class="absolute font-bold border-2 border-black bg-yellow-300 rotate-30 max-md:rotate-0 -top-2 max-md:-top-4 -right-8 max-md:right-1/2 max-md:translate-x-1/2 px-4 py-2 max-md:px-3 max-md:py-1 max-md:text-sm font-bricolage">
                                    BEST SELLER
                                </div>
                                <?php endif; ?>

                            <?php if ($menu['tersedia'] === 0): ?>
                                </div>
                            <?php endif ?>
                        </div>
                    </a>
                    <!-- END CARD MENU 1 -->
                <?php endwhile;?>
            <?php endif;?>
        </div>
    </section>
    <!-- END SEC SEMUA MENU -->

    <?php include './includes/guest/footer.php' ?>

</body>
</html>