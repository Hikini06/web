// function search() {
//     const products = document.querySelectorAll('.product-item');

//     products.forEach(product => {
//         const productName = product.querySelector('h3').textContent.toLowerCase();
//         const productDescription = product.querySelector('p').textContent.toLowerCase();

//         if (productName.includes(searchTerm) || productDescription.includes(searchTerm)) {
//             product.style.display = 'block';
//         } else {
//             product.style.display = 'none';
//         }
//     });
// }

// // Đảm bảo rằng DOM đã được tải hoàn toàn trước khi thực thi script
// document.addEventListener('DOMContentLoaded', function() {
//     const searchIcon = document.querySelector('.search-icon');
//     const searchContainer = document.querySelector('.search-container');

//     searchIcon.addEventListener('click', function(e) {
//         e.preventDefault();
//         searchContainer.classList.toggle('active');
//     });


//     // Ẩn ô tìm kiếm khi click bên ngoài
//     document.addEventListener('click', function(e) {
//         if (!searchIcon.contains(e.target) && !searchContainer.contains(e.target)) {
//             searchContainer.classList.remove('active');
//         }
//     });

//     // Thêm function search() ở đây nếu bạn muốn sử dụng
//     function search() {
//         const searchTerm = document.getElementById('searchBox').value.toLowerCase();
//         // Thực hiện tìm kiếm ở đây
//         console.log('Searching for:', searchTerm);
//     }

//     // Thêm sự kiện lắng nghe cho ô tìm kiếm
//     document.getElementById('searchBox').addEventListener('input', search);

    
// });


// // menuToggle.addEventListener('click', function(event) {
// //     event.preventDefault();
// //     console.log('Menu button clicked');
// //     setTimeout(() => {
// //         menuNav.classList.toggle('active');
// //         menuToggle.classList.toggle('active');
// //         console.log('Menu nav classes:', menuNav.classList);
// //     }, 10);
// // });
// //  document.addEventListener('DOMContentLoaded', function() {
// //      const menuToggle = document.getElementById('menu-toggle');
// //      const menuNav = document.getElementById('menu-nav');

// //      if (menuToggle && menuNav) {
// //          menuToggle.addEventListener('click', function(event) {
// //              event.preventDefault();
// //              menuNav.classList.toggle('active');
// //              menuToggle.classList.toggle('active');
// //          });
// //      }

// //      if (searchIcon && searchContainer) {
// //          searchIcon.addEventListener('click', function() {
// //              searchContainer.classList.toggle('active');
// //          });
// //      }

//     //  // Đóng menu khi click bên ngoài
//     //  document.addEventListener('click', function(event) {
//     //      if (!menuNav.contains(event.target) && !menuToggle.contains(event.target)) {
//     //          menuNav.classList.remove('active');
//     //          menuToggle.classList.remove('active');
//     //      }
//     //  });
// //  });
document.addEventListener('DOMContentLoaded', function() {
    const searchIcon = document.querySelector('.search-icon');
    const searchContainer = document.querySelector('.search-container');
    const searchBox = document.getElementById('searchBox');

    searchIcon.addEventListener('click', function(e) {
        e.preventDefault();
        searchContainer.classList.toggle('active');
    });

    // Ẩn ô tìm kiếm khi click bên ngoài
    document.addEventListener('click', function(e) {
        if (!searchIcon.contains(e.target) && !searchContainer.contains(e.target)) {
            searchContainer.classList.remove('active');
        }
    });

    // Hàm tìm kiếm
    function search() {
        const searchTerm = searchBox.value.toLowerCase();
        const products = document.querySelectorAll('.product-item');

        products.forEach(product => {
            const productName = product.querySelector('h3').textContent.toLowerCase();
            const productDescription = product.querySelector('p').textContent.toLowerCase();

            if (productName.includes(searchTerm) || productDescription.includes(searchTerm)) {
                product.style.display = 'block';
            } else {
                product.style.display = 'none';
            }
        });
    }

    // Thêm sự kiện lắng nghe cho ô tìm kiếm
    searchBox.addEventListener('input', search);
});
