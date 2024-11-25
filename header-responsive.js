// header-responsive.js

document.addEventListener("DOMContentLoaded", function () {
    const hamburger = document.getElementById('hamburger');
    const menu = document.querySelector('.product-banner-jim-child');
    const searchBar = document.querySelector('.search-bar-cont');
    const contactNumber = document.querySelector('.header-contact-number');

    // Xử lý sự kiện click trên nút hamburger
    hamburger.addEventListener('click', function (e) {
        e.stopPropagation(); // Ngăn sự kiện lan truyền lên document
        hamburger.classList.toggle('active'); // Thêm hoặc loại bỏ lớp active
        menu.classList.toggle('active'); // Thêm hoặc loại bỏ lớp active cho menu
    });

    // Đóng menu khi nhấp vào bên ngoài menu
    document.addEventListener('click', function (e) {
        // Nếu nhấp ngoài nút hamburger và menu đang mở
        if (!hamburger.contains(e.target) && !menu.contains(e.target)) {
            hamburger.classList.remove('active');
            menu.classList.remove('active');
        }
    });
});
