
document.addEventListener("DOMContentLoaded", function () {
    const cartIcon = document.getElementById("cart-icon");
    const miniCart = document.getElementById("mini-cart");
    const cartCount = document.getElementById("cart-count");
    const miniCartContent = document.getElementById("mini-cart-content");

    if (!miniCart || !cartIcon || !cartCount || !miniCartContent) return;

    // Hàm cập nhật mini cart
    function updateMiniCart() {
        fetch("/cart/items")
            .then(res => res.json())
            .then(data => {
                miniCartContent.innerHTML = '';

                if (!data.items || data.items.length === 0) {
                    miniCartContent.innerHTML = `
                        <img src="https://cdn-icons-png.flaticon.com/512/2038/2038854.png" width="80">
                        <p class="mt-3">Chưa Có Sản Phẩm</p>
                    `;
                } else {
                    data.items.forEach(item => {
                        const div = document.createElement("div");
                        div.classList.add("mini-cart-item");
                        div.innerHTML = `
                            <img src="${item.image}" alt="${item.name}">
                            <div>
                                <p>${item.name}</p>
                                <p class="price">${item.price.toLocaleString()} VND</p>
                            </div>
                        `;
                        miniCartContent.appendChild(div);
                    });

                    // Nút xem giỏ hàng
                    const footer = document.createElement("div");
                    footer.classList.add("mini-cart-footer");
                    footer.innerHTML = `<a href="/cart">Xem giỏ hàng</a>`;
                    miniCartContent.appendChild(footer);
                }
            });
    }

    // Hàm cập nhật số lượng giỏ hàng
    function updateCartCount() {
        fetch("/cart/count")
            .then(res => res.json())
            .then(data => {
                cartCount.textContent = data.count ?? 0;
            });
    }

    // Cập nhật khi trang load
    updateMiniCart();
    updateCartCount();

    // Toggle mini cart khi click icon
    cartIcon.addEventListener("click", function (e) {
        e.preventDefault();
        miniCart.style.display = miniCart.style.display === "block" ? "none" : "block";
    });

    // Ẩn mini cart khi click ra ngoài
    document.addEventListener("click", function (e) {
        if (!cartIcon.contains(e.target) && !miniCart.contains(e.target)) {
            miniCart.style.display = "none";
        }
    });
});



