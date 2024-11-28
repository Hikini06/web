// categories.js

const filterContainer = document.querySelector('.categories-filter ul');
const leftArrow = document.querySelector('.left-arrow');
const rightArrow = document.querySelector('.right-arrow');

// Biến theo dõi vị trí cuộn
let scrollAmount = 0;

// Hàm xử lý cuộn sang trái
leftArrow.addEventListener('click', () => {
    const containerWidth = document.querySelector('.categories-filter').offsetWidth;
    const maxScrollLeft = 0; // Giới hạn cuộn trái là 0
    scrollAmount -= containerWidth; // Di chuyển bằng chiều rộng khung nhìn
    if (scrollAmount < maxScrollLeft) scrollAmount = maxScrollLeft; // Không vượt quá trái
    filterContainer.style.transform = `translateX(-${scrollAmount}px)`;
});

// Hàm xử lý cuộn sang phải
rightArrow.addEventListener('click', () => {
    const containerWidth = document.querySelector('.categories-filter').offsetWidth;
    const maxScrollRight = filterContainer.scrollWidth - containerWidth; // Giới hạn cuộn phải
    scrollAmount += containerWidth; // Di chuyển bằng chiều rộng khung nhìn
    if (scrollAmount > maxScrollRight) scrollAmount = maxScrollRight; // Không vượt quá phải
    filterContainer.style.transform = `translateX(-${scrollAmount}px)`;
});

// Thêm hỗ trợ thao tác vuốt trên thiết bị di động
let startX;
let isDragging = false;

filterContainer.addEventListener('touchstart', (e) => {
    startX = e.touches[0].clientX;
    isDragging = true;
});

filterContainer.addEventListener('touchmove', (e) => {
    if (!isDragging) return;
    const currentX = e.touches[0].clientX;
    const diffX = startX - currentX;
    scrollAmount += diffX;
    
    const containerWidth = document.querySelector('.categories-filter').offsetWidth;
    const maxScrollRight = filterContainer.scrollWidth - containerWidth;
    if (scrollAmount < 0) scrollAmount = 0;
    if (scrollAmount > maxScrollRight) scrollAmount = maxScrollRight;
    
    filterContainer.style.transform = `translateX(-${scrollAmount}px)`;
    startX = currentX;
});

filterContainer.addEventListener('touchend', () => {
    isDragging = false;
});
