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
    description: 'Laptop Asus với cấu hình mạnh mẽ, phù hợp cho mọi nhu cầu làm việc.',
    url: 'https://www.google.com/'
};

products[1] = {
    id: 2,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: 'iPhone 13 với thiết kế thời thượng và hiệu năng vượt trội.',
    url: 'iphone-13.html'
};
products[2] = {
    id: 3,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: 'iPhone 13 với thiết kế thời thượng và hiệu năng vượt trội.',
    url: 'iphone-13.html'
};
products[3] = {
    id: 4,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: 'iPhone 13 với thiết kế thời thượng và hiệu năng vượt trội.',
    url: 'iphone-13.html'
};
products[4] = {
    id: 5,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: 'iPhone 13 với thiết kế thời thượng và hiệu năng vượt trội.',
    url: 'iphone-13.html'
};
products[5] = {
    id: 6,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: 'iPhone 13 với thiết kế thời thượng và hiệu năng vượt trội.',
    url: 'iphone-13.html'
};
products[6] = {
    id: 7,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: 'iPhone 13 với thiết kế thời thượng và hiệu năng vượt trội.',
    url: 'iphone-13.html'
};
products[7] = {
    id: 8,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: 'iPhone 13 với thiết kế thời thượng và hiệu năng vượt trội.',
    url: 'iphone-13.html'
};
products[8] = {
    id: 9,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: 'iPhone 13 với thiết kế thời thượng và hiệu năng vượt trội.',
    url: 'iphone-13.html'
};
products[9] = {
    id: 10,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: 'iPhone 13 với thiết kế thời thượng và hiệu năng vượt trội.',
    url: 'iphone-13.html'
};
products[10] = {
    id: 11,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: 'iPhone 13 với thiết kế thời thượng và hiệu năng vượt trội.',
    url: 'iphone-13.html'
};
products[11] = {
    id: 12,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: 'iPhone 13 với thiết kế thời thượng và hiệu năng vượt trội.',
    url: 'iphone-13.html'
};
products[12] = {
    id: 13,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: 'iPhone 13 với thiết kế thời thượng và hiệu năng vượt trội.',
    url: 'iphone-13.html'
};
products[13] = {
    id: 14,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: 'iPhone 13 với thiết kế thời thượng và hiệu năng vượt trội.',
    url: 'iphone-13.html'
};
products[14] = {
    id: 15,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: 'iPhone 13 với thiết kế thời thượng và hiệu năng vượt trội.',
    url: 'iphone-13.html'
};
products[15] = {
    id: 16,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: 'iPhone 13 với thiết kế thời thượng và hiệu năng vượt trội.',
    url: 'iphone-13.html'
};
products[16] = {
    id: 17,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: 'iPhone 13 với thiết kế thời thượng và hiệu năng vượt trội.',
    url: 'iphone-13.html'
};
products[17] = {
    id: 18,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: 'iPhone 13 với thiết kế thời thượng và hiệu năng vượt trội.',
    url: 'iphone-13.html'
};
products[18] = {
    id: 19,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: 'iPhone 13 với thiết kế thời thượng và hiệu năng vượt trội.',
    url: 'iphone-13.html'
};
products[19] = {
    id: 20,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: 'iPhone 13 với thiết kế thời thượng và hiệu năng vượt trội.',
    url: 'iphone-13.html'
};
products[20] = {
    id: 21,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: 'iPhone 13 với thiết kế thời thượng và hiệu năng vượt trội.',
    url: 'iphone-13.html'
};
products[21] = {
    id: 22,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: 'iPhone 13 với thiết kế thời thượng và hiệu năng vượt trội.',
    url: 'iphone-13.html'
};
products[22] = {
    id: 23,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: 'iPhone 13 với thiết kế thời thượng và hiệu năng vượt trội.',
    url: 'iphone-13.html'
};
products[23] = {
    id: 24,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: 'iPhone 13 với thiết kế thời thượng và hiệu năng vượt trội.',
    url: 'iphone-13.html'
};
products[24] = {
    id: 25,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: 'iPhone 13 với thiết kế thời thượng và hiệu năng vượt trội.',
    url: 'iphone-13.html'
};
products[25] = {
    id: 26,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: 'iPhone 13 với thiết kế thời thượng và hiệu năng vượt trội.',
    url: 'iphone-13.html'
};
products[26] = {
    id: 27,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: 'iPhone 13 với thiết kế thời thượng và hiệu năng vượt trội.',
    url: 'iphone-13.html'
};
products[27] = {
    id: 28,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: 'iPhone 13 với thiết kế thời thượng và hiệu năng vượt trội.',
    url: 'iphone-13.html'
};
products[28] = {
    id: 29,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: 'iPhone 13 với thiết kế thời thượng và hiệu năng vượt trội.',
    url: 'iphone-13.html'
};
products[29] = {
    id: 30,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: 'iPhone 13 với thiết kế thời thượng và hiệu năng vượt trội.',
    url: 'iphone-13.html'
};
products[30] = {
    id: 31,
    name: 'Điện thoại tàu khựa',
    image: 'https://onewaymobile.vn/images/products/2022/06/18/large/iphone-13-pro-oneway-1_1655520789.webp',
    description: 'iPhone 13 với thiết kế thời thượng và hiệu năng vượt trội.',
    url: 'iphone-13.html'
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
    prevButton.textContent = 'Trước';
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
    nextButton.textContent = 'Tiếp';
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
}

// Hiển thị trang đầu tiên
changePage(1);
