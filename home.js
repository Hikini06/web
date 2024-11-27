// home.js
document.addEventListener("DOMContentLoaded", function () {
    // Hàm khởi tạo Swiper với cấu hình responsive
    function initializeSwiper(containerSelector, slidesPerViewDesktop) {
        const swiperInstance = new Swiper(containerSelector, {
            slidesPerView: slidesPerViewDesktop, // Số lượng slides hiển thị trên desktop
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
                    slidesPerView: 3, // Ba slide trên mobile
                    spaceBetween: 10,
                },
                768: {
                    slidesPerView: slidesPerViewDesktop, // Sáu slide trên desktop
                    spaceBetween: 20,
                },
            },
        });
        return swiperInstance;
    }

    // Khởi tạo Swiper cho Slider 1 với 4 slides trên desktop
    const swiper1 = initializeSwiper(".slider-container-one", 4);

    // Khởi tạo Swiper cho Slider 2 với 4 slides trên desktop
    const swiper2 = initializeSwiper(".slider-container-two", 4);

    // Khởi tạo Swiper cho Hero Banner với cấu hình riêng
    const heroSwiper = new Swiper(".hero-banner", {
        slidesPerView: 1,
        spaceBetween: 10,
        loop: true,
        autoplay: {
            delay: 3000, // Thời gian giữa các slide (ms)
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: ".hero-banner .swiper-button-next",
            prevEl: ".hero-banner .swiper-button-prev",
        },
        pagination: {
            el: ".hero-banner .swiper-pagination",
            clickable: true,
        },
    });

    // Khởi tạo Swiper cho Categories Section với 6 slides trên desktop
    const categoriesSwiper = initializeSwiper(".categories-section", 6);
});
