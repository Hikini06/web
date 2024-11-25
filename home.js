document.addEventListener("DOMContentLoaded", function () {
    // Khởi tạo Swiper hoặc các logic khác
    const swiper1 = new Swiper(".slider-container-one", {
        slidesPerView: 4,
        spaceBetween: 20,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        loop: true,
        autoplay: {
            delay: 5000,
        },
    });

    const swiper2 = new Swiper(".slider-container-two", {
        slidesPerView: 4,
        spaceBetween: 20,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        loop: true,
        autoplay: {
            delay: 5000,
        },
    });
});
