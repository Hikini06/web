document.addEventListener("DOMContentLoaded", function () {
    function initializeSwiper(containerSelector, slidesPerViewDesktop, isFreeMode = false) {
        const swiperInstance = new Swiper(containerSelector, {
            slidesPerView: slidesPerViewDesktop, // Số lượng slides trên desktop
            spaceBetween: 20, // Khoảng cách giữa các slides (px)
            loop: false, // Không lặp lại
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: `${containerSelector} .swiper-button-next`,
                prevEl: `${containerSelector} .swiper-button-prev`,
            },
            pagination: {
                el: `${containerSelector} .swiper-pagination`,
                clickable: true,
            },
            breakpoints: {
                // Cấu hình responsive cho từng breakpoint
                0: {
                    slidesPerView: 2, // Số lượng slide hiển thị trên mobile
                    spaceBetween: 10,
                },
                768: {
                    slidesPerView: slidesPerViewDesktop, // Số lượng slide hiển thị trên desktop
                    spaceBetween: 20,
                },
            },
            freeMode: isFreeMode, // Kích hoạt freeMode nếu được chỉ định
            freeModeMomentum: isFreeMode, // Tạo hiệu ứng quán tính
            freeModeMomentumRatio: 1, // Tỷ lệ quán tính (càng lớn, slider chạy càng xa trước khi dừng)
            freeModeMomentumBounce: isFreeMode, // Hiệu ứng bật lại khi đạt biên giới
        });
        return swiperInstance;
    }

    // Khởi tạo Swiper với freeMode
    const swiper1 = initializeSwiper(".slider-container-one", 4, true); // Bật freeMode
    const swiper2 = initializeSwiper(".slider-container-two", 4, true); // Bật freeMode
});
