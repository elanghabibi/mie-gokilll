<?php
session_start();

include './../../services/domain.php';
include './../../services/auth-nologin.php';
include './../../services/helpers.php';
include "./../../config/koneksi.php";

?>

<?php 

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);

$limit = 8;
$offset = ($page - 1) * $limit;

$stmt = $conn->prepare("
        SELECT COUNT(*) as total
        FROM menu
    ");
$stmt->execute();
$result = $stmt->get_result();

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

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Menu - Mie Gokilll</title>
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
                class="block p-4 border-4 border-black bg-white shadow-[6px_6px_0px_#000] hover:translate-x-1 hover:translate-y-1 hover:shadow-none transition-all "
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
                class="block p-4 border-4 border-black bg-black text-white shadow-[6px_6px_0px_#000] hover:translate-x-1 hover:translate-y-1 hover:shadow-none transition-all"
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
            <!-- Tabel -->
            <section class="bg-white border-4 border-black p-6 shadow-[8px_8px_0px_#000]">

                <div class="flex justify-between items-center mb-6">

                    <h3 class="font-bricolage text-3xl font-black">
                        SEMUA MENU
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
                        <?php while($menu = $allMenus->fetch_assoc()): ?>

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

                        <!-- Tinggal FIX RESPONSIVE MOBILE -->

                        <?php endwhile; ?>

                        </tbody>

                </table>
                <div class="relative flex gap-2 justify-between mt-8">
            <!-- Previous -->
            <?php if ($page > 1): ?>
                <a
                    href="?page=<?= $page - 1 ?>"
                    class="flex items-center justify-center px-4 py-2 border-2 border-black bg-white"
                >
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
            <!-- Nomor Halaman -->
            <div class="flex gap-2 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
                <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                    <a
                        href="?page=<?= $i ?>"
                        class="w-10 h-10 flex items-center justify-center border-2 border-black
                        <?= $i == $page ? 'bg-black text-white' : 'bg-white' ?>"
                    >
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>

            <!-- Next -->
            <?php if ($page < $totalPages): ?>
                <a
                    href="?page=<?= $page + 1 ?>"
                    class="flex items-center justify-center px-4 py-2 border-2 border-black bg-white"
                >
                    <i class="bx bx-chevron-right text-2xl"></i>
                </a>
            <?php else: ?>
                <div></div>
            <?php endif; ?>

        </div>

            </section>

        </main>

    </div>

</div>
<?php include './../../includes/toast.php'; ?>
</body>
<script src="./../../src/js/script.js"></script>
</html>