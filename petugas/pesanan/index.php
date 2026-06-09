<?php
session_start();

include './../../services/domain.php';
include './../../services/middleware/petugas.php';
include './../../services/helpers.php';
include "./../../config/koneksi.php";

?>

<?php 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);
$statusFilter = $_GET['status'] ?? 'all';

if ($statusFilter !== 'all') {

    $stmt = $conn->prepare("
        SELECT COUNT(*) as total
        FROM pesanan
        WHERE status = ?
    ");

    $stmt->bind_param("s", $statusFilter);

} else {

    $stmt = $conn->prepare("
        SELECT COUNT(*) as total
        FROM pesanan
    ");

}

$limit = 8;
$offset = ($page - 1) * $limit;

$stmt->execute();
$result = $stmt->get_result();

$totalData = $result->fetch_assoc()['total'];
$totalPages = ceil($totalData / $limit);

if ($statusFilter !== 'all') {

    $stmt = $conn->prepare("
        SELECT *
        FROM pesanan
        WHERE status = ?
        ORDER BY created_at DESC
        LIMIT ? OFFSET ?
    ");

    $stmt->bind_param(
        "sii",
        $statusFilter,
        $limit,
        $offset
    );

} else {

    $stmt = $conn->prepare("
        SELECT *
        FROM pesanan
        ORDER BY created_at DESC
        LIMIT ? OFFSET ?
    ");

    $stmt->bind_param(
        "ii",
        $limit,
        $offset
    );

}

$stmt->execute();

$allPesanan = $stmt->get_result();
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
            <!-- Tabel -->
            <section class="bg-white border-4 border-black p-6 shadow-[8px_8px_0px_#000] max-md:mb-20">

                <div class="flex justify-between items-center mb-6">

                    <h3 class="font-bricolage text-3xl font-black">
                        SEMUA PESANAN
                    </h3>

                    <a href="<?= $domain . 'petugas/menu/tambah.php' ?>"
                        class="px-4 py-2 bg-black text-white border-4 border-black shadow-[4px_4px_0px_#000] hover:translate-x-1 hover:translate-y-1 hover:shadow-none transition-all"
                    >
                        <i class="bx bx-plus"></i>
                    </a>

                </div>

                <div class="flex flex-wrap gap-2 mb-6">

                    <a
                        href="?status=all"
                        class="px-3 py-2 border-2 border-black <?= $statusFilter == 'all' ? 'bg-black text-white' : 'bg-white' ?>"
                    >
                        Semua
                    </a>

                    <a
                        href="?status=menunggu_konfirmasi"
                        class="px-3 py-2 border-2 border-black <?= $statusFilter == 'menunggu_konfirmasi' ? 'bg-yellow-300' : 'bg-white' ?>"
                    >
                        Menunggu
                    </a>

                    <a
                        href="?status=diproses"
                        class="px-3 py-2 border-2 border-black <?= $statusFilter == 'diproses' ? 'bg-blue-300' : 'bg-white' ?>"
                    >
                        Diproses
                    </a>

                    <a
                        href="?status=selesai"
                        class="px-3 py-2 border-2 border-black <?= $statusFilter == 'selesai' ? 'bg-green-300' : 'bg-white' ?>"
                    >
                        Selesai
                    </a>

                </div>

                <div class="w-full overflow-x-auto">
                    <table class="min-w-[900px] w-max text-sm text-left">

                        <thead>
                            <tr class="border-b-4 border-black">
                                <th class="text-left p-4">ID</th>
                                <th class="text-left p-4">Nota</th>
                                <th class="text-left p-4">Pemesan</th>
                                <th class="text-left p-4">Meja</th>
                                <th class="text-left p-4">Total</th>
                                <th class="text-left p-4">Pembayaran</th>
                                <th class="text-left p-4">Status</th>
                                <th class="text-left p-4">Tanggal</th>
                                <th class="text-left p-4">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php while($pesanan = $allPesanan->fetch_assoc()): ?>

                            <tr class="border-b-2 border-black">

                                <td class="p-4">
                                    <?= $pesanan['id_pesanan'] ?>
                                </td>

                                <td class="p-4 font-space-mono">
                                    <?= htmlspecialchars($pesanan['nota_pesanan']) ?>
                                </td>

                                <td class="p-4">
                                    <?= htmlspecialchars($pesanan['nama_pelanggan']) ?>
                                </td>

                                <td class="p-4">
                                    <?= $pesanan['nomor_meja'] ?: '-' ?>
                                </td>

                                <td class="p-4">
                                    Rp<?= number_format($pesanan['total_harga'], 0, ',', '.') ?>
                                </td>

                                <td class="p-4">
                                    <?= strtoupper($pesanan['metode_pembayaran']) ?>
                                </td>

                                <td class="p-4">

                                    <?php if($pesanan['status'] == 'menunggu_konfirmasi'): ?>
                                        <span class="px-2 py-1 border-2 border-black bg-yellow-200">
                                            Menunggu
                                        </span>

                                    <?php elseif($pesanan['status'] == 'diproses'): ?>
                                        <span class="px-2 py-1 border-2 border-black bg-blue-200">
                                            Diproses
                                        </span>

                                    <?php elseif($pesanan['status'] == 'selesai'): ?>
                                        <span class="px-2 py-1 border-2 border-black bg-green-200">
                                            Selesai
                                        </span>

                                    <?php else: ?>
                                        <span class="px-2 py-1 border-2 border-black bg-red-200">
                                            Dibatalkan
                                        </span>
                                    <?php endif; ?>

                                </td>

                                <td class="p-4">
                                    <?= date('d M Y H:i', strtotime($pesanan['created_at'])) ?>
                                </td>

                                <td class="p-4">
                                    <div class="flex items-center gap-2">

                                        <a
                                            href="<?= $domain . 'petugas/pesanan/struk.php?id=' . $pesanan['id_pesanan'] ?>"
                                            class="flex items-center justify-center bg-white border-black border-2 p-1 w-fit h-fit"
                                        >
                                            <i class="bx bx-info-circle"></i>
                                        </a>
                                    </div>
                                </td>

                            </tr>

                            <?php endwhile; ?>

                            </tbody>

                    </table>

                </div>

                <div class="relative flex gap-2 justify-between mt-8">
                    <!-- Previous -->
                    <?php if ($page > 1): ?>
                        <a
                            href="?page=<?= $page - 1 ?>&status=<?= $statusFilter ?>"
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
                                href="?page=<?= $i ?>&status=<?= $statusFilter ?>"
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
                            href="?page=<?= $page + 1 ?>&status=<?= $statusFilter ?>"
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