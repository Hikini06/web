// headernew.js

document.addEventListener('DOMContentLoaded', () => {
    // Mobile Menu Elements
    const hamburger = document.getElementById('hamburger');
    const categoriesMobi = document.querySelector('.categories-mobi');
    const subcategoriesMobi = document.querySelector('.subcategories-mobi');

    // Toggle categories-mobi and activeHamburger when clicking hamburger
    hamburger.addEventListener('click', (e) => {
        e.stopPropagation(); // Prevent event bubbling
        categoriesMobi.classList.toggle('active');
        hamburger.classList.toggle('activeHamburger'); // Toggle activeHamburger class

        if (categoriesMobi.classList.contains('active')) {
            subcategoriesMobi.classList.remove('active-slide-in'); // Ensure subcategories are hidden
        } else {
            // Nếu menu bị đóng, loại bỏ lớp active từ tất cả các category-mobi
            removeActiveClasses();
        }
    });

    // Handle click on category-mobi
    const categoryLinksMobi = document.querySelectorAll('.category-link-mobi');
    const subcategoryCache = {}; // Cache for subcategories
    const itemCache = {}; // Cache for items

    categoryLinksMobi.forEach(categoryLink => {
        categoryLink.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation(); // Prevent event bubbling

            const categoryId = categoryLink.getAttribute('data-category-id');

            // Loại bỏ lớp active từ tất cả các category-mobi
            removeActiveClasses();

            // Thêm lớp active vào category-mobi đang được nhấn
            categoryLink.classList.add('active');

            // Nếu subcategories đã được tải, render chúng
            if (subcategoryCache[categoryId]) {
                renderSubcategories(subcategoryCache[categoryId], categoryId);
            } else {
                // Fetch subcategories via AJAX
                fetch(`get_subcategories.php?category_id=${categoryId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            subcategoryCache[categoryId] = data.data; // Cache data
                            renderSubcategories(data.data, categoryId);
                        } else {
                            console.error(data.message);
                        }
                    })
                    .catch(error => console.error('Error fetching subcategories:', error));
            }
        });
    });

    // Function to remove 'active' class from all category-link-mobi
    function removeActiveClasses() {
        categoryLinksMobi.forEach(link => {
            link.classList.remove('active');
        });
    }

    // Function to render subcategories with slide-in effect
    function renderSubcategories(subcategories, categoryId) {
        // Assign category_id to subcategories-mobi
        subcategoriesMobi.setAttribute('data-category-id', categoryId);

        // Clear previous subcategories
        subcategoriesMobi.innerHTML = '';

        subcategories.forEach(subcategory => {
            const subcategoryLi = document.createElement('li');
            subcategoryLi.classList.add('subcategory-mobi');

            const subcategoryLink = document.createElement('a');
            subcategoryLink.href = `danh-muc/${subcategory.id}`;
            subcategoryLink.classList.add('subcategory-link-mobi');
            subcategoryLink.setAttribute('data-subcategory-id', subcategory.id);
            
            // Tạo văn bản cho liên kết
            const linkText = document.createTextNode(subcategory.name);
            subcategoryLink.appendChild(linkText);

            // Tạo và thêm biểu tượng chevron-down
            const icon = document.createElement('i');
            icon.classList.add('fa-solid', 'fa-chevron-down');
            subcategoryLink.appendChild(icon);

            // Add click event for subcategory-link-mobi
            subcategoryLink.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation(); // Prevent event bubbling

                const subcategoryId = subcategoryLink.getAttribute('data-subcategory-id');
                let itemsUl = subcategoryLi.querySelector('.items-mobi');

                // Kiểm tra xem itemsUl có tồn tại và đang mở không
                const isActive = itemsUl && itemsUl.classList.contains('slide-down-active');

                // Đóng tất cả các items-mobi đang mở
                const allItemsMobi = document.querySelectorAll('.items-mobi.slide-down-active');
                allItemsMobi.forEach(items => {
                    items.classList.remove('slide-down-active');
                    // Tìm icon của items đang đóng và chuyển hướng biểu tượng về chevron-down
                    const relatedIcon = items.parentElement.querySelector('.subcategory-link-mobi i');
                    if (relatedIcon) {
                        relatedIcon.classList.remove('fa-chevron-up');
                        relatedIcon.classList.add('fa-chevron-down');
                    }
                });

                if (isActive) {
                    // Nếu itemsUl đang mở, đóng nó và chuyển hướng biểu tượng về chevron-down
                    itemsUl.classList.remove('slide-down-active');
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                } else {
                    // Nếu itemsUl chưa mở, mở nó
                    if (itemsUl) {
                        itemsUl.classList.add('slide-down-active');
                        icon.classList.remove('fa-chevron-down');
                        icon.classList.add('fa-chevron-up');
                    } else {
                        if (itemCache[subcategoryId]) {
                            renderItems(itemCache[subcategoryId], subcategoryLi);
                            // Chuyển hướng biểu tượng về chevron-up
                            icon.classList.remove('fa-chevron-down');
                            icon.classList.add('fa-chevron-up');
                        } else {
                            // Fetch items via AJAX
                            fetch(`get_items.php?subcategory_id=${subcategoryId}`)
                                .then(response => response.json())
                                .then(itemData => {
                                    if (itemData.success) {
                                        itemCache[subcategoryId] = itemData.data; // Cache data
                                        renderItems(itemData.data, subcategoryLi);
                                        // Chuyển hướng biểu tượng về chevron-up
                                        icon.classList.remove('fa-chevron-down');
                                        icon.classList.add('fa-chevron-up');
                                    } else {
                                        console.error(itemData.message);
                                    }
                                })
                                .catch(error => console.error('Error fetching items:', error));
                        }
                    }
                }
            });

            subcategoryLi.appendChild(subcategoryLink);
            subcategoriesMobi.appendChild(subcategoryLi);
        });

        // Trigger the slide-in animation for subcategories-mobi
        subcategoriesMobi.classList.add('active-slide-in');
    }

    // Function to render items with slide-down effect
    function renderItems(items, subcategoryLi) {
        // Create ul.items-mobi and append to subcategory-li
        const itemsUl = document.createElement('ul');
        itemsUl.classList.add('items-mobi');

        items.forEach(item => {
            const itemLi = document.createElement('li');
            itemLi.classList.add('item-mobi');

            const itemLink = document.createElement('a');
            itemLink.href = `san-pham/${item.id}`;
            itemLink.textContent = item.name;

            itemLi.appendChild(itemLink);
            itemsUl.appendChild(itemLi);
        });

        subcategoryLi.appendChild(itemsUl);

        // Trigger the slide-down animation by adding 'slide-down-active' class
        setTimeout(() => {
            itemsUl.classList.add('slide-down-active');
        }, 10); // Slight delay to allow DOM insertion
    }

    // Close mobile menu when clicking outside
    document.addEventListener('click', (e) => {
        if (!categoriesMobi.contains(e.target) && !hamburger.contains(e.target)) {
            categoriesMobi.classList.remove('active');
            hamburger.classList.remove('activeHamburger'); // Remove activeHamburger class
            subcategoriesMobi.classList.remove('active-slide-in'); // Remove slide-in class
            // Hide items
            const allItemsMobi = document.querySelectorAll('.items-mobi');
            allItemsMobi.forEach(itemsUl => {
                itemsUl.classList.remove('slide-down-active');
                // Reset chevron icons
                // const relatedIcon = itemsUl.parentElement.querySelector('.subcategory-link-mobi i');
                // if (relatedIcon) {
                //     relatedIcon.classList.remove('fa-chevron-up');
                //     relatedIcon.classList.add('fa-chevron-down');
                // }
            });
            // Loại bỏ lớp active từ tất cả các category-mobi
            removeActiveClasses();
        }
    });

    // Prevent closing when clicking inside categories-mobi
    categoriesMobi.addEventListener('click', (e) => {
        e.stopPropagation();
    });
});
