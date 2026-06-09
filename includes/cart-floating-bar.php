<div id="cart-floating-bar">
    <div class="w-full mx-auto flex items-center justify-between px-12 max-md:px-4">
        
        <div class="text-3xl max-md:text-xl font-black font-bricolage text-black tracking-tight">
            Total Pesanan : <span id="total-qty">0</span>
        </div>
        
        <a href="<?= $domain . 'cart.php' ?>" class="bg-white text-black border-4 border-black px-8 py-2 rounded-full font-black text-2xl max-md:text-lg shadow-[4px_4px_0px_#000] hover:translate-x-0.5 hover:translate-y-0.5 hover:shadow-none transition-all tracking-tight block">
            Buat Pesanan
        </a>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const currentMenuId = "<?= $menu['id'] ?>";
        
        const cartBar = document.getElementById('cart-floating-bar');
        const totalQtyEl = document.getElementById('total-qty');
        const detailQtyLabel = document.getElementById('detail-qty');

        function updateCartDOM() {
            let cart = JSON.parse(localStorage.getItem('gokilll_cart')) || {};
            let totalItems = 0;

            Object.keys(cart).forEach(id => {
                totalItems += cart[id];
            });

            if (detailQtyLabel) {
                detailQtyLabel.innerText = cart[currentMenuId] || 0;
            }

            if (totalItems > 0) {
                cartBar.classList.add('active');
            } else {
                cartBar.classList.remove('active');
            }

            totalQtyEl.innerText = totalItems;
        }

        updateCartDOM();

        // Tombol Tambah (+)
        document.querySelectorAll('.btn-plus').forEach(button => {
            button.addEventListener('click', () => {
                let cart = JSON.parse(localStorage.getItem('gokilll_cart')) || {};
                const id = button.dataset.id;

                if (!cart[id]) {
                    cart[id] = 0;
                }
                cart[id]++;
                
                localStorage.setItem('gokilll_cart', JSON.stringify(cart));
                updateCartDOM();
            });
        });

        // Tombol Kurang (-)
        document.querySelectorAll('.btn-min').forEach(button => {
            button.addEventListener('click', () => {
                let cart = JSON.parse(localStorage.getItem('gokilll_cart')) || {};
                const id = button.dataset.id;

                if (cart[id] && cart[id] > 0) {
                    cart[id]--;
                    
                    if(cart[id] === 0) {
                        delete cart[id];
                    }
                    
                    localStorage.setItem('gokilll_cart', JSON.stringify(cart));
                    updateCartDOM();
                }
            });
        });
    });
</script>