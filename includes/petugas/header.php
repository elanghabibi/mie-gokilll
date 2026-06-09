<header
    class="sticky top-0 z-50 h-20 shrink-0 bg-white border-b-4 border-black flex items-center justify-between px-8 max-md:px-4"
>

    <div class="flex items-center gap-4">
        <button
            id="menuBtn"
            class="flex items-center justify-center w-fit h-fit cursor-pointer md:hidden border-4 border-black p-2 bg-white"
        >
            <i class="bx bx-menu text-2xl"></i>
        </button>

        <h2 class="font-bricolage text-3xl max-md:text-xl font-black">
            Dashboard Petugas
        </h2>

    </div>

    <div class="flex items-center gap-4">

        <div class="font-space-mono text-sm max-md:hidden">
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