// home.js

console.log("JavaScript file loaded");

// Khung search
document.addEventListener("DOMContentLoaded", function () {
    const searchToggle = document.getElementById("searchToggle");
    const searchContainer = document.getElementById("searchContainer");
    const searchForm = document.getElementById("searchForm");
    const searchBox = document.getElementById("searchBox");

    document.addEventListener("click", function (event) {
        if (
            !searchContainer.contains(event.target) &&
            !searchToggle.contains(event.target)
        ) {
            searchContainer.classList.remove("active");
        }
    });

    searchToggle.addEventListener("click", function (e) {
        e.preventDefault();
        searchContainer.classList.toggle("active");
    });

    searchForm.addEventListener("submit", function (e) {
        if (searchBox.value.trim() === "") {
            e.preventDefault();
            alert("Vui lòng nhập từ khóa tìm kiếm");
        }
    });
});

// Trình chiếu hình ảnh
let slideIndex = 0;
showSlides();
const slides = document.getElementsByClassName("mySlides");

function showSlides() {
    let i;
    let slides = document.getElementsByClassName("mySlides");
    for (i = 0; i < slides.length; i++) {
        slides[i].classList.remove("active");
    }
    slideIndex++;
    if (slideIndex > slides.length) {
        slideIndex = 1;
    }
    slides[slideIndex - 1].classList.add("active");
    setTimeout(showSlides, 5000); // Thay đổi ảnh mỗi 5 giây
}

function plusSlides(n) {
    slideIndex += n;
    let slides = document.getElementsByClassName("mySlides");
    if (slideIndex > slides.length) {
        slideIndex = 1;
    }
    if (slideIndex < 1) {
        slideIndex = slides.length;
    }
    for (let i = 0; i < slides.length; i++) {
        slides[i].classList.remove("active");
    }
    slides[slideIndex - 1].classList.add("active");
}

// Slider 1
let currentIndexSlider1 = 0;
const itemsToShowSlider1 = 4; // Số sản phẩm hiển thị mỗi lần

function updateSliderPositionSlider1() {
    const slider = document.querySelector(".slider-container-one .slider");
    const itemWidth = document.querySelector(".slider-item-one").offsetWidth;
    slider.style.transform = `translateX(${-currentIndexSlider1 * (itemWidth + 20)}px)`; // 20px là khoảng cách giữa các item
}

function slideLeft() {
    if (currentIndexSlider1 > 0) {
        currentIndexSlider1--;
        updateSliderPositionSlider1();
    }
}

function slideRight() {
    const slider = document.querySelector(".slider-container-one .slider");
    const totalItems = slider.children.length;
    const visibleItems = itemsToShowSlider1;
    if (currentIndexSlider1 < totalItems - visibleItems) {
        currentIndexSlider1++;
        updateSliderPositionSlider1();
    }
}

// Slider 2
let currentIndexSlider2 = 0;
const itemsToShowSlider2 = 4; // Số sản phẩm hiển thị mỗi lần

function updateSliderPositionSlider2() {
    const slider = document.querySelector(".slider-container-two .slider-two");
    const itemWidth = document.querySelector(".slider-item-two").offsetWidth;
    slider.style.transform = `translateX(${-currentIndexSlider2 * (itemWidth + 20)}px)`; // 20px là khoảng cách giữa các item
}

function slideLeftTwo() {
    if (currentIndexSlider2 > 0) {
        currentIndexSlider2--;
        updateSliderPositionSlider2();
    }
}

function slideRightTwo() {
    const slider = document.querySelector(".slider-container-two .slider-two");
    const totalItems = slider.children.length;
    const visibleItems = itemsToShowSlider2;
    if (currentIndexSlider2 < totalItems - visibleItems) {
        currentIndexSlider2++;
        updateSliderPositionSlider2();
    }
}

// Cập nhật lại vị trí slider khi thay đổi kích thước cửa sổ
window.addEventListener("resize", function() {
    updateSliderPositionSlider1();
    updateSliderPositionSlider2();
});
