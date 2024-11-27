document.addEventListener("DOMContentLoaded", function () {
    // Hàm khởi tạo Swiper cho slider sản phẩm
    function initializeProductSlider(containerSelector, slidesPerViewDesktop, isFreeMode = false) {
        return new Swiper(containerSelector, {
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
            freeModeMomentum: isFreeMode,
            freeModeMomentumRatio: 1,
            freeModeMomentumBounce: isFreeMode,
            speed: isFreeMode ? 600 : 400,
        });
    }

    // Hàm khởi tạo Swiper cho categories section
    function initializeCategoriesSlider(containerSelector, slidesPerViewDesktop, isFreeMode = false) {
        return new Swiper(containerSelector, {
            slidesPerView: slidesPerViewDesktop,
            spaceBetween: 20,
            loop: false,
            navigation: {
                nextEl: `${containerSelector} .swiper-button-next`,
                prevEl: `${containerSelector} .swiper-button-prev`,
            },
            pagination: {
                el: `${containerSelector} .swiper-pagination`,
                clickable: true,
            },
            breakpoints: {
                0: {
                    slidesPerView: 3, // Hiển thị 3 sản phẩm trên mobile
                    spaceBetween: 10,
                },
                768: {
                    slidesPerView: slidesPerViewDesktop,
                    spaceBetween: 20,
                },
            },
            freeMode: isFreeMode,
            freeModeMomentum: isFreeMode,
            freeModeMomentumRatio: 0.5,
            freeModeMomentumBounce: isFreeMode,
        });
    }

    // Hàm khởi tạo Swiper cho Hero Banner
    function initializeHeroBanner(containerSelector) {
        return new Swiper(containerSelector, {
            slidesPerView: 1, // Hiển thị 1 slide
            spaceBetween: 10,
            loop: true,
            autoplay: {
                delay: 3000, // Tự động chuyển slide
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
        });
    }

    // Khởi tạo từng phần với hàm riêng biệt
    const sliderOne = initializeProductSlider(".slider-container-one", 4, true); // FreeMode bật
    const sliderTwo = initializeProductSlider(".slider-container-two", 4, true); // FreeMode bật
    const heroBanner = initializeHeroBanner(".hero-banner"); // Hero banner
    const categoriesSlider = initializeCategoriesSlider(".categories-section", 6, true); // Categories section
});
