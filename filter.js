document.getElementById('load-more')?.addEventListener('click', function() {
    const button = this;
    const offset = parseInt(button.getAttribute('data-offset'));
    const query = button.getAttribute('data-query');
    const container = document.getElementById('product-container');

    fetch(`filter.php?q=${encodeURIComponent(query)}&offset=${offset}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
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

            // Ẩn nút nếu không còn sản phẩm
            if (!data.hasMore) {
                button.style.display = 'none';
            }
        } else {
            button.style.display = 'none';
        }
    })
    .catch(error => console.error('Error loading more products:', error));
});
