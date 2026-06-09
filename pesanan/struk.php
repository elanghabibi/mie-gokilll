<?php 
session_start();

include './../config/koneksi.php';
include './../services/domain.php';



$id = (int) $_GET['id'];

if (!isset($_GET['id'])) {
    header("Location: " . $domain);
    exit;
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("
    SELECT *
    FROM pesanan
    WHERE id_pesanan = ?
");

$stmt->bind_param("i", $id);
$stmt->execute();

$pesanan = $stmt->get_result()->fetch_assoc();

$stmt_detail = $conn->prepare("
    SELECT dp.*, m.nama_menu
    FROM detail_pesanan dp
    JOIN menu m ON dp.menu_id = m.id
    WHERE dp.pesanan_id = ?
");

$stmt_detail->bind_param("i", $id);
$stmt_detail->execute();

$item_details = $stmt_detail->get_result();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil Dikirim! - Mie Gokilll</title>
    <link rel="stylesheet" href="./../src/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@700;800&amp;family=Inter:wght@400;500&amp;family=Space+Mono:wght@400;700&amp;display=swap" rel="stylesheet" />
    <link href="https://cdn.boxicons.com/3.0.8/fonts/basic/boxicons.min.css" rel="stylesheet">

    <style>
        @media print {
            body {
                background-color: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            nav, footer, .no-print {
                display: none !important;
            }
            .print-area {
                border: 2px dashed #000000 !important;
                box-shadow: none !important;
                margin: 0 auto !important;
                width: 100% !important;
                max-width: 100% !important;
                padding: 12px !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col justify-between">

    <div class="no-print">
        <?php include './../includes/guest/header.php' ?>
    </div>

    <main class="px-4 py-12 max-w-md mx-auto w-full flex-1 flex flex-col justify-center">
        
        <div class="print-area bg-white border-4 border-black p-6 shadow-[8px_8px_0px_#000] space-y-6 text-center relative overflow-hidden">
            
            <div class="absolute top-0 left-0 right-0 h-3 bg-green-400 border-b-4 border-black no-print"></div>

            <div class="pt-4 no-print">
                <div class="w-16 h-16 bg-green-100 border-4 border-black text-green-600 flex items-center justify-center rounded-full mx-auto text-3xl shadow-[3px_3px_0px_#000]">
                    <i class="bx bx-check-shield"></i>
                </div>
            </div>

            <div class="space-y-1">
                <h1 class="text-3xl font-black font-bricolage uppercase tracking-tight text-gray-950">MIE GOKILLL</h1>
                <p class="font-space-mono text-xs text-gray-500">Nota Pesanan Pelanggan Digital</p>
            </div>

            <div class="border-4 border-black bg-yellow-200 py-4 shadow-[4px_4px_0px_#000]">
                <p class="font-space-mono text-xs font-bold text-gray-700 uppercase tracking-wider">NO. MEJA</p>
                <p class="text-5xl font-black font-bricolage text-black tracking-tight mt-1">#<?= htmlspecialchars($pesanan['nomor_meja']); ?></p>
                
                <p class="font-space-mono text-[10px] text-gray-500 mt-1">Nota: <?= $pesanan['nota_pesanan']; ?></p>
                
            </div>

            <div class="text-left font-space-mono text-sm space-y-2 border-t-4 border-dashed border-black pt-4">
                <div class="flex justify-between">
                    <span class="text-gray-500">Pemesan:</span>
                    <span class="font-bold text-gray-950"><?= htmlspecialchars($pesanan['nama_pelanggan']); ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Pembayaran:</span>
                    <span class="font-bold uppercase px-2 py-0.5 bg-black text-white text-xs"><?= htmlspecialchars($pesanan['metode_pembayaran'] === 'kasir' ? 'TUNAI (KASIR)' : 'QRIS DANA'); ?></span>
                </div>
                <?php if(!empty($pesanan['catatan'])): ?>
                <div class="border-t border-gray-200 pt-2 text-xs">
                    <span class="text-gray-500 block">Catatan:</span>
                    <p class="text-gray-950 italic mt-0.5">"<?= htmlspecialchars($pesanan['catatan']); ?>"</p>
                </div>
                <?php endif; ?>
            </div>

            <div class="text-left font-space-mono text-xs border-t-4 border-dashed border-black pt-4 space-y-3">
                <p class="font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200 pb-1">Daftar Item Belanja:</p>
                
                <div class="space-y-2 h-fit pr-1">
                    <?php while($item = $item_details->fetch_assoc()): ?>
                        <div class="space-y-0.5">
                            <div class="font-bold text-gray-950 text-sm">
                                <?= htmlspecialchars($item['nama_menu']); ?>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span><?= $item['jumlah']; ?> x Rp<?= number_format($item['harga_satuan'], 0, ',', '.'); ?></span>
                                <span class="font-bold text-gray-900">Rp<?= number_format($item['subtotal'], 0, ',', '.'); ?></span>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <div class="flex justify-between font-black text-base text-black border-t-4 border-double border-black pt-3">
                    <span>TOTAL BELANJA:</span>
                    <span>Rp<?= number_format($pesanan['total_harga'], 0, ',', '.'); ?></span>
                </div>
            </div>

            <div class="pt-2 space-y-2 no-print">
                <button type="button" onclick="window.print()" class="w-full text-center bg-yellow-300 text-black border-4 border-black py-2.5 font-bricolage font-black text-md shadow-[4px_4px_0px_#000] hover:translate-x-0.5 hover:translate-y-0.5 hover:shadow-none transition-all uppercase block tracking-tight cursor-pointer">
                    <i class="bx bx-printer mr-1"></i> Cetak Struk Pesanan
                </button>
                
                <a href="<?= $domain; ?>" class="w-full text-center bg-black text-white border-4 border-black py-2.5 font-bricolage font-black text-md shadow-[4px_4px_0px_rgba(0,0,0,0.2)] hover:translate-x-0.5 hover:translate-y-0.5 hover:shadow-none transition-all uppercase block tracking-tight">
                    Pesan Menu Lain Lagi
                </a>
            </div>

        </div>
    </main>

    <div class="no-print">
        <?php include './../includes/guest/footer.php' ?>
    </div>

</body>
</html>