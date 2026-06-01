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
<div
    id="backdrop"
    class="hidden fixed inset-0 bg-black/50 z-40 md:hidden"
></div>
<div class="flex h-screen">

    <!-- SIDEBAR -->
    <?php include './../../includes/admin/sidebar.php'; ?>
    

    <!-- CONTENT -->
    <div class="flex flex-col flex-1 h-screen min-w-0">

        <!-- HEADER -->
        <?php include './../../includes/admin/header.php'; ?>

        <!-- SCROLLABLE MAIN -->
        <main class="flex-1 overflow-y-auto p-8 space-y-8 max-md:p-4 max-md:space-y-4 min-w-0">
            <!-- Tabel -->
            <section class="bg-white border-4 border-black p-6 shadow-[8px_8px_0px_#000] max-md:mb-20">

                <div class="flex justify-between items-center mb-6">

                    <h3 class="font-bricolage text-3xl font-black">
                        SEMUA MENU
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
                                <th class="text-left p-4">ID</th>
                                <th class="text-left p-4">Nama Menu</th>
                                <th class="text-left p-4">Kategori</th>
                                <th class="text-left p-4">Harga</th>
                                <th class="text-left p-4">Status</th>
                                <th class="text-left p-4">Aksi</th>
                            </tr>

                        </thead>

                        <tbody>
                            <?php while($menu = $allMenus->fetch_assoc()): ?>

                            <tr class="border-b-2 border-black">

                                <td class="p-4">
                                    <?= $menu['id'] ?>
                                </td>

                                <td class="py-4">
                                    <div class="flex items-center gap-2 h-fit">
                                        <?= htmlspecialchars($menu['nama_menu']) ?>
                                        <?php if ($menu['terlaris'] === 1): ?>
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

                            <!-- Tinggal FIX RESPONSIVE MOBILE -->

                            <?php endwhile; ?>

                            </tbody>

                    </table>

                </div>

                <div class="relative flex gap-2 justify-between mt-8">
                    <!-- Previous -->
                    <?php if ($page > 1): ?>
                        <a
                            href="?page=<?= $page - 1 ?>"
                            class="flex items-center justify-center p-2 border-2 border-black bg-white"
                        >
                            <i class="bx bx-chevron-left text-lg"></i>
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
                            class="flex items-center justify-center p-2 border-2 border-black bg-white"
                        >
                            <i class="bx bx-chevron-right text-lg"></i>
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