// filter.js

document.addEventListener('DOMContentLoaded', function() {
    // Xử lý nút "Xem thêm"
    const loadMoreButton = document.getElementById('load-more');
    if (loadMoreButton) {
        console.log('Load More button found'); // Debug
        loadMoreButton.addEventListener('click', function() {
            console.log('Load More button clicked'); // Debug
            const button = this;
            const offset = parseInt(button.getAttribute('data-offset'));
            const query = button.getAttribute('data-query');
            
            // Xác định nếu thiết bị là di động bằng cách sử dụng matchMedia
            const isMobile = window.matchMedia("(max-width: 768px)").matches;
            const limit = isMobile ? 10 : 16;
            console.log(`Device is ${isMobile ? 'mobile' : 'desktop'}. Limit set to ${limit}`); // Debug

            const container = document.getElementById('product-container');

            fetch(`filter.php?q=${encodeURIComponent(query)}&offset=${offset}&limit=${limit}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('AJAX response:', data); // Debug
                if (data.results && data.results.length > 0) {
                    const grid = container.querySelector('.product-grid');
                    data.results.forEach(product => {
                        const item = document.createElement('div');
                        item.className = 'product-item';
                        item.innerHTML = `
                            <a href="product-detail.php?id=${product.id}">
                                <img src="${product.img}" alt="${product.name}">
                                <h3>${product.name}</h3>
                                <p>${new Intl.NumberFormat().format(product.price)}đ</p>
                            </a>
                        `;
                        grid.appendChild(item);
                    });

                    // Cập nhật offset
                    button.setAttribute('data-offset', offset + data.results.length);
                    console.log(`Updated offset to ${offset + data.results.length}`); // Debug

                    // Ẩn nút nếu không còn sản phẩm
                    if (!data.hasMore) {
                        button.style.display = 'none';
                        console.log('No more products. Load More button hidden'); // Debug
                    }
                } else {
                    button.style.display = 'none';
                    console.log('No results returned. Load More button hidden'); // Debug
                }
            })
            .catch(error => console.error('Error loading more products:', error));
        });
    } else {
        console.log('Load More button not found'); // Debug
    }
});
