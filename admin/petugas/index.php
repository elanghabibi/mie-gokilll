<?php
// 1. AKTIFKAN PELACAK ERROR PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

include './../../services/domain.php';
include './../../services/middleware/admin.php';
include './../../services/helpers.php';
include "./../../config/koneksi.php";

$conn->query("
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        nama_lengkap VARCHAR(100) NOT NULL,
        role VARCHAR(20) NOT NULL DEFAULT 'kasir'
    )
");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_petugas'])) {
    $username     = mysqli_real_escape_string($conn, $_POST['username']);
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $password     = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role         = mysqli_real_escape_string($conn, $_POST['role']);

    // Cek dulu apakah username sudah dipakai orang lain
    $cek_username = $conn->query("SELECT id FROM users WHERE username = '$username'");

    if ($cek_username->num_rows > 0) {
        $_SESSION['error'] = "Username sudah terdaftar, gunakan username lain.";
    } else {
        $stmt_insert = $conn->prepare("
            INSERT INTO users (username, password, nama_lengkap, role)
            VALUES (?, ?, ?, ?)
        ");

        $stmt_insert->bind_param(
            "ssss",
            $username,
            $password,
            $nama_lengkap,
            $role
        );

        if ($stmt_insert->execute()) {
            header("Location: " . $domain . "admin/petugas/?status=tersimpan");
            exit;
        } else {
            $_SESSION['error'] = "Gagal mendaftarkan petugas baru.";
        }
    }
}

// Hapus User
if (isset($_GET['aksi']) && $_GET['aksi'] === 'hapus' && isset($_GET['id'])) {
    $id_hapus = (int)$_GET['id'];
    
    // Cegah admin menghapus akunnya sendiri yang sedang dipakai login
    $akun_target = $conn->query("SELECT username FROM users WHERE id = $id_hapus")->fetch_assoc();
    if ($akun_target && $akun_target['username'] === $_SESSION['user']) {
        $_SESSION['error'] = "Akun sendiri tidak dapat dihapus!";
    } else {
        $delete = $conn->query("DELETE FROM users WHERE id = $id_hapus");
        if ($delete) {
            header("Location: " . $domain . "admin/petugas/?status=terhapus");
            exit;
        } else {
            $_SESSION['error'] = "Gagal menghapus petugas!";
        }
    }
}

// Cek kiriman notifikasi status dari redirect
if (isset($_GET['status']) && $_GET['status'] === 'tersimpan') $_SESSION['success'] = "Petugas baru berhasil direkrut masuk tim!";
if (isset($_GET['status']) && $_GET['status'] === 'terhapus') $_SESSION['success'] = "Akun petugas berhasil dihapus dari sistem!";

// Ambil semua daftar akun petugas untuk dimasukkan ke tabel
$all_staff = $conn->query("SELECT * FROM users ORDER BY id DESC");
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
            <div class="space-y-2">
            <h1 class="text-4xl font-black font-bricolage uppercase tracking-tight text-gray-950">MANAJEMEN AKUN PETUGAS</h1>
            <p class="font-space-mono text-sm text-gray-500">Daftarkan akun kasir atau kru dapur baru agar bisa mengelola pesanan Mie Gokilll!</p>
        </div>

        <div class="grid grid-cols-3 max-md:grid-cols-1 gap-8 items-start">
            
            <div class="bg-white border-4 border-black p-6 shadow-[8px_8px_0px_#000] space-y-4 sticky top-6">
                <h2 class="text-xl font-black font-bricolage uppercase tracking-tight border-b-4 border-black pb-2 text-gray-950">
                    <i class="bx bx-user-plus align-middle mr-1"></i> Registrasi Staff
                </h2>
                
                <form action="" method="POST" class="space-y-4 font-space-mono text-xs">
                    <div class="flex flex-col gap-1">
                        <label class="font-bold text-gray-700">NAMA LENGKAP :</label>
                        <input type="text" name="nama_lengkap" required placeholder="Contoh: Ahmad Kasir" class="border-4 border-black p-2 bg-gray-50 focus:bg-white text-sm font-bold">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="font-bold text-gray-700">USERNAME LOGIN :</label>
                        <input type="text" name="username" required placeholder="ahmad123" class="border-4 border-black p-2 bg-gray-50 focus:bg-white text-sm font-bold">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="font-bold text-gray-700">PASSWORD AKSES :</label>
                        <input type="password" name="password" required placeholder="Buat password kuat" class="border-4 border-black p-2 bg-gray-50 focus:bg-white text-sm font-bold">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="font-bold text-gray-700">DIVISI / JABATAN :</label>
                        <select name="role" required class="border-4 border-black p-2 bg-gray-50 focus:bg-white text-sm font-bold">
                            <option value="">-- Pilih Role --</option>
                            <option value="admin">Administrator Utama</option>
                            <option value="petugas">Petugas</option>
                        </select>
                    </div>

                    <button type="submit" name="tambah_petugas" class="w-full bg-black text-white border-4 border-black py-2.5 font-bricolage font-black text-md shadow-[4px_4px_0px_rgba(0,0,0,0.2)] hover:translate-x-0.5 hover:translate-y-0.5 hover:shadow-none transition-all uppercase block tracking-tight cursor-pointer">
                        Aktifkan Akun Staff
                    </button>
                </form>
            </div>

            <div class="col-span-2 bg-white border-4 border-black p-6 shadow-[8px_8px_0px_#000] overflow-x-auto">
                <h2 class="text-xl font-black font-bricolage uppercase tracking-tight border-b-4 border-black pb-2 mb-4 text-gray-950">
                    <i class="bx bx-group align-middle mr-1"></i> Daftar Tim Kerja Mie Gokilll
                </h2>
                <table class="w-full font-space-mono text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b-4 border-black bg-gray-100 text-gray-800 font-bold">
                            <th class="p-3">Nama Lengkap</th>
                            <th class="p-3">Username</th>
                            <th class="p-3 w-32 text-center">Jabatan</th>
                            <th class="p-3 w-24 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y-2 divide-gray-200">
                        <?php if ($all_staff->num_rows > 0): ?>
                            <?php while($staff = $all_staff->fetch_assoc()): ?>
                                <?php 
                                    // AMAN: Beri nilai cadangan jika kolom di database kosong/null
                                    $nama_staf = $staff['nama_lengkap'] ?? 'Belum Diisi';
                                    $role_staf = $staff['role'] ?? 'kasir';
                                ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="p-3 font-bold text-sm text-gray-950">
                                        <?= htmlspecialchars($nama_staf); ?>
                                    </td>
                                    <td class="p-3 text-gray-600">
                                        @<?= htmlspecialchars($staff['username'] ?? 'user'); ?>
                                    </td>
                                    <td class="p-3 text-center">
                                        <?php if($role_staf === 'admin'): ?>
                                            <span class="bg-purple-100 text-purple-700 border-2 border-purple-400 font-bold px-2 py-0.5 text-[9px] uppercase">ADMIN</span>
                                        <?php elseif($role_staf === 'petugas'): ?>
                                            <span class="bg-orange-100 text-orange-700 border-2 border-orange-400 font-bold px-2 py-0.5 text-[9px] uppercase">PETUGAS</span>
                                        <?php endif ?>
                                    </td>
                                    <td class="p-3 text-center">
                                        <a href="<?= $domain .  "admin/petugas/?aksi=hapus&id=" . $staff['id'] ?>" onclick="return confirm('Yakin ingin menonaktifkan akun petugas ini?');" class="bg-red-500 hover:bg-red-600 text-white border-2 border-black px-2 py-1 text-[10px] font-bold shadow-[2px_2px_0px_#000] hover:shadow-none hover:translate-x-0.5 hover:translate-y-0.5 transition-all uppercase block text-center">
                                            Pecat
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="p-8 text-center text-gray-400 italic">Belum ada kru terdaftar.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>

        </main>

    </div>

</div>
<?php include './../../includes/toast.php'; ?>
</body>
<script src="./../../src/js/script.js"></script>
</html>