// TABLE SẢN PHẨM
// Hiển thị `product-banner-jim-table` khi hover vào `product-banner-jim-sanpham-one`
document.addEventListener("DOMContentLoaded", function () {
    const sanphamOne = document.querySelector('.product-banner-jim-sanpham-one');
    const tableOne = document.querySelector('.product-banner-jim-table');
  
    // Hover vào .product-banner-jim-sanpham-one để hiển thị bảng
    sanphamOne.addEventListener('mouseenter', () => {
      tableOne.style.display = 'block';
    });
  
    // Hover ra khỏi .product-banner-jim-sanpham-one để ẩn bảng
    sanphamOne.addEventListener('mouseleave', () => {
      setTimeout(() => {
        if (!tableOne.matches(':hover')) {
          tableOne.style.display = 'none';
        }
      }, 100); // Delay để tránh bị tắt khi di chuyển chuột quá nhanh
    });
  
    // Giữ bảng hiển thị khi hover vào chính bảng
    tableOne.addEventListener('mouseenter', () => {
      tableOne.style.display = 'block';
    });
  
    // Ẩn bảng khi chuột rời khỏi bảng
    tableOne.addEventListener('mouseleave', () => {
      tableOne.style.display = 'none';
    });
  
    // Hiển thị `product-banner-jim-table-two` khi hover vào `product-banner-jim-sanpham-two`
    const sanphamTwo = document.querySelector('.product-banner-jim-sanpham-two');
    const tableTwo = document.querySelector('.product-banner-jim-table-two');
  
    // Hover vào .product-banner-jim-sanpham-two để hiển thị bảng
    sanphamTwo.addEventListener('mouseenter', () => {
      tableTwo.style.display = 'block';
    });
  
    // Hover ra khỏi .product-banner-jim-sanpham-two để ẩn bảng
    sanphamTwo.addEventListener('mouseleave', () => {
      setTimeout(() => {
        if (!tableTwo.matches(':hover')) {
          tableTwo.style.display = 'none';
        }
      }, 100); // Delay để tránh bị tắt khi di chuyển chuột quá nhanh
    });
  
    // Giữ bảng hiển thị khi hover vào chính bảng
    tableTwo.addEventListener('mouseenter', () => {
      tableTwo.style.display = 'block';
    });
  
    // Ẩn bảng khi chuột rời khỏi bảng
    tableTwo.addEventListener('mouseleave', () => {
      tableTwo.style.display = 'none';
    });
  });
  document.addEventListener('DOMContentLoaded', () => {
    // Hiển thị và ẩn bảng `product-banner-jim-table-three` khi hover vào `product-banner-jim-sanpham-three`
    const sanphamThree = document.querySelector('.product-banner-jim-sanpham-three');
    const tableThree = document.querySelector('.product-banner-jim-table-three');
  
    sanphamThree.addEventListener('mouseenter', () => {
      tableThree.style.display = 'block';
    });
  
    sanphamThree.addEventListener('mouseleave', () => {
      setTimeout(() => {
        if (!tableThree.matches(':hover')) {
          tableThree.style.display = 'none';
        }
      }, 100);
    });
  
    tableThree.addEventListener('mouseenter', () => {
      tableThree.style.display = 'block';
    });
  
    tableThree.addEventListener('mouseleave', () => {
      tableThree.style.display = 'none';
    });
  
    // Hiển thị và ẩn bảng `product-banner-jim-table-four` khi hover vào `product-banner-jim-sanpham-four`
    const sanphamFour = document.querySelector('.product-banner-jim-sanpham-four');
    const tableFour = document.querySelector('.product-banner-jim-table-four');
  
    sanphamFour.addEventListener('mouseenter', () => {
      tableFour.style.display = 'block';
    });
  
    sanphamFour.addEventListener('mouseleave', () => {
      setTimeout(() => {
        if (!tableFour.matches(':hover')) {
          tableFour.style.display = 'none';
        }
      }, 100);
    });
  
    tableFour.addEventListener('mouseenter', () => {
      tableFour.style.display = 'block';
    });
  
    tableFour.addEventListener('mouseleave', () => {
      tableFour.style.display = 'none';
    });
  
    // Hiển thị và ẩn bảng `product-banner-jim-table-five` khi hover vào `product-banner-jim-sanpham-five`
    const sanphamFive = document.querySelector('.product-banner-jim-sanpham-five');
    const tableFive = document.querySelector('.product-banner-jim-table-five');
  
    sanphamFive.addEventListener('mouseenter', () => {
      tableFive.style.display = 'block';
    });
  
    sanphamFive.addEventListener('mouseleave', () => {
      setTimeout(() => {
        if (!tableFive.matches(':hover')) {
          tableFive.style.display = 'none';
        }
      }, 100);
    });
  
    tableFive.addEventListener('mouseenter', () => {
      tableFive.style.display = 'block';
    });
  
    tableFive.addEventListener('mouseleave', () => {
      tableFive.style.display = 'none';
    });
  });
  
  
  