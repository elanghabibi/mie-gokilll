<?php if (isset($_SESSION['success'])): ?>
<div
    id="toast-success"
    class="fixed top-5 right-5 z-50 px-6 py-4 bg-white border-4 border-black shadow-[6px_6px_0px_#000] transition-all duration-300"
>
    <div class="flex items-center gap-3">
        <i class="bx bx-check-circle text-2xl"></i>
        <span><?= $_SESSION['success'] ?></span>
    </div>
</div>

<?php unset($_SESSION['success']); ?>
<?php endif; ?>


<?php if (isset($_SESSION['error'])): ?>
<div
    id="toast-error"
    class="fixed top-5 right-5 z-50 px-6 py-4 bg-white border-4 border-black shadow-[6px_6px_0px_#000] transition-all duration-300"
>
    <div class="flex items-center gap-3">
        <i class="bx bx-x-circle text-2xl"></i>
        <span><?= $_SESSION['error'] ?></span>
    </div>
</div>

<?php unset($_SESSION['error']); ?>
<?php endif; ?>