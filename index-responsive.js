
document.addEventListener("DOMContentLoaded", function () {
    function initializeSwiper() {
        const swiperContainer = document.querySelector(".swiper-slider-container .swiper");
        if (!swiperContainer) return;

        const totalSlides = swiperContainer.querySelectorAll(".swiper-slide").length;
        const slidesPerView = 2; 

    
        if (window.mobileSwiperInitialized) return;

        window.mobileSwiper = new Swiper(".swiper-slider-container .swiper", {
            slidesPerView: slidesPerView,
            spaceBetween: 20,
            loop: totalSlides > slidesPerView, 
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: ".swiper-slider-container .swiper-button-next",
                prevEl: ".swiper-slider-container .swiper-button-prev",
            },
            pagination: {
                el: ".swiper-slider-container .swiper-pagination",
                clickable: true,
            },
            breakpoints: {
                
            },
        });

        window.mobileSwiperInitialized = true;
    }

    function destroySwiper() {
        if (window.mobileSwiper && window.mobileSwiper.destroy) {
            window.mobileSwiper.destroy(true, true);
            window.mobileSwiper = null;
            window.mobileSwiperInitialized = false;
        }
    }

    function handleSwiperInitialization() {
        if (window.innerWidth <= 768) {
            initializeSwiper();
        } else {
            destroySwiper();
        }
    }

    handleSwiperInitialization();

    
    window.addEventListener("resize", function () {
        handleSwiperInitialization();
    });
});
