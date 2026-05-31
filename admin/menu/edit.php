<?php
session_start();
include './../../services/domain.php';
include './../../services/auth-nologin.php';
include './../../services/helpers.php';
include "./../../config/koneksi.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare("
    SELECT *
    FROM menu
    WHERE id = ?
");

$stmt->bind_param('i', $id);
$stmt->execute();

$menu = $stmt->get_result()->fetch_assoc();

if (!$menu) {
    header("Location: " . $domain . 'admin/menu');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu - Mie Gokilll</title>
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
                    <span class="font-space-mono-mono font-bold">
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
                    <span class="font-space-mono-mono font-bold">
                        Kelola Menu
                    </span>
                </div>
            </a>

        </nav>

        <!-- Footer Sidebar -->
        <div class="p-4 border-t-4 border-black">

            <div class="border-4 border-black p-4 bg-zinc-50 shadow-[6px_6px_0px_#000]">
                <p class="font-space-mono-mono text-xs">
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

                <div class="font-space-mono-mono text-sm">
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
        <main class="flex-1 overflow-y-auto p-8">
            <div class="max-w-4xl mx-auto">

                <!-- Heading -->
                <div class="mb-8">
                    <h1 class="font-bricolage text-5xl font-black">
                        Edit Menu
                    </h1>

                    <p class="font-space-mono mt-2">
                        Edit menu Mie Gokilll.
                    </p>
                </div>

                <!-- Form -->
                <form
                    action="<?= $domain . 'admin/menu/proses-edit.php' ?>"
                    method="POST"
                    enctype="multipart/form-data"
                    class="bg-white border-4 border-black shadow-[8px_8px_0px_#000] p-8 space-y-6"
                >
                     <input
                        type="hidden"
                        name="id"
                        value="<?= $menu['id'] ?>"
                    >

                    <input
                        type="hidden"
                        name="foto_lama"
                        value="<?= $menu['foto'] ?>"
                    >

                    <!-- Nama Menu -->
                    <div>
                        <label class="block font-space-mono font-bold mb-2">
                            NAMA MENU
                        </label>

                        <input
                            type="text"
                            name="nama_menu"
                            required
                            value="<?= htmlspecialchars($menu['nama_menu']) ?>"
                            class="w-full border-4 border-black px-4 py-3 outline-none"
                            placeholder="Mie Gokill Original"
                        >
                    </div>

                    <!-- Harga -->
                    <div>
                        <label class="block font-space-mono font-bold mb-2">
                            HARGA
                        </label>

                        <input
                            type="text"
                            name="harga"
                            inputmode="numeric"
                            value="<?= $menu['harga'] ?>"
                            pattern="[0-9]*"
                            required
                            class="w-full border-4 border-black px-4 py-3 outline-none"
                            placeholder="18000"
                        >
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label class="block font-space-mono font-bold mb-2">
                            KATEGORI
                        </label>

                        <select
                            name="kategori"
                            required
                            class="w-full border-4 border-black px-4 py-3 outline-none"
                        >
                            <option value="makanan"
                                <?= $menu['kategori'] == 'makanan' ? 'selected' : '' ?>>
                                Makanan
                            </option>

                            <option value="minuman"
                                <?= $menu['kategori'] == 'minuman' ? 'selected' : '' ?>>
                                Minuman
                            </option>

                            <option value="cemilan"
                                <?= $menu['kategori'] == 'cemilan' ? 'selected' : '' ?>>
                                Cemilan
                            </option>
                        </select>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block font-space-mono font-bold mb-2">
                            DESKRIPSI
                        </label>

                        <textarea
                            name="deskripsi"
                            rows="5"
                            class="w-full border-4 border-black px-4 py-3 resize-none outline-none"
                            placeholder="Masukkan deskripsi menu..."
                        ><?= htmlspecialchars($menu['deskripsi']) ?></textarea>
                    </div>

                    <!-- Foto -->
                    <div>
                        <label class="block font-space-mono font-bold mb-2">
                            FOTO MENU
                        </label>

                        <input
                            type="file"
                            name="foto"
                            accept="image/*"
                            id="previewInput"
                            class="w-full mb-4 border-4 border-black p-3"
                        >
                        <div class="w-40 aspect-square overflow-hidden border-4 border-black bg-white">
                            <img
                                id="previewImg"
                                src="<?= $domain . 'uploads/' . $menu['foto'] ?>"
                                class="w-full h-full object-cover"
                            >
                        </div>

                        <p class="font-space-mono text-xs mt-2">
                            Kosongkan jika tidak ingin mengganti foto.
                        </p>
                    </div>

                    <!-- Checkbox -->
                    <div class="flex flex-col gap-4">

                        <label class="flex items-center gap-3">
                            <input
                                type="checkbox"
                                name="tersedia"
                                value="1"
                                <?= $menu['tersedia'] ? 'checked' : '' ?>
                                class="w-5 h-5"
                            >

                            <span class="font-space-mono">
                                Menu tersedia
                            </span>
                        </label>

                        <label class="flex items-center gap-3">
                            <input
                                type="checkbox"
                                name="terlaris"
                                value="1"
                                class="w-5 h-5"
                                <?= $menu['terlaris'] ? 'checked' : '' ?>
                            >

                            <span class="font-space-mono">
                                Jadikan Best Seller
                            </span>
                        </label>

                    </div>

                    <!-- Action -->
                    <div class="flex gap-4 pt-4">

                        <a
                            href="<?= $domain . 'admin/menu' ?>"
                            class="px-6 py-3 border-4 border-black bg-white shadow-[6px_6px_0px_#000] hover:translate-x-1 hover:translate-y-1 hover:shadow-none transition-all"
                        >
                            Batal
                        </a>

                        <button
                            type="submit"
                            class="px-6 py-3 border-4 border-black bg-black text-white shadow-[6px_6px_0px_#000] hover:translate-x-1 hover:translate-y-1 hover:shadow-none transition-all"
                        >
                            Simpan Menu
                        </button>

                    </div>

                </form>

            </div>

        </main>

    </div>

</div>

</body>
<script src="./../../src/js/script.js"></script>
</html>