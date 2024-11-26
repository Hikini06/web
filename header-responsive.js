// responsive-header.js

document.addEventListener("DOMContentLoaded", function () {
    // Nút Hamburger và menu chính
    const hamburger = document.getElementById('hamburger');
    const menu = document.querySelector('.product-banner-jim-child');

    // Mảng các mục sản phẩm và bảng tương ứng
    const sanphamItems = [
        {
            sanphamClass: '.product-banner-jim-sanpham-one',
            tableClass: '.product-banner-jim-table'
        },
        {
            sanphamClass: '.product-banner-jim-sanpham-two',
            tableClass: '.product-banner-jim-table-two'
        },
        {
            sanphamClass: '.product-banner-jim-sanpham-three',
            tableClass: '.product-banner-jim-table-three'
        },
        {
            sanphamClass: '.product-banner-jim-sanpham-four',
            tableClass: '.product-banner-jim-table-four'
        },
        {
            sanphamClass: '.product-banner-jim-sanpham-five',
            tableClass: '.product-banner-jim-table-five'
        }
    ];

    // Xác định xem thiết bị có phải là di động không
    const isMobile = window.matchMedia("(max-width: 768px)").matches;

    // Xử lý sự kiện click trên nút hamburger
    hamburger.addEventListener('click', function (e) {
        e.stopPropagation(); // Ngăn sự kiện lan truyền lên document
        hamburger.classList.toggle('active'); // Thêm hoặc loại bỏ lớp active
        menu.classList.toggle('active'); // Thêm hoặc loại bỏ lớp active cho menu
    });

    // Xử lý sự kiện click cho từng mục sản phẩm
    sanphamItems.forEach(item => {
        const sanpham = document.querySelector(item.sanphamClass);
        const table = document.querySelector(item.tableClass);

        if (sanpham && table) {
            sanpham.addEventListener('click', (e) => {
                if (isMobile) {
                    e.preventDefault(); // Ngăn hành động mặc định của thẻ <a> chỉ trên di động
                    e.stopPropagation(); // Ngăn sự kiện lan truyền chỉ trên di động

                    // Tắt tất cả các bảng khác
                    sanphamItems.forEach(i => {
                        const otherTable = document.querySelector(i.tableClass);
                        if (otherTable && otherTable !== table) {
                            otherTable.classList.remove('active');
                        }
                    });

                    // Bật hoặc tắt bảng hiện tại
                    table.classList.toggle('active');
                }
                // Nếu không phải di động, để các liên kết hoạt động bình thường
            });
        }
    });

    // Thêm sự kiện cho nút đóng trong bảng sản phẩm
    sanphamItems.forEach(item => {
        const table = document.querySelector(item.tableClass);
        if (table) {
            const closeBtn = table.querySelector('.close-table');
            if (closeBtn) {
                closeBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation(); // Ngăn sự kiện lan truyền
                    table.classList.remove('active');
                });
            }

            // Ngăn sự kiện lan truyền khi nhấp vào bên trong bảng
            table.addEventListener('click', (e) => {
                e.stopPropagation();
            });
        }
    });

    // Đóng menu khi nhấp vào bên ngoài menu và các bảng sản phẩm
    document.addEventListener('click', function (e) {
        // Kiểm tra xem nơi nhấp có nằm ngoài hamburger, menu chính và các bảng sản phẩm hay không
        const isClickInsideHamburger = hamburger.contains(e.target);
        const isClickInsideMenu = menu.contains(e.target);

        // Kiểm tra các bảng sản phẩm
        let isClickInsideTable = false;
        sanphamItems.forEach(item => {
            const table = document.querySelector(item.tableClass);
            if (table && table.contains(e.target)) {
                isClickInsideTable = true;
            }
        });

        if (!isClickInsideHamburger && !isClickInsideMenu && !isClickInsideTable) {
            hamburger.classList.remove('active');
            menu.classList.remove('active');
            // Đóng tất cả các bảng sản phẩm
            sanphamItems.forEach(item => {
                const table = document.querySelector(item.tableClass);
                if (table) {
                    table.classList.remove('active');
                }
            });
        }
    });

    // Xử lý sự kiện click cho các mục lồng nhau
    const nestedLinks = document.querySelectorAll('.product-banner-jim-table-ul .product-banner-jim-li > a');

    nestedLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            if (isMobile) {
                e.preventDefault(); // Ngăn hành động mặc định của thẻ <a> chỉ trên di động
                e.stopPropagation(); // Ngăn sự kiện lan truyền chỉ trên di động

                const parentLi = e.target.closest('.product-banner-jim-li');

                if (parentLi) {
                    const isActive = parentLi.classList.contains('active');

                    // Đóng tất cả các danh sách lồng nhau khác
                    document.querySelectorAll('.product-banner-jim-table-ul .product-banner-jim-li.active').forEach(li => {
                        if (li !== parentLi) {
                            li.classList.remove('active');
                        }
                    });

                    // Bật hoặc tắt danh sách lồng nhau của mục hiện tại
                    if (!isActive) {
                        parentLi.classList.add('active');
                    } else {
                        parentLi.classList.remove('active');
                    }
                }
            }
            // Nếu không phải di động, để các liên kết hoạt động bình thường
        });
    });
});
