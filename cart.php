<?php
session_start();
include './config/koneksi.php';
include './services/domain.php';

// Ambil semua data menu dari database
$query = $conn->query("SELECT id, nama_menu, harga, foto FROM menu");
$menuDb = [];
while ($row = $query->fetch_assoc()) {
    $menuDb[$row['id']] = [
        'nama' => $row['nama_menu'],
        'harga' => $row['harga'],
        'foto' => $row['foto'] ? $domain . 'uploads/' . $row['foto'] : $domain . 'src/img/placeholder-image.png'
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mie Gokilll - Buat Pesanan</title>
    <link rel="stylesheet" href="./src/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@700;800&amp;family=Inter:wght@400;500&amp;family=Space+Mono:wght@400;700&amp;display=swap" rel="stylesheet" />
    <link href="https://cdn.boxicons.com/3.0.8/fonts/basic/boxicons.min.css" rel="stylesheet">
    
    <style>
        /* Styling Pop-up Modal Brutalism */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 100000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }
        .modal-box {
            background-color: #ffffff;
            border: 6px solid #000000;
            padding: 24px;
            max-width: 450px;
            width: 100%;
            box-shadow: 8px 8px 0px #000000;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen pb-20">
    
    <?php include './includes/guest/header.php' ?>

    <section class="px-20 py-8 max-md:p-4 max-w-5xl mx-auto space-y-6">
        <h1 class="text-4xl font-black font-bricolage italic uppercase tracking-tight text-gray-950 flex items-center gap-2">
            <a href="<?= $domain ?>" class="border-2 border-black bg-white p-1 hover:shadow-none shadow-[2px_2px_0px_#000] transition-all"><i class="bx bx-chevron-left text-2xl flex"></i></a>
            <span>Detail Pesanan Kamu</span>
        </h1>

        <div id="empty-cart" class="hidden border-4 border-dashed border-gray-400 p-12 text-center bg-white shadow-[8px_8px_0px_rgba(0,0,0,0.05)]">
            <p class="font-space-mono text-xl text-gray-500 font-bold uppercase">Keranjang kamu masih kosong gann! 😮‍💨</p>
            <a href="<?= $domain ?>" class="inline-block mt-4 bg-yellow-300 border-4 border-black font-bricolage font-black px-6 py-2 shadow-[4px_4px_0px_#000] hover:translate-x-0.5 hover:translate-y-0.5 hover:shadow-none transition-all">LIHAT MENU MIE</a>
        </div>

        <div id="main-cart-layout" class="grid grid-cols-3 max-md:grid-cols-1 gap-8 items-start">
            
            <div class="col-span-2 space-y-4" id="cart-items-container"></div>

            <div class="bg-white border-4 border-black p-6 shadow-[8px_8px_0px_#000] space-y-6">
                <h2 class="text-2xl font-black font-bricolage border-b-4 border-black pb-2 uppercase tracking-tight">Data Pemesan</h2>
                
                <form action="<?= $domain.  "pesanan/proses-pesanan.php" ?>" method="POST" id="form-checkout" class="space-y-4">
                    <input type="hidden" name="items_json" id="items_json">

                    <div class="flex flex-col gap-1">
                        <label for="nama_pelanggan" class="font-space-mono font-bold text-sm">NAMA KAMU :</label>
                        <input type="text" name="nama_pelanggan" id="nama_pelanggan" required class="border-4 border-black p-2 font-space-mono bg-gray-50 focus:bg-white transition-colors" placeholder="Contoh: Budi Slebew">
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="nomor_meja" class="font-space-mono font-bold text-sm">NOMOR MEJA :</label>
                        <input type="number" name="nomor_meja" id="nomor_meja" required class="border-4 border-black p-2 font-space-mono bg-gray-50 focus:bg-white transition-colors" placeholder="Contoh: 05">
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="catatan" class="font-space-mono font-bold text-sm">CATATAN (OPSIONAL) :</label>
                        <textarea name="catatan" id="catatan" rows="2" class="border-4 border-black p-2 font-space-mono bg-gray-50 focus:bg-white transition-colors" placeholder="Contoh: Pedas gila bang!"></textarea>
                    </div>

                    <div class="flex flex-col gap-2 pt-2">
                        <label class="font-space-mono font-bold text-sm">METODE PEMBAYARAN :</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" id="pay-cash-btn" class="border-4 border-black bg-white p-3 font-space-mono font-bold text-center shadow-[4px_4px_0px_#000] hover:translate-1 hover:shadow-none transition-all cursor-pointer">
                                KASIR (LANGSUNG)
                            </button>
                            <button type="button" id="pay-qris-btn" class="border-4 border-black bg-white p-3 font-space-mono font-bold text-center shadow-[4px_4px_0px_#000] hover:translate-1 hover:shadow-none transition-all cursor-pointer">
                                QRIS (OTOMATIS)
                            </button>
                        </div>
                        <input type="hidden" name="metode_pembayaran" id="metode_pembayaran" required>
                    </div>

                    <div class="border-t-4 border-dashed border-black pt-4 space-y-2">
                        <div class="flex justify-between font-space-mono font-bold text-gray-600">
                            <span>Total Item:</span>
                            <span id="summary-qty">0 Item</span>
                        </div>
                        <div class="flex justify-between font-bricolage font-black text-2xl text-black">
                            <span>TOTAL :</span>
                            <span>Rp<span id="summary-total">0</span></span>
                        </div>
                    </div>

                    <button type="submit" id="btn-submit-order" disabled class="w-full text-center bg-gray-300 text-gray-500 border-4 border-gray-400 py-3 font-black text-xl transition-all uppercase tracking-tight cursor-not-allowed">
                        Buat Pesanan
                    </button>
                </form>
            </div>

        </div>
    </section>

    <div id="modal-cash" class="modal-overlay" style="display: none;">
        <div class="modal-box space-y-4">
            <h3 class="text-2xl font-black font-bricolage border-b-4 border-black pb-1 uppercase">Konfirmasi Kasir</h3>
            <p class="font-space-mono text-sm text-gray-700">Silakan bayar uang tunai sebesar <strong class="text-black">Rp<span class="modal-total-txt">0</span></strong> langsung ke meja kasir.</p>
            <div class="flex flex-col gap-1 pt-2">
                <label class="font-space-mono font-bold text-xs text-red-600">PIN RAHASIA KASIR:</label>
                <input type="password" id="pin-kasir-input" class="border-4 border-black p-2 font-space-mono text-center text-lg tracking-widest" placeholder="••••">
                <span id="cash-error-msg" class="text-xs text-red-600 font-bold font-space-mono" style="display: none;">Kode Salah! Khusus Petugas Kasir.</span>
            </div>
            <div class="grid grid-cols-2 gap-3 pt-2">
                <button type="button" id="close-cash-btn" class="border-4 border-black bg-gray-100 font-space-mono font-bold p-2 cursor-pointer">Batal</button>
                <button type="button" id="verify-cash-btn" class="border-4 border-black bg-green-400 font-space-mono font-black p-2 cursor-pointer">Verifikasi</button>
            </div>
        </div>
    </div>

    <div id="modal-qris" class="modal-overlay" style="display: none;">
        <div class="modal-box space-y-4 text-center">
            <h3 class="text-2xl font-black font-bricolage border-b-4 border-black pb-1 uppercase">Scan QRIS Dana</h3>
            <p class="font-space-mono text-sm text-gray-700">Total Tagihan: <span class="font-bold text-black text-lg">Rp<span class="modal-total-txt">0</span></span></p>
            
            <div class="w-56 h-56 mx-auto border-4 border-black bg-white p-2 flex items-center justify-center">
                <img id="qris-image-src" src="" class="w-full h-full object-contain" alt="QRIS DANA MIE GOKILLL">
            </div>
            
            <p class="text-xs font-space-mono text-gray-500 italic">Nomer DANA Kasir: 087895937237</p>
            <div class="pt-2">
                <button type="button" id="confirm-qris-paid" class="w-full border-4 border-black bg-green-400 font-bricolage font-black p-3 text-lg cursor-pointer shadow-[4px_4px_0px_#000]">SAYA SUDAH TRANSFER</button>
                <button type="button" id="close-qris-btn" class="mt-2 text-sm text-gray-500 underline block mx-auto cursor-pointer font-space-mono">Batalkan Pembayaran</button>
            </div>
        </div>
    </div>

    <?php include './includes/guest/footer.php' ?>

    <script>
        const menuDatabase = <?php echo json_encode($menuDb); ?>;
        let globalTotalPrice = 0;

        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('cart-items-container');
            const emptyCartEl = document.getElementById('empty-cart');
            const mainLayoutEl = document.getElementById('main-cart-layout');
            const summaryQtyEl = document.getElementById('summary-qty');
            const summaryTotalEl = document.getElementById('summary-total');
            const itemsJsonInput = document.getElementById('items_json');
            
            const methodInput = document.getElementById('metode_pembayaran');
            const submitBtn = document.getElementById('btn-submit-order');

            // Selektor Tombol & Modal Pembayaran
            const cashBtn = document.getElementById('pay-cash-btn');
            const qrisBtn = document.getElementById('pay-qris-btn');
            const modalCash = document.getElementById('modal-cash');
            const modalQris = document.getElementById('modal-qris');

            function formatRupiah(num) {
                return new Intl.NumberFormat('id-ID').format(num);
            }

            function renderCart() {
                let cart = JSON.parse(localStorage.getItem('gokilll_cart')) || {};
                let itemIds = Object.keys(cart);
                
                if (itemIds.length === 0) {
                    emptyCartEl.classList.remove('hidden');
                    mainLayoutEl.classList.add('hidden');
                    return;
                }

                emptyCartEl.classList.add('hidden');
                mainLayoutEl.classList.remove('hidden');
                container.innerHTML = "";

                let totalQty = 0;
                globalTotalPrice = 0;

                itemIds.forEach(id => {
                    const qty = cart[id];
                    const itemData = menuDatabase[id];

                    if (itemData) {
                        totalQty += qty;
                        globalTotalPrice += qty * itemData.harga;

                        container.innerHTML += `
                            <div class="bg-white border-4 border-black p-4 shadow-[4px_4px_0px_#000] flex gap-4 items-center relative">
                                <div class="w-24 h-24 shrink-0 border-2 border-black overflow-hidden bg-gray-100">
                                    <img src="${itemData.foto}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 space-y-1">
                                    <h3 class="font-bricolage font-black text-xl tracking-tight text-gray-950">${itemData.nama}</h3>
                                    <p class="font-space-mono text-sm text-gray-600">Rp${formatRupiah(itemData.harga)} x ${qty}</p>
                                    <p class="font-space-mono font-bold text-md text-gray-950">Subtotal: Rp${formatRupiah(itemData.harga * qty)}</p>
                                </div>
                                <button type="button" class="absolute top-2 right-2 text-gray-400 hover:text-red-600 font-bold text-xl cursor-pointer" onclick="hapusItemDariCart('${id}')">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        `;
                    }
                });

                summaryQtyEl.innerText = `${totalQty} Item`;
                summaryTotalEl.innerText = formatRupiah(globalTotalPrice);
                document.querySelectorAll('.modal-total-txt').forEach(el => el.innerText = formatRupiah(globalTotalPrice));
                itemsJsonInput.value = JSON.stringify(cart);
            }

            window.hapusItemDariCart = function(id) {
                let cart = JSON.parse(localStorage.getItem('gokilll_cart')) || {};
                if (cart[id]) {
                    delete cart[id];
                    localStorage.setItem('gokilll_cart', JSON.stringify(cart));
                    renderCart();
                    resetPaymentState();
                }
            };

            renderCart();

            // --- FUNGSI RESET METODE PEMBAYARAN ---
            function resetPaymentState() {
                methodInput.value = "";
                cashBtn.className = "border-4 border-black bg-white p-3 font-space-mono font-bold text-center shadow-[4px_4px_0px_#000] transition-all cursor-pointer";
                qrisBtn.className = "border-4 border-black bg-white p-3 font-space-mono font-bold text-center shadow-[4px_4px_0px_#000] transition-all cursor-pointer";
                submitBtn.disabled = true;
                submitBtn.className = "w-full text-center bg-gray-300 text-gray-500 border-4 border-gray-400 py-3 font-black text-xl uppercase tracking-tight cursor-not-allowed";
                submitBtn.innerText = "Buat Pesanan";
            }

            // --- LOGIKA AKSI 1: KASIR LANGSUNG ---
            cashBtn.addEventListener('click', () => {
                modalCash.style.display = "flex";
            });
            document.getElementById('close-cash-btn').addEventListener('click', () => {
                modalCash.style.display = "none";
                document.getElementById('pin-kasir-input').value = "";
                document.getElementById('cash-error-msg').style.display = "none";
            });

            document.getElementById('verify-cash-btn').addEventListener('click', () => {
                const pinInput = document.getElementById('pin-kasir-input').value;
                if (pinInput === 'BaYaR') {
                    modalCash.style.display = "none";
                    methodInput.value = "kasir";
                    
                    cashBtn.className = "border-4 border-black bg-black text-white p-3 font-space-mono font-bold text-center shadow-none translate-x-1 translate-y-1 transition-all";
                    qrisBtn.className = "border-4 border-black bg-white text-gray-400 p-3 font-space-mono font-bold text-center opacity-40 shadow-none pointer-events-none";
                    
                    enableSubmitButton();
                } else {
                    document.getElementById('cash-error-msg').style.display = "block";
                }
            });

            // --- LOGIKA AKSI 2: SCAN QRIS DANA ---
            qrisBtn.addEventListener('click', () => {
                const qrisUrl = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=087895937237?amount=${globalTotalPrice}`;
                document.getElementById('qris-image-src').src = qrisUrl;
                modalQris.style.display = "flex";
            });
            document.getElementById('close-qris-btn').addEventListener('click', () => {
                modalQris.style.display = "none";
            });

            document.getElementById('confirm-qris-paid').addEventListener('click', () => {
                modalQris.style.display = "none";
                methodInput.value = "qris";

                qrisBtn.className = "border-4 border-black bg-black text-white p-3 font-space-mono font-bold text-center shadow-none translate-x-1 translate-y-1 transition-all";
                cashBtn.className = "border-4 border-black bg-white text-gray-400 p-3 font-space-mono font-bold text-center opacity-40 shadow-none pointer-events-none";

                enableSubmitButton();
            });

            function enableSubmitButton() {
                submitBtn.disabled = false;
                submitBtn.className = "w-full text-center bg-green-400 hover:bg-green-500 text-black border-4 border-black py-3 font-black text-xl shadow-[4px_4px_0px_#000] hover:translate-x-0.5 hover:translate-y-0.5 hover:shadow-none transition-all cursor-pointer uppercase tracking-tight block";
                submitBtn.innerText = "Kirim Pesanan Ke Dapur";
            }

            document.getElementById('form-checkout').addEventListener('submit', () => {
                setTimeout(() => {
                    localStorage.removeItem('gokilll_cart');
                }, 100);
            });
        });
    </script>
</body>
</html>