// chỉnh tổng số sản phẩm và sản phẫm xuất hiện trên 1 trang
const totalProducts = 600; 
const productsPerPage = 16;

const productsContainer = document.getElementById('products-container');
const paginationContainer = document.getElementById('pagination');
let currentPage = 1;

// Tạo danh sách sản phẩm
const products = Array.from({ length: totalProducts }, (_, i) => ({
    id: i + 1,
    name: `Sản phẩm ${i + 1}`,
    image: `https://picsum.photos/200?random=${i + 1}`,
    description: `Đây là mô tả chi tiết cho sản phẩm ${i + 1}.`
}));

// Thêm sản phẩm tùy chỉnh tại đây
products[0] = {
    id: 1,
    name: 'Laptop Asus vớ vẩn đểu',
    image: 'https://cdn.phuckhangmobile.com/image/iphone-13-128gb-hong-quocte-phuckhangmobile-27954j.jpg',
    description: '385.000đ',
    url: 'https://www.google.com/'
};
products[1] = {
    id: 2,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: '128.000đ',
    url: 'iphone-13.html'
};
products[2] = {
    id: 3,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: '294.000đ',
    url: 'iphone-13.html'
};
products[3] = {
    id: 4,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: '450.000đ',
    url: 'iphone-13.html'
};
products[4] = {
    id: 5,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: '92.000đ',
    url: 'iphone-13.html'
};
products[5] = {
    id: 6,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: '333.000đ',
    url: 'iphone-13.html'
};
products[6] = {
    id: 7,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: '75.000đ',
    url: 'iphone-13.html'
};
products[7] = {
    id: 8,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: '482.000đ',
    url: 'iphone-13.html'
};
products[8] = {
    id: 9,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: '108.000đ',
    url: 'iphone-13.html'
};
products[9] = {
    id: 10,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: '299.000đ',
    url: 'iphone-13.html'
};
products[10] = {
    id: 11,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: '467.000đ',
    url: 'iphone-13.html'
};
products[11] = {
    id: 12,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: '81.000đ',
    url: 'iphone-13.html'
};
products[12] = {
    id: 13,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: '371.000đ',
    url: 'iphone-13.html'
};
products[13] = {
    id: 14,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: '265.000đ',
    url: 'iphone-13.html'
};
products[14] = {
    id: 15,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: '495.000đ',
    url: 'iphone-13.html'
};
products[15] = {
    id: 16,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: '310.000đ',
    url: 'iphone-13.html'
};
products[16] = {
    id: 17,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: '138.000đ',
    url: 'iphone-13.html'
};
products[17] = {
    id: 18,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: '204.000đ',
    url: 'iphone-13.html'
};
products[18] = {
    id: 19,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: '480.000đ',
    url: 'iphone-13.html'
};
products[19] = {
    id: 20,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: '359.000đ',
    url: 'iphone-13.html'
};
products[20] = {
    id: 21,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: '295.000đ',
    url: 'iphone-13.html'
};
products[21] = {
    id: 22,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: '132.000đ',
    url: 'iphone-13.html'
};
products[22] = {
    id: 23,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: '432.000đ',
    url: 'iphone-13.html'
};
products[23] = {
    id: 24,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: '370.000đ',
    url: 'iphone-13.html'
};
products[24] = {
    id: 25,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: '451.000đ',
}
products[25] = {
    id: 26,
    name: 'Điện thoại Xiaomi giá rẻ',
    image: 'https://via.placeholder.com/200x200.png?text=Product+26',
    description: '278.000đ',
    url: 'xiaomi-26.html'
};
products[26] = {
    id: 27,
    name: 'Máy tính bảng Huawei',
    image: 'https://via.placeholder.com/200x200.png?text=Product+27',
    description: '312.000đ',
    url: 'huawei-tablet.html'
};
products[27] = {
    id: 28,
    name: 'Laptop Dell Inspiron',
    image: 'https://via.placeholder.com/200x200.png?text=Product+28',
    description: '456.000đ',
    url: 'dell-inspiron.html'
};
products[28] = {
    id: 29,
    name: 'Tai nghe Bluetooth Sony',
    image: 'https://via.placeholder.com/200x200.png?text=Product+29',
    description: '125.000đ',
    url: 'sony-headphones.html'
};
products[29] = {
    id: 30,
    name: 'Điện thoại Oppo Reno',
    image: 'https://via.placeholder.com/200x200.png?text=Product+30',
    description: '388.000đ',
    url: 'oppo-reno.html'
};
products[30] = {
    id: 31,
    name: 'Đồng hồ thông minh Garmin',
    image: 'https://via.placeholder.com/200x200.png?text=Product+31',
    description: '489.000đ',
    url: 'garmin-watch.html'
};
products[31] = {
    id: 32,
    name: 'Loa Bluetooth JBL',
    image: 'https://via.placeholder.com/200x200.png?text=Product+32',
    description: '174.000đ',
    url: 'jbl-speaker.html'
};
products[32] = {
    id: 33,
    name: 'Chuột không dây Logitech',
    image: 'https://via.placeholder.com/200x200.png?text=Product+33',
    description: '95.000đ',
    url: 'logitech-mouse.html'
};
products[33] = {
    id: 34,
    name: 'Bàn phím cơ Keychron',
    image: 'https://via.placeholder.com/200x200.png?text=Product+34',
    description: '342.000đ',
    url: 'keychron-keyboard.html'
};
products[34] = {
    id: 35,
    name: 'Máy ảnh Canon EOS',
    image: 'https://via.placeholder.com/200x200.png?text=Product+35',
    description: '472.000đ',
    url: 'canon-eos.html'
};
products[35] = {
    id: 36,
    name: 'Smartphone Vivo V27',
    image: 'https://via.placeholder.com/200x200.png?text=Product+36',
    description: '391.000đ',
    url: 'vivo-v27.html'
};
products[36] = {
    id: 37,
    name: 'Ổ cứng di động WD',
    image: 'https://via.placeholder.com/200x200.png?text=Product+37',
    description: '238.000đ',
    url: 'wd-external.html'
};
products[37] = {
    id: 38,
    name: 'Màn hình LG UltraWide',
    image: 'https://via.placeholder.com/200x200.png?text=Product+38',
    description: '499.000đ',
    url: 'lg-ultrawide.html'
};
products[38] = {
    id: 39,
    name: 'Sạc dự phòng Anker',
    image: 'https://via.placeholder.com/200x200.png?text=Product+39',
    description: '155.000đ',
    url: 'anker-powerbank.html'
};
products[39] = {
    id: 40,
    name: 'Tablet Samsung Galaxy',
    image: 'https://via.placeholder.com/200x200.png?text=Product+40',
    description: '298.000đ',
    url: 'samsung-tablet.html'
};
products[40] = {
    id: 41,
    name: 'Điện thoại Nokia 3310',
    image: 'https://via.placeholder.com/200x200.png?text=Product+41',
    description: '52.000đ',
    url: 'nokia-3310.html'
};
products[41] = {
    id: 42,
    name: 'Máy tính để bàn Lenovo',
    image: 'https://via.placeholder.com/200x200.png?text=Product+42',
    description: '418.000đ',
    url: 'lenovo-desktop.html'
};
products[42] = {
    id: 43,
    name: 'Smart TV Sony Bravia',
    image: 'https://via.placeholder.com/200x200.png?text=Product+43',
    description: '473.000đ',
    url: 'sony-tv.html'
};
products[43] = {
    id: 44,
    name: 'Đèn thông minh Xiaomi',
    image: 'https://via.placeholder.com/200x200.png?text=Product+44',
    description: '92.000đ',
    url: 'xiaomi-lamp.html'
};
products[44] = {
    id: 45,
    name: 'Router Wi-Fi TP-Link',
    image: 'https://via.placeholder.com/200x200.png?text=Product+45',
    description: '164.000đ',
    url: 'tp-link-router.html'
};




// Hàm hiển thị sản phẩm theo trang (đúng)
function renderProducts(page) {
    productsContainer.innerHTML = ''; // Xóa các sản phẩm hiện tại
    const startIndex = (page - 1) * productsPerPage;
    const endIndex = Math.min(startIndex + productsPerPage, products.length);

    for (let i = startIndex; i < endIndex; i++) {
        const product = products[i];

        // Kiểm tra URL
        if (!product.url || product.url.trim() === '') {
            console.error(`Sản phẩm ${product.name} không có URL hợp lệ.`);
            continue;
        }

        // Tạo thẻ <a> bọc sản phẩm
        const productLink = document.createElement('a');
        productLink.href = product.url;
        productLink.target = '_blank'; // Mở trong tab mới
        productLink.style.textDecoration = 'none';

        // Tạo thẻ sản phẩm
        const productDiv = document.createElement('div');
        productDiv.className = 'product';

        // Tạo hình ảnh
        const productImg = document.createElement('img');
        productImg.src = product.image;
        productImg.alt = `Hình ảnh của ${product.name}`;
        // productImg.style.width = '150px';
        // productImg.style.height = '150px';

        // Tạo tên và mô tả
        const productName = document.createElement('span');
        productName.textContent = product.name;

        const productDescription = document.createElement('p');
        productDescription.textContent = product.description;

        // Gắn các phần tử
        productDiv.appendChild(productImg);
        productDiv.appendChild(productName);
        productDiv.appendChild(productDescription);

        // Gắn thẻ sản phẩm vào thẻ <a>
        productLink.appendChild(productDiv);

        // Thêm sản phẩm vào container
        productsContainer.appendChild(productLink);
    }
}

// Hàm hiển thị nút phân trang
function renderPagination() {
    paginationContainer.innerHTML = ''; // Xóa các nút phân trang cũ
    const totalPages = Math.ceil(totalProducts / productsPerPage);

    // Nút "Trước"
    const prevButton = document.createElement('button');
    prevButton.textContent = '⬅';
    prevButton.className = currentPage === 1 ? 'disabled' : '';
    prevButton.addEventListener('click', () => changePage(currentPage - 1));
    paginationContainer.appendChild(prevButton);

    // Hiển thị các nút phân trang
    if (totalPages <= 5) {
        for (let i = 1; i <= totalPages; i++) {
            createPageButton(i);
        }
    } else {
        if (currentPage <= 3) {
            for (let i = 1; i <= 3; i++) {
                createPageButton(i);
            }
            createDots();
            createPageButton(totalPages);
        } else if (currentPage > 3 && currentPage < totalPages - 2) {
            createPageButton(1);
            createDots();
            createPageButton(currentPage - 1);
            createPageButton(currentPage);
            createPageButton(currentPage + 1);
            createDots();
            createPageButton(totalPages);
        } else {
            createPageButton(1);
            createDots();
            for (let i = totalPages - 2; i <= totalPages; i++) {
                createPageButton(i);
            }
        }
    }

    // Nút "Tiếp"
    const nextButton = document.createElement('button');
    nextButton.textContent = '⮕';
    nextButton.className = currentPage === totalPages ? 'disabled' : '';
    nextButton.addEventListener('click', () => changePage(currentPage + 1));
    paginationContainer.appendChild(nextButton);
}

// Các hàm hỗ trợ pagination
function createPageButton(page) {
    const pageButton = document.createElement('button');
    pageButton.textContent = page;
    pageButton.className = page === currentPage ? 'active' : '';
    pageButton.addEventListener('click', () => changePage(page));
    paginationContainer.appendChild(pageButton);
}

function createDots() {
    const dots = document.createElement('span');
    dots.textContent = '...';
    dots.className = 'dots';
    paginationContainer.appendChild(dots);
}

// Hàm chuyển trang
function changePage(page) {
    const totalPages = Math.ceil(totalProducts / productsPerPage);
    if (page < 1 || page > totalPages) return;

    currentPage = page;
    renderProducts(page);
    renderPagination();
    // Cuộn lên đầu trang
    window.scrollTo({
        top: 0,
        behavior: 'smooth' // Cuộn mượt
    });
}

// Hiển thị trang đầu tiên
changePage(1);



// GUIDE SECTION SLIDER JS START HERE
const guideSectionImageSlider = document.querySelector('.guideSection-image-slider');
const guideSectionImageSliderContainer = document.querySelector('.guideSection-image-slider-container');
const guideSectionPrevButton = document.querySelector('.guideSection-slider-prev-btn');
const guideSectionNextButton = document.querySelector('.guideSection-slider-next-btn');

let guideSectionCurrentIndex = 0;
const guideSectionVisibleImages = 3; // Hiển thị 3 ảnh
let guideSectionSlideWidth = guideSectionImageSliderContainer.offsetWidth / guideSectionVisibleImages; // Tính chiều rộng mỗi ảnh
const guideSectionTotalImages = document.querySelectorAll('.guideSection-image-slider .guideSection-image-card').length;
let guideSectionAutoSlideInterval; // Biến lưu interval cho auto slide

// Hàm cập nhật vị trí slider
function guideSectionUpdateSlider() {
    guideSectionImageSlider.style.transform = `translateX(-${guideSectionCurrentIndex * guideSectionSlideWidth}px)`;
    guideSectionUpdateButtons();
}

// Hàm cập nhật trạng thái của nút Prev và Next
function guideSectionUpdateButtons() {
    guideSectionPrevButton.disabled = guideSectionCurrentIndex === 0;
    guideSectionNextButton.disabled = guideSectionCurrentIndex >= guideSectionTotalImages - guideSectionVisibleImages;
}

// Hàm tự động chuyển slide
function guideSectionAutoSlide() {
    guideSectionCurrentIndex++;
    if (guideSectionCurrentIndex > guideSectionTotalImages - guideSectionVisibleImages) {
        guideSectionCurrentIndex = 0; // Quay về slide đầu tiên
    }
    guideSectionUpdateSlider();
}

// Hàm dừng tự động slide
function guideSectionStopAutoSlide() {
    clearInterval(guideSectionAutoSlideInterval);
}

// Hàm khởi động lại tự động slide
function guideSectionRestartAutoSlide() {
    guideSectionStopAutoSlide();
    guideSectionAutoSlideInterval = setInterval(guideSectionAutoSlide, 10000); // Đặt lại 10 giây
}

// Sự kiện khi nhấn nút Prev
guideSectionPrevButton.addEventListener('click', () => {
    if (guideSectionCurrentIndex > 0) {
        guideSectionCurrentIndex--;
        guideSectionUpdateSlider();
    }
    guideSectionRestartAutoSlide(); // Khởi động lại timer sau khi nhấn
});

// Sự kiện khi nhấn nút Next
guideSectionNextButton.addEventListener('click', () => {
    if (guideSectionCurrentIndex < guideSectionTotalImages - guideSectionVisibleImages) {
        guideSectionCurrentIndex++;
        guideSectionUpdateSlider();
    }
    guideSectionRestartAutoSlide(); // Khởi động lại timer sau khi nhấn
});

// Tự động điều chỉnh chiều rộng ảnh khi thay đổi kích thước màn hình
window.addEventListener('resize', () => {
    guideSectionSlideWidth = guideSectionImageSliderContainer.offsetWidth / guideSectionVisibleImages;
    guideSectionUpdateSlider();
});

// Kích hoạt tự động slide mỗi 10 giây
guideSectionAutoSlideInterval = setInterval(guideSectionAutoSlide, 3000);

// Khởi tạo slider
guideSectionUpdateButtons();

// GUIDE SECTION SLIDER JS END HERE


