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

document.addEventListener('DOMContentLoaded', function() {
    // Xử lý nút "Xem thêm"
    const loadMoreButton = document.getElementById('load-more');
    if (loadMoreButton) {
        loadMoreButton.addEventListener('click', function() {
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
    }

    // Carousel cho sản phẩm gợi ý
    const track = document.querySelector('.carousel-track');
    if (track) {
        const slides = Array.from(track.children);
        const prevButton = document.querySelector('.carousel-prev');
        const nextButton = document.querySelector('.carousel-next');

        // Lấy giá trị gap từ CSS
        const gap = parseFloat(window.getComputedStyle(track).gap) || 0;

        // Tính toán slideWidth bao gồm cả gap
        let slideWidth = slides[0].getBoundingClientRect().width + gap;

        // Sắp xếp các slide cạnh nhau
        const setSlidePosition = () => {
            slides.forEach((slide, index) => {
                slide.style.left = (slideWidth * index) + 'px';
            });
        };
        setSlidePosition();

        let currentIndex = 0;
        const slidesToScroll = 4; // Số lượng slides cần cuộn mỗi lần

        // Hàm di chuyển slide
        const moveToSlide = (index) => {
            track.style.transform = 'translateX(-' + slideWidth * index + 'px)';
        };

        // Khi cửa sổ thay đổi kích thước, cập nhật slideWidth và vị trí
        window.addEventListener('resize', () => {
            slideWidth = slides[0].getBoundingClientRect().width + gap;
            setSlidePosition();
            moveToSlide(currentIndex);
        });

        // Hàm tính số slides hiển thị
        const getSlidesToShow = () => {
            const containerWidth = track.parentElement.getBoundingClientRect().width;
            const slideTotalWidth = slides[0].getBoundingClientRect().width + gap;
            return Math.floor(containerWidth / slideTotalWidth);
        };

        // Sự kiện khi nhấn nút next
        nextButton.addEventListener('click', () => {
            const slidesToShow = getSlidesToShow();
            const maxIndex = slides.length - slidesToShow;
            currentIndex += slidesToScroll;
            if (currentIndex > maxIndex) {
                currentIndex = maxIndex;
            }
            moveToSlide(currentIndex);
        });

        // Sự kiện khi nhấn nút prev
        prevButton.addEventListener('click', () => {
            currentIndex -= slidesToScroll;
            if (currentIndex < 0) {
                currentIndex = 0;
            }
            moveToSlide(currentIndex);
        });

        // Hiệu ứng hover phóng to sản phẩm trong carousel
        const carouselSlides = document.querySelectorAll('.carousel-slide');
        const body = document.body;

        carouselSlides.forEach(slide => {
            slide.addEventListener('mouseenter', function(event) {
                const slideInner = this.querySelector('.carousel-slide-inner');
                const rect = slideInner.getBoundingClientRect();

                // Tạo bản sao của slideInner
                const clone = slideInner.cloneNode(true);
                clone.classList.add('carousel-clone');

                // Thiết lập vị trí và kích thước cho clone
                clone.style.position = 'absolute';
                clone.style.top = rect.top + window.scrollY + 'px';
                clone.style.left = rect.left + window.scrollX + 'px';
                clone.style.width = rect.width + 'px';
                clone.style.height = rect.height + 'px';
                clone.style.transformOrigin = 'center center';
                clone.style.transition = 'transform 0.3s ease, box-shadow 0.3s ease';
                clone.style.zIndex = '9999';
                clone.style.pointerEvents = 'none'; // Thêm dòng này

                // Thêm clone vào body
                body.appendChild(clone);

                // Thêm hiệu ứng phóng to
                requestAnimationFrame(() => {
                    clone.style.transform = 'scale(1.05)';
                    clone.style.boxShadow = '0 6px 12px rgba(0, 0, 0, 0.2)';
                });

                // Lưu trữ clone để sử dụng khi mouseleave
                this._clone = clone;
            });

            slide.addEventListener('mouseleave', function(event) {
                const clone = this._clone;
                if (clone) {
                    // Thêm hiệu ứng thu nhỏ trước khi xóa
                    clone.style.transform = 'scale(1)';
                    clone.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';

                    // Xóa clone sau khi hiệu ứng kết thúc
                    setTimeout(() => {
                        if (clone && clone.parentNode) {
                            clone.parentNode.removeChild(clone);
                        }
                    }, 300);

                    // Xóa tham chiếu đến clone
                    this._clone = null;
                }
            });
        });
    }
});
