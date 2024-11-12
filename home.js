console.log("JavaScript file loaded");
// khung search
document.addEventListener("DOMContentLoaded", function () {
  const searchToggle = document.getElementById("searchToggle");
  const searchContainer = document.getElementById("searchContainer");
  const searchForm = document.getElementById("searchForm");
  const searchBox = document.getElementById("searchBox");
  //   const searchForm = document.getElementById("searchForm");
  //   const searchBox = document.getElementById("searchBox");

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
    // if (searchContainer.classList.contains("active")) {
    //   searchBox.focus();
    // }
  });
  // Đóng ô tìm kiếm khi click bên ngoài
  document.addEventListener("click", function (e) {
    if (
      !searchContainer.contains(e.target) &&
      !searchToggle.contains(e.target)
    ) {
      searchContainer.classList.remove("active");
    }
  });
  ///////////////////
  searchForm.addEventListener("submit", function (e) {
    if (searchBox.value.trim() === "") {
      e.preventDefault();
      alert("Vui lòng nhập từ khóa tìm kiếm");
    }
    searchBox.addEventListener("keypress", function (e) {
      if (e.key === "Enter") {
        e.preventDefault(); // Ngăn chặn hành vi mặc định của form
        searchForm.submit(); // Submit form
      }
    });
    document.addEventListener("click", function (e) {
      if (
        !searchContainer.contains(e.target) &&
        !searchToggle.contains(e.target)
      ) {
        searchContainer.classList.remove("active");
      }
    });
  });
});
// trình chiếu hình ảnh
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
    slideIndex = 1 ;
  }
  if (slideIndex < 1) {
    slideIndex = slides.length;
  }
  for (let i = 0; i < slides.length; i++) {
    slides[i].classList.remove("active");
  }
  slides[slideIndex - 1].classList.add("active");
}
// Khởi tạo trình chiếu khi trang được tải
document.addEventListener("DOMContentLoaded", function() {
  showSlides();
});

// <!-- js test -->
//     <!-- <script>
//       // JavaScript để hiển thị lần lượt 4 thẻ div con
//       let itemsPerPage = 4;
//       let currentPage = 0;
  
//       function showMoreItems() {
//           const items = document.querySelectorAll('.item');
//           const totalItems = items.length;
//           const start = currentPage * itemsPerPage;
//           const end = start + itemsPerPage;
  
//           for (let i = start; i < end && i < totalItems; i++) {
//               items[i].style.display = 'block';
//           }
  
//           currentPage++;
  
//           // Ẩn nút "Xem thêm" khi hiển thị hết các phần tử
//           if (end >= totalItems) {
//               document.querySelector('.show-more-btn').style.display = 'none';
//           }
//       }
  
//       // Gọi hàm showMoreItems() để hiển thị 4 thẻ div đầu tiên khi tải trang
//       showMoreItems();
//   </script> -->
//   <!-- end test ở đây -->
// document.addEventListener('DOMContentLoaded', function() {
//   const productGrid = document.querySelector('.product-grid');
//   const prevButton = document.querySelector('.slider-button.prev');
//   const nextButton = document.querySelector('.slider-button.next');
//   const productItems = document.querySelectorAll('.product-item');
  
//   let currentIndex = 0;
//   const itemWidth = productItems[0].offsetWidth + 20; // 20 là giá trị gap
//   const maxIndex = productItems.length - Math.floor(productGrid.offsetWidth / itemWidth);

//   function updateSliderPosition() {
//       productGrid.style.transform = `translateX(-${currentIndex * itemWidth}px)`;
//   }

//   function updateButtonState() {
//       prevButton.style.opacity = currentIndex === 0 ? '0.5' : '1';
//       nextButton.style.opacity = currentIndex === maxIndex ? '0.5' : '1';
//   }

//   prevButton.addEventListener('click', () => {
//       if (currentIndex > 0) {
//           currentIndex--;
//           updateSliderPosition();
//           updateButtonState();
//       }
//   });

//   nextButton.addEventListener('click', () => {
//       if (currentIndex < maxIndex) {
//           currentIndex++;
//           updateSliderPosition();
//           updateButtonState();
//       }
//   });

//   // Khởi tạo trạng thái ban đầu của các nút
//   updateButtonState();

//   // Xử lý responsive
//   window.addEventListener('resize', () => {
//       const newMaxIndex = productItems.length - Math.floor(productGrid.offsetWidth / itemWidth);
//       if (newMaxIndex !== maxIndex) {
//           maxIndex = newMaxIndex;
//           currentIndex = Math.min(currentIndex, maxIndex);
//           updateSliderPosition();
//           updateButtonState();
//       }
//   });
// });
document.addEventListener('DOMContentLoaded', function() {
  const productGrid = document.querySelector('#product-list .product-grid');
  const prevButton = document.querySelector('#product-list #prevButton');
  const nextButton = document.querySelector('#product-list #nextButton');
  let currentPosition = 0;

  nextButton.addEventListener('click', () => {
    if (currentPosition > -(productGrid.children.length - 4) * 25) {
      currentPosition -= 25;
      productGrid.style.transform = `translateX(${currentPosition}%)`;
    }
  });

  prevButton.addEventListener('click', () => {
    if (currentPosition < 0) {
      currentPosition += 25;
      productGrid.style.transform = `translateX(${currentPosition}%)`;
    }
  });
});

// slider jim làm test
let currentIndex = 0;
const itemsToShow = 4; // Số lượng sản phẩm hiển thị mỗi lần
const totalItems = 10; // Tổng số sản phẩm

function updateSliderPosition() {
    const slider = document.querySelector('.slider');
    const itemWidth = document.querySelector('.slider-item-one').offsetWidth;
    slider.style.transform = `translateX(${-currentIndex * itemWidth}px)`;
}

function slideLeft() {
    if (currentIndex > 0) {
        currentIndex--;
        updateSliderPosition();
    }
}

function slideRight() {
    if (currentIndex < totalItems - itemsToShow) {
        currentIndex++;
        updateSliderPosition();
    }
}

window.addEventListener('resize', updateSliderPosition); // Cập nhật khi thay đổi kích thước