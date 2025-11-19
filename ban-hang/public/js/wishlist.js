document.addEventListener("DOMContentLoaded", function () {
    const wishlistIcon = document.getElementById("wishlist-icon");
    const miniWishlist = document.getElementById("mini-wishlist");
    const miniWishlistContent = document.getElementById("mini-wishlist-content");
    const wishlistCount = document.getElementById("wishlist-count");
    const wishlistBtn = document.getElementById("btn-add-to-wishlist");

    function getWishlist() {
        return JSON.parse(localStorage.getItem("wishlist")) || [];
    }

    function saveWishlist(wishlist) {
        localStorage.setItem("wishlist", JSON.stringify(wishlist));
    }

    function updateWishlistCount() {
        if (wishlistCount) wishlistCount.innerText = getWishlist().length;
    }

    function removeFromWishlist(id) {
        let wishlist = getWishlist();
        wishlist = wishlist.filter(item => item.id !== id);
        saveWishlist(wishlist);
        updateWishlistCount();
        loadWishlistUI();
    }

    function loadWishlistUI() {
        const wishlist = getWishlist();
        miniWishlistContent.innerHTML = "";

        if (wishlist.length === 0) {
            miniWishlistContent.innerHTML = `
                <img src="https://cdn-icons-png.flaticon.com/512/4076/4076500.png" width="80">
                <p class="mt-3">Danh sách yêu thích trống</p>
            `;
            return;
        }

        wishlist.forEach(item => {
            const div = document.createElement("div");
            div.classList.add("mini-cart-item");

            const name = item.name || "Sản phẩm";
            const image = item.image || "http://via.placeholder.com/400";
            const price = item.price != null ? Number(item.price).toLocaleString() : "0";

            div.innerHTML = `
                <img src="${image}" alt="${name}">
                <div>
                    <p>${name}</p>
                    <p class="price">${price} VND</p>
                </div>
                <button class="remove-wishlist-btn" data-id="${item.id}">×</button>
            `;
            miniWishlistContent.appendChild(div);
        });

        const footer = document.createElement("div");
        footer.classList.add("mini-cart-footer");
        footer.innerHTML = `<a href="/wishlist">Xem danh sách yêu thích</a>`;
        miniWishlistContent.appendChild(footer);

        document.querySelectorAll(".remove-wishlist-btn").forEach(btn => {
            btn.addEventListener("click", function () {
                removeFromWishlist(this.getAttribute("data-id"));
            });
        });
    }

    // Toggle mini-wishlist
    if (wishlistIcon) {
        wishlistIcon.addEventListener("click", function (e) {
            e.preventDefault();
            loadWishlistUI();
            miniWishlist.style.display =
                miniWishlist.style.display === "block" ? "none" : "block";
        });
    }

    // Click outside → close
    document.addEventListener("click", function (e) {
        if (wishlistIcon && miniWishlist && !wishlistIcon.contains(e.target) && !miniWishlist.contains(e.target)) {
            miniWishlist.style.display = "none";
        }
    });

    // Add/remove from wishlist
    if (wishlistBtn && typeof productData !== "undefined") {
        let wishlist = getWishlist();
        const exists = wishlist.find(item => item.id === productData.id);
        if (exists) wishlistBtn.innerHTML = `<i class="fas fa-heart text-red-500"></i>`;

        wishlistBtn.addEventListener("click", function () {
            let wishlist = getWishlist();
            const exists = wishlist.find(item => item.id === productData.id);

            if (!exists) {
                wishlist.push({
                    id: productData.id,
                    name: productData.name || "Sản phẩm",
                    price: productData.price || 0,
                    image: (productData.images && productData.images.length > 0) ? productData.images[0] : 'http://via.placeholder.com/400'
                });

                wishlistBtn.innerHTML = `<i class="fas fa-heart text-red-500"></i>`;
            } else {
                wishlist = wishlist.filter(item => item.id !== productData.id);
                wishlistBtn.innerHTML = `<i class="far fa-heart"></i>`;
            }

            saveWishlist(wishlist);
            updateWishlistCount();
            if (miniWishlist.style.display === "block") loadWishlistUI();
        });
    }

    // Cập nhật số lượng ngay khi load
    updateWishlistCount();
});
