// footer.js

// GUIDE SECTION SLIDER JS START HERE
const guideSectionImageSlider = document.querySelector('.guideSection-image-slider');
const guideSectionImageSliderContainer = document.querySelector('.guideSection-image-slider-container');
const guideSectionPrevButton = document.querySelector('.guideSection-slider-prev-btn');
const guideSectionNextButton = document.querySelector('.guideSection-slider-next-btn');

let guideSectionCurrentIndex = 0;
let guideSectionVisibleImages = getVisibleImages(); // Động xác định số lượng hình ảnh hiển thị
let guideSectionSlideWidth = guideSectionImageSliderContainer.offsetWidth / guideSectionVisibleImages; // Tính chiều rộng mỗi ảnh
const guideSectionTotalImages = document.querySelectorAll('.guideSection-image-slider .guideSection-image-card').length;
let guideSectionAutoSlideInterval; // Biến lưu interval cho auto slide

// Hàm xác định số lượng hình ảnh hiển thị dựa trên kích thước màn hình
function getVisibleImages() {
    return window.innerWidth <= 768 ? 1 : 3; // 1 trên mobile, 3 trên desktop
}

// Hàm cập nhật chiều rộng mỗi slide
function updateSlideWidth() {
    guideSectionSlideWidth = guideSectionImageSliderContainer.offsetWidth / guideSectionVisibleImages;
}

// Hàm cập nhật vị trí slider
function guideSectionUpdateSlider() {
    guideSectionImageSlider.style.transform = `translateX(-${guideSectionCurrentIndex * guideSectionSlideWidth}px)`;
    guideSectionUpdateButtons();
}

// Hàm cập nhật trạng thái của nút Prev và Next
function guideSectionUpdateButtons() {
    guideSectionPrevButton.disabled = guideSectionCurrentIndex === 0;
    guideSectionNextButton.disabled = guideSectionCurrentIndex >= guideSectionTotalImages - guideSectionVisibleImages;
}

// Hàm tự động chuyển slide
function guideSectionAutoSlide() {
    guideSectionCurrentIndex++;
    if (guideSectionCurrentIndex > guideSectionTotalImages - guideSectionVisibleImages) {
        guideSectionCurrentIndex = 0; // Quay về slide đầu tiên
    }
    guideSectionUpdateSlider();
}

// Hàm dừng tự động slide
function guideSectionStopAutoSlide() {
    clearInterval(guideSectionAutoSlideInterval);
}

// Hàm khởi động lại tự động slide
function guideSectionRestartAutoSlide() {
    guideSectionStopAutoSlide();
    guideSectionAutoSlideInterval = setInterval(guideSectionAutoSlide, 10000); // Đặt lại 10 giây
}

// Sự kiện khi nhấn nút Prev
guideSectionPrevButton.addEventListener('click', () => {
    if (guideSectionCurrentIndex > 0) {
        guideSectionCurrentIndex--;
        guideSectionUpdateSlider();
    }
    guideSectionRestartAutoSlide(); // Khởi động lại timer sau khi nhấn
});

// Sự kiện khi nhấn nút Next
guideSectionNextButton.addEventListener('click', () => {
    if (guideSectionCurrentIndex < guideSectionTotalImages - guideSectionVisibleImages) {
        guideSectionCurrentIndex++;
        guideSectionUpdateSlider();
    }
    guideSectionRestartAutoSlide(); // Khởi động lại timer sau khi nhấn
});

// Hàm điều chỉnh slider khi thay đổi kích thước màn hình
function guideSectionResizeHandler() {
    // Xác định lại số lượng hình ảnh hiển thị
    const newVisibleImages = getVisibleImages();
    if (newVisibleImages !== guideSectionVisibleImages) {
        guideSectionVisibleImages = newVisibleImages;
        guideSectionCurrentIndex = 0; // Reset về slide đầu tiên
        updateSlideWidth();
        guideSectionUpdateSlider();
        guideSectionUpdateButtons();
    }
}

// Hàm khởi động slider
function guideSectionInitialize() {
    updateSlideWidth();
    guideSectionUpdateSlider();
    guideSectionUpdateButtons();
    guideSectionAutoSlideInterval = setInterval(guideSectionAutoSlide, 3000); // Tự động slide mỗi 3 giây
}

// Kích hoạt khi DOM đã tải
guideSectionInitialize();

// Lắng nghe sự kiện thay đổi kích thước màn hình
window.addEventListener('resize', guideSectionResizeHandler);
// GUIDE SECTION SLIDER JS END HERE
