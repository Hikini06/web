// home.js
document.addEventListener("DOMContentLoaded", function () {
    // Khởi tạo Swiper cho Slider 1
    const swiper1 = new Swiper(".slider-container-one", {
        slidesPerView: 4,
        spaceBetween: 20,
        navigation: {
            nextEl: ".slider-container-one .swiper-button-next",
            prevEl: ".slider-container-one .swiper-button-prev",
        },
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        breakpoints: {
            320: { slidesPerView: 1, spaceBetween: 10 },
            480: { slidesPerView: 2, spaceBetween: 15 },
            768: { slidesPerView: 3, spaceBetween: 20 },
            1024: { slidesPerView: 4, spaceBetween: 20 },
        },
    });

    // Khởi tạo Swiper cho Slider 2
    const swiper2 = new Swiper(".slider-container-two", {
        slidesPerView: 4,
        spaceBetween: 20,
        navigation: {
            nextEl: ".slider-container-two .swiper-button-next",
            prevEl: ".slider-container-two .swiper-button-prev",
        },
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        breakpoints: {
            320: { slidesPerView: 1, spaceBetween: 10 },
            480: { slidesPerView: 2, spaceBetween: 15 },
            768: { slidesPerView: 3, spaceBetween: 20 },
            1024: { slidesPerView: 4, spaceBetween: 20 },
        },
    });

    // Khởi tạo Swiper cho Hero Banner
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
});
