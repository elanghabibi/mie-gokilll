<?php

session_start();
include './../config/koneksi.php';
include './../services/domain.php';

$kategori = $_GET['kategori'] ?? 'all';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);

$limit = 8;
$offset = ($page - 1) * $limit;

// Query count
if ($kategori === 'all') {
    $result = $conn->query("SELECT COUNT(*) as total FROM menu");
} else {
    $stmt = $conn->prepare("
        SELECT COUNT(*) as total
        FROM menu
        WHERE kategori = ?
    ");
    $stmt->bind_param('s', $kategori);
    $stmt->execute();
    $result = $stmt->get_result();
}

$totalData = $result->fetch_assoc()['total'];
$totalPages = ceil($totalData / $limit);

// Query data
if ($kategori === 'all') {
    $stmt = $conn->prepare("
        SELECT *
        FROM menu
        ORDER BY created_at DESC
        LIMIT ? OFFSET ?
    ");

    $stmt->bind_param('ii', $limit, $offset);
} else {
    $stmt = $conn->prepare("
        SELECT *
        FROM menu
        WHERE kategori = ?
        ORDER BY created_at DESC
        LIMIT ? OFFSET ?
    ");

    $stmt->bind_param('sii', $kategori, $limit, $offset);
}

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
    <style>
        #cart-floating-bar {
            position: fixed !important;
            bottom: 0 !important;
            left: 0 !important;
            right: 0 !important;
            background-color: #ffffff !important;
            border-top: 8px solid #000000 !important;
            padding: 24px !important;
            z-index: 99999 !important;
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
            transform: translateY(100%) !important;
            box-shadow: 0px -8px 0px rgba(0,0,0,0.15) !important;
        }
        #cart-floating-bar.active {
            transform: translateY(0) !important;
        }
    </style>
</head>
<body class="bg-gray-100 overflow-x-hidden">
    <?php include './../includes/guest/header.php' ?>

    <section class="px-20 py-8 max-md:p-4 space-y-4">
        <h2 class="flex items-center text-4xl gap-2"><i class="bx bx-dish"></i><span class="font-semibold font-bricolage italic">SEMUA MENU</span></h2>

        <div class="flex flex-wrap gap-3 mb-8">
            <a href="?kategori=all" class="px-2 py-1 font-space-mono border-2 border-black shadow-[3px_3px_0px_#000] transition-all text-sm hover:translate-x-1 hover:translate-y-1 hover:shadow-none <?= $kategori === 'all' ? 'bg-black text-white translate-x-1 translate-y-1 shadow-none' : 'bg-white text-black' ?>">Semua</a>
            <a href="?kategori=makanan" class="px-2 py-1 font-space-mono border-2 border-black shadow-[3px_3px_0px_#000] transition-all text-sm hover:translate-x-1 hover:translate-y-1 hover:shadow-none <?= $kategori === 'makanan' ? 'bg-black text-white translate-x-1 translate-y-1 shadow-none' : 'bg-white text-black' ?>">Makanan</a>
            <a href="?kategori=minuman" class="px-2 py-1 font-space-mono border-2 border-black shadow-[3px_3px_0px_#000] transition-all text-sm hover:translate-x-1 hover:translate-y-1 hover:shadow-none <?= $kategori === 'minuman' ? 'bg-black text-white translate-x-1 translate-y-1 shadow-none' : 'bg-white text-black' ?>">Minuman</a>
            <a href="?kategori=cemilan" class="px-2 py-1 font-space-mono border-2 border-black shadow-[3px_3px_0px_#000] transition-all text-sm hover:translate-x-1 hover:translate-y-1 hover:shadow-none <?= $kategori === 'cemilan' ? 'bg-black text-white translate-x-1 translate-y-1 shadow-none' : 'bg-white text-black' ?>">Cemilan</a>
        </div>

        <div class="grid grid-cols-4 max-md:grid-cols-2 w-full p-4 max-md:p-0 gap-6">
            <?php if($allMenus->num_rows > 0): ?>
                <?php while ($menu = $allMenus->fetch_assoc()): ?>
                
                <div class="relative h-full p-4 border-4 border-gray-950 bg-gray-50 shadow-[8px_8px_0px_#000] hover:translate-x-1 hover:translate-y-1 hover:shadow-none transition-all flex flex-col justify-between">
                    
                    <?php if ($menu['tersedia'] === 0): ?>
                        <div class="absolute inset-0 z-30 sold-out-cross pointer-events-none"></div>
                        <div class="absolute inset-0 z-20 flex items-center justify-center rotate-[-12deg]">
                            <div class="bg-red-600 text-white border-4 border-gray-950 font-black text-5xl max-md:text-xl px-8 py-2 max-md:px-5 uppercase tracking-tighter shadow-[8px_8px_0px_#000]">
                                HABIS!
                            </div>
                        </div>
                    <?php endif; ?>

                    <a href="<?= $domain . 'menu/detail.php?id=' . $menu['id'] ?>" class="block <?php echo ($menu['tersedia'] === 0) ? 'opacity-50 grayscale' : ''; ?>">
                        <div class="w-full aspect-square overflow-hidden shrink-0 border-2 border-black">
                            <img class="w-full h-full object-cover" src="<?= $menu['foto'] ? $domain . 'uploads/' . $menu['foto'] : $domain . 'src/img/placeholder-image.png' ?>" alt="<?= $menu['nama_menu'] ?>">
                        </div>
        
                        <div class="p-2 space-y-4">
                            <div class="space-y-2">
                                <h3 class="font-bricolage font-semibold text-2xl max-md:text-lg leading-6 text-gray-950"><?= $menu['nama_menu'] ?></h3>
                                <p class="text-xs text-gray-600"><?= ucfirst($menu['kategori']) ?></p>
                            </div>   
                            <p class="font-space-mono text-xl max-md:text-base leading-5 text-gray-950">Rp<?= number_format($menu['harga'], 0, ',', '.') ?></p>
                        </div>
                        
                        <?php if ($menu['terlaris'] === 1): ?>
                        <div class="absolute font-bold border-2 border-black bg-yellow-300 rotate-12 max-md:rotate-0 -top-2 max-md:-top-4 -right-4 max-md:right-1/2 max-md:translate-x-1/2 px-4 py-1 text-sm font-bricolage z-10">
                            TERLARIS
                        </div>
                        <?php endif; ?>
                    </a>

                    <div class="mt-4 z-10">
                        <?php if ($menu['tersedia'] > 0): ?>
                            <div class="flex items-center justify-between border-4 border-black bg-white font-space-mono overflow-hidden shadow-[4px_4px_0px_#000]">
                                <button type="button" class="btn-min px-4 py-2 bg-gray-100 hover:bg-gray-200 border-r-4 border-black font-black text-xl cursor-pointer select-none transition-colors" data-id="<?= $menu['id'] ?>">-</button>
                                <span class="qty-count font-black text-xl text-gray-950" id="qty-<?= $menu['id'] ?>">0</span>
                                <button type="button" class="btn-plus px-4 py-2 bg-gray-100 hover:bg-gray-200 border-l-4 border-black font-black text-xl cursor-pointer select-none transition-colors" data-id="<?= $menu['id'] ?>">+</button>
                            </div>
                        <?php else: ?>
                            <div class="text-center p-2 border-4 border-dashed border-gray-400 text-gray-400 font-space-mono text-sm uppercase font-bold">
                                Tidak Tersedia
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
                <?php endwhile;?>
            <?php endif;?>
        </div>

        <div class="relative flex gap-2 justify-between mt-8">
            <?php if ($page > 1): ?>
                <a href="?kategori=<?= urlencode($kategori) ?>&page=<?= $page - 1 ?>" class="flex items-center justify-center px-4 py-2 border-2 border-black bg-white shadow-[3px_3px_0px_#000] hover:translate-x-0.5 hover:translate-y-0.5 hover:shadow-none transition-all">
                    <i class="bx bx-chevron-left text-2xl"></i>
                </a>
            <?php else: ?>
                <div></div>
            <?php endif; ?>

            <?php
            $visiblePages = 4;
            $startPage = floor(($page - 1) / $visiblePages) * $visiblePages + 1;
            $endPage = min($startPage + $visiblePages - 1, $totalPages);
            ?>
            <div class="flex gap-2 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
                <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                    <a href="?kategori=<?= urlencode($kategori) ?>&page=<?= $i ?>" class="w-10 h-10 flex items-center justify-center border-2 border-black font-space-mono font-bold shadow-[3px_3px_0px_#000] hover:translate-x-0.5 hover:translate-y-0.5 hover:shadow-none transition-all <?= $i == $page ? 'bg-black text-white translate-x-0.5 translate-y-0.5 shadow-none' : 'bg-white text-black' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>

            <?php if ($page < $totalPages): ?>
                <a href="?kategori=<?= urlencode($kategori) ?>&page=<?= $page + 1 ?>" class="flex items-center justify-center px-4 py-2 border-2 border-black bg-white shadow-[3px_3px_0px_#000] hover:translate-x-0.5 hover:translate-y-0.5 hover:shadow-none transition-all">
                    <i class="bx bx-chevron-right text-2xl"></i>
                </a>
            <?php else: ?>
                <div></div>
            <?php endif; ?>
        </div>
    </section>
    <div id="cart-floating-bar">
        <div class="w-full mx-auto flex items-center justify-between px-12 max-md:px-4">
            
            <div class="text-3xl max-md:text-xl font-black font-bricolage text-black tracking-tight">
                Total Pesanan : <span id="total-qty">0</span>
            </div>
            
            <a href="<?= $domain . 'pesanan/keranjang.php' ?>" class="bg-white text-black border-4 border-black px-8 py-2 rounded-full font-black text-2xl max-md:text-lg shadow-[4px_4px_0px_#000] hover:translate-x-0.5 hover:translate-y-0.5 hover:shadow-none transition-all tracking-tight block">
                Buat Pesanan
            </a>

        </div>
    </div>

    <?php include './../includes/guest/footer.php' ?>
</body>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const cartBar = document.getElementById('cart-floating-bar');
        const totalQtyEl = document.getElementById('total-qty');

        function updateCartDOM() {
            // Ambil data dari key storage yang sama 'gokilll_cart'
            let cart = JSON.parse(localStorage.getItem('gokilll_cart')) || {};
            let totalItems = 0;

            // Reset semua angka di kartu menu halaman ini ke 0 terlebih dahulu
            document.querySelectorAll('.qty-count').forEach(span => {
                span.innerText = "0";
            });

            // Isi nilai counter masing-masing kartu menu berdasarkan isi localStorage
            Object.keys(cart).forEach(id => {
                totalItems += cart[id];
                
                const qtyLabel = document.getElementById(`qty-${id}`);
                if(qtyLabel) qtyLabel.innerText = cart[id];
            });

            // Memunculkan atau menyembunyikan floating bar
            if (totalItems > 0) {
                cartBar.classList.add('active');
            } else {
                cartBar.classList.remove('active');
            }

            totalQtyEl.innerText = totalItems;
        }

        // Jalankan sinkronisasi data saat halaman semua menu dibuka
        updateCartDOM();

        // Tombol Tambah (+)
        document.querySelectorAll('.btn-plus').forEach(button => {
            button.addEventListener('click', () => {
                let cart = JSON.parse(localStorage.getItem('gokilll_cart')) || {};
                const id = button.dataset.id;

                if (!cart[id]) {
                    cart[id] = 0;
                }
                cart[id]++;
                
                localStorage.setItem('gokilll_cart', JSON.stringify(cart));
                updateCartDOM();
            });
        });

        // Tombol Kurang (-)
        document.querySelectorAll('.btn-min').forEach(button => {
            button.addEventListener('click', () => {
                let cart = JSON.parse(localStorage.getItem('gokilll_cart')) || {};
                const id = button.dataset.id;

                if (cart[id] && cart[id] > 0) {
                    cart[id]--;
                    
                    if(cart[id] === 0) {
                        delete cart[id];
                    }
                    
                    localStorage.setItem('gokilll_cart', JSON.stringify(cart));
                    updateCartDOM();
                }
            });
        });
    });
</script>
</html>