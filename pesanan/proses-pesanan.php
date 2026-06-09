
<?php
// 1. AKTIFKAN PELACAK ERROR PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include './../config/koneksi.php';
include './../services/domain.php';

// Pastikan data dikirim melalui metode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . $domain);
    exit;
}

// Ambil data dari form cart.php
$nama_pelanggan = mysqli_real_escape_string($conn, $_POST['nama_pelanggan']);
$nomor_meja     = (int)$_POST['nomor_meja'];
$catatan        = mysqli_real_escape_string($conn, $_POST['catatan']);
$metode_bayar   = mysqli_real_escape_string($conn, $_POST['metode_pembayaran']); 
$items_json     = $_POST['items_json'];

// Dekode JSON keranjang belanja dari localStorage
$cart_items = json_decode($items_json, true);

if (empty($cart_items)) {
    echo "<script>alert('Keranjang belanja kosong!'); window.location.href='".$domain."';</script>";
    exit;
}

// 2. HITUNG TOTAL HARGA SECARA VALID DARI SERVER
$total_harga = 0;
$item_details = [];

foreach ($cart_items as $id => $qty) {
    $id = (int)$id;
    $qty = (int)$qty;
    
    $query_menu = $conn->query("SELECT nama_menu, harga FROM menu WHERE id = $id");
    if ($query_menu->num_rows > 0) {
        $menu = $query_menu->fetch_assoc();
        $subtotal = $menu['harga'] * $qty;
        $total_harga += $subtotal;
        
        $item_details[] = [
            'menu_id' => $id,
            'nama_menu' => $menu['nama_menu'],
            'harga' => $menu['harga'],
            'qty' => $qty,
            'subtotal' => $subtotal
        ];
    }
}

// 3. DETEKSI OTOMATIS STRUKTUR KOLOM TABEL PESANAN (PERISAI ANTI-ERROR)
$kolom_metode = '';
$ada_kolom_nota = false;

$check_columns = $conn->query("SHOW COLUMNS FROM pesanan");
while ($col = $check_columns->fetch_assoc()) {
    if ($col['Field'] === 'metode_pembayaran') {
        $kolom_metode = 'metode_pembayaran';
    } elseif ($col['Field'] === 'metode_bayar') {
        $kolom_metode = 'metode_bayar';
    }

    if ($col['Field'] === 'nota_pesanan') { $ada_kolom_nota = true; }
    
}

if (empty($kolom_metode)) {
    $conn->query("ALTER TABLE pesanan ADD COLUMN metode_pembayaran VARCHAR(50) NOT NULL AFTER total_harga");
    $kolom_metode = 'metode_pembayaran';
}


// 4. GENERATOR KODE NOTA UNIK OTOMATIS
$nota_val = "";
if ($ada_kolom_nota) {
    $nota_val = "MG" . date('Ymd-His') . rand(10, 99);
}

// 5. AUTO-CREATE TABEL 'DETAIL_PESANAN' JIKA BELUM ADA
$conn->query("
    CREATE TABLE IF NOT EXISTS detail_pesanan (
        id INT AUTO_INCREMENT PRIMARY KEY,
        pesanan_id INT NOT NULL,
        menu_id INT NOT NULL,
        jumlah INT NOT NULL,
        harga_satuan INT NOT NULL,
        subtotal INT NOT NULL
    )
");

// 6. PROSES SIMPAN KE TABEL UTAMA 'PESANAN'
if ($ada_kolom_nota) {
    $query_insert = "INSERT INTO pesanan (nama_pelanggan, nomor_meja, total_harga, $kolom_metode, catatan, nota_pesanan, status, created_at) VALUES (?, ?, ?, ?, ?, ?, 'menunggu_konfirmasi', NOW())";
    $stmt = $conn->prepare($query_insert);
    if ($stmt === false) { die("Gagal Query Pesanan: " . $conn->error); }
    $stmt->bind_param("siisss", $nama_pelanggan, $nomor_meja, $total_harga, $metode_bayar, $catatan, $nota_val);
} else {
    $query_insert = "INSERT INTO pesanan (nama_pelanggan, nomor_meja, total_harga, $kolom_metode, catatan, status, created_at) VALUES (?, ?, ?, ?, ?, 'menunggu_konfirmasi', NOW())";
    $stmt = $conn->prepare($query_insert);
    if ($stmt === false) { die("Gagal Query Pesanan: " . $conn->error); }
    $stmt->bind_param("siiss", $nama_pelanggan, $nomor_meja, $total_harga, $metode_bayar, $catatan);
}

$stmt->execute();
$pesanan_id = $conn->insert_id;

// 7. PROSES SIMPAN RINCIAN ITEM KE TABEL 'DETAIL_PESANAN'
foreach ($item_details as $item) {
    $stmt_detail = $conn->prepare("INSERT INTO detail_pesanan (pesanan_id, menu_id, jumlah, harga_satuan, subtotal) VALUES (?, ?, ?, ?, ?)");
    if ($stmt_detail === false) { die("Gagal Query Detail Pesanan: " . $conn->error); }
    $stmt_detail->bind_param("iiiii", $pesanan_id, $item['menu_id'], $item['qty'], $item['harga'], $item['subtotal']);
    $stmt_detail->execute();
}

header("Location: " . $domain . "pesanan/struk.php?id=".$pesanan_id);
exit;

// $nomor_antrean = str_pad($pesanan_id, 3, "0", STR_PAD_LEFT);
?>
