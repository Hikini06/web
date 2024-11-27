// filter-responsive.js

document.addEventListener('DOMContentLoaded', function() {
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
        let slidesToScroll = 4; // Số lượng slides cần cuộn mỗi lần

        // Hàm cập nhật slidesToScroll dựa trên kích thước màn hình
        const updateCarouselSettings = () => {
            const isMobile = window.matchMedia("(max-width: 768px)").matches;
            slidesToScroll = isMobile ? 2 : 4;
            console.log('Updated slidesToScroll:', slidesToScroll); // Debug
        };

        updateCarouselSettings(); // Khởi tạo lần đầu

        // Hàm di chuyển slide
        const moveToSlide = (index) => {
            track.style.transform = 'translateX(-' + slideWidth * index + 'px)';
            console.log('Moved to slide index:', index); // Debug
        };

        // Khi cửa sổ thay đổi kích thước, cập nhật slideWidth và slidesToScroll
        window.addEventListener('resize', () => {
            slideWidth = slides[0].getBoundingClientRect().width + gap;
            console.log('Resized slideWidth:', slideWidth); // Debug
            setSlidePosition();
            updateCarouselSettings();
            moveToSlide(currentIndex);
        });

        // Hàm tính số slides hiển thị
        const getSlidesToShow = () => {
            const containerWidth = track.parentElement.getBoundingClientRect().width;
            const slideTotalWidth = slides[0].getBoundingClientRect().width + gap;
            const slidesToShow = Math.floor(containerWidth / slideTotalWidth);
            console.log('Slides to show:', slidesToShow); // Debug
            return slidesToShow;
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
                if (!slideInner) return; // Kiểm tra nếu slideInner tồn tại

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
                clone.style.pointerEvents = 'none'; // Ngăn chặn sự kiện chuột trên clone

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

        // Chức năng vuốt bằng tay cho carousel
        let touchStartX = 0;
        let touchEndX = 0;
        const threshold = 50; // Ngưỡng độ dài swipe để thực hiện chuyển slide

        track.addEventListener('touchstart', function(event) {
            touchStartX = event.changedTouches[0].screenX;
        }, false);

        track.addEventListener('touchend', function(event) {
            touchEndX = event.changedTouches[0].screenX;
            handleGesture();
        }, false);

        const handleGesture = () => {
            const deltaX = touchEndX - touchStartX;
            console.log('Swipe deltaX:', deltaX); // Debug

            if (Math.abs(deltaX) > threshold) {
                if (deltaX < 0) {
                    // Swipe left - chuyển đến slide tiếp theo
                    const slidesToShow = getSlidesToShow();
                    const maxIndex = slides.length - slidesToShow;
                    currentIndex += slidesToScroll;
                    if (currentIndex > maxIndex) {
                        currentIndex = maxIndex;
                    }
                    moveToSlide(currentIndex);
                } else {
                    // Swipe right - chuyển đến slide trước
                    currentIndex -= slidesToScroll;
                    if (currentIndex < 0) {
                        currentIndex = 0;
                    }
                    moveToSlide(currentIndex);
                }
            }
        };
    }
});
