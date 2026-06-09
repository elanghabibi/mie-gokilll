<aside
    id="sidebar"
    class="fixed md:relative top-0 left-0 z-50 w-72 h-screen bg-white border-r-4 border-black flex flex-col transform -translate-x-full md:translate-x-0 transition-transform duration-300"
>

    <!-- Logo -->
    <div class="p-6 border-b-4 border-black max-md:mt-20">
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
            href="<?= $domain . 'petugas/dashboard' ?>"
            class="block p-4 border-4 border-black bg-white shadow-[6px_6px_0px_#000] hover:translate-x-1 hover:translate-y-1 hover:shadow-none transition-all"
        >
            <div class="flex items-center gap-3">
                <i class="bx bx-home text-2xl"></i>
                <span class="font-space-mono font-bold">
                    Dashboard
                </span>
            </div>
        </a>

        <a
            href="<?= $domain . 'petugas/pesanan' ?>"
            class="block p-4 border-4 border-black bg-white shadow-[6px_6px_0px_#000] hover:translate-x-1 hover:translate-y-1 hover:shadow-none transition-all"
        >
            <div class="flex items-center gap-3">
                <i class="bx bx-receipt text-2xl"></i>
                <span class="font-space-mono font-bold">
                    Data Pesanan
                </span>
            </div>
        </a>

        <a
            href="<?= $domain . 'petugas/pesanan/proses-pesanan.php' ?>"
            class="block p-4 border-4 border-black bg-white shadow-[6px_6px_0px_#000] hover:translate-x-1 hover:translate-y-1 hover:shadow-none transition-all"
        >
            <div class="flex items-center gap-3">
                <i class="bx bx-check text-2xl"></i>
                <span class="font-space-mono font-bold">
                    Pesanan Masuk
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
                <?= strtoupper($_SESSION['role']) ?>
            </h3>
        </div>

    </div>

</aside>