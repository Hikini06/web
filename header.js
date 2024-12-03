document.addEventListener('DOMContentLoaded', () => {
    // Mobile Menu Elements
    const headerHamburger = document.getElementById('header-hamburger');
    const headerCategoriesMobi = document.querySelector('.header-categories-mobi');
    const headerSubcategoriesMobi = document.querySelector('.header-subcategories-mobi');

    // Toggle header-categories-mobi and header-activeHamburger when clicking hamburger
    headerHamburger.addEventListener('click', (e) => {
        e.stopPropagation(); // Prevent event bubbling
        headerCategoriesMobi.classList.toggle('active');
        headerHamburger.classList.toggle('activeHamburger'); // Toggle activeHamburger class

        if (headerCategoriesMobi.classList.contains('active')) {
            headerSubcategoriesMobi.classList.remove('active-slide-in'); // Ensure subcategories are hidden
        } else {
            // Nếu menu bị đóng, loại bỏ lớp active từ tất cả các header-category-mobi
            headerRemoveActiveClasses();
        }
    });

    // Handle click on header-category-link-mobi
    const headerCategoryLinksMobi = document.querySelectorAll('.header-category-link-mobi');
    const headerSubcategoryCache = {}; // Cache for subcategories
    const headerItemCache = {}; // Cache for items

    headerCategoryLinksMobi.forEach(headerCategoryLink => {
        headerCategoryLink.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation(); // Prevent event bubbling

            const headerCategoryId = headerCategoryLink.getAttribute('data-category-id');

            // Loại bỏ lớp active từ tất cả các header-category-link-mobi
            headerRemoveActiveClasses();

            // Thêm lớp active vào header-category-link-mobi đang được nhấn
            headerCategoryLink.classList.add('active');

            // Nếu subcategories đã được tải, render chúng
            if (headerSubcategoryCache[headerCategoryId]) {
                headerRenderSubcategories(headerSubcategoryCache[headerCategoryId], headerCategoryId);
            } else {
                // Fetch subcategories via AJAX using XMLHttpRequest
                fetchSubcategories(headerCategoryId);
            }
        });
    });

    // Function to remove 'active' class from all header-category-link-mobi
    function headerRemoveActiveClasses() {
        headerCategoryLinksMobi.forEach(headerLink => {
            headerLink.classList.remove('active');
        });
    }

    // Function to fetch subcategories using XMLHttpRequest
    function fetchSubcategories(categoryId) {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `get_subcategories.php?category_id=${categoryId}`, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        if (data.success) {
                            headerSubcategoryCache[categoryId] = data.data; // Cache data
                            headerRenderSubcategories(data.data, categoryId);
                        } else {
                            console.error(data.message);
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                    }
                } else {
                    console.error('Error fetching subcategories:', xhr.statusText);
                }
            }
        };
        xhr.send();
    }

    // Function to render subcategories with slide-in effect
    function headerRenderSubcategories(headerSubcategories, headerCategoryId) {
        // Assign category_id to header-subcategories-mobi
        headerSubcategoriesMobi.setAttribute('data-category-id', headerCategoryId);

        // Clear previous subcategories
        headerSubcategoriesMobi.innerHTML = '';

        headerSubcategories.forEach(headerSubcategory => {
            const headerSubcategoryLi = document.createElement('li');
            headerSubcategoryLi.classList.add('header-subcategory-mobi');

            const headerSubcategoryLink = document.createElement('a');
            headerSubcategoryLink.href = `danh-muc/${headerSubcategory.id}`;
            headerSubcategoryLink.classList.add('header-subcategory-link-mobi');
            headerSubcategoryLink.setAttribute('data-subcategory-id', headerSubcategory.id);
            
            // Tạo văn bản cho liên kết
            const headerLinkText = document.createTextNode(headerSubcategory.name);
            headerSubcategoryLink.appendChild(headerLinkText);

            // Tạo và thêm biểu tượng chevron-down
            const headerIcon = document.createElement('i');
            headerIcon.classList.add('fa-solid', 'fa-chevron-down');
            headerSubcategoryLink.appendChild(headerIcon);

            // Add click event for header-subcategory-link-mobi
            headerSubcategoryLink.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation(); // Prevent event bubbling

                const headerSubcategoryId = headerSubcategoryLink.getAttribute('data-subcategory-id');
                let headerItemsUl = headerSubcategoryLi.querySelector('.header-items-mobi');

                // Kiểm tra xem itemsUl có tồn tại và đang mở không
                const headerIsActive = headerItemsUl && headerItemsUl.classList.contains('slide-down-active');

                // Đóng tất cả các header-items-mobi đang mở
                const headerAllItemsMobi = document.querySelectorAll('.header-items-mobi.slide-down-active');
                headerAllItemsMobi.forEach(headerItems => {
                    headerItems.classList.remove('slide-down-active');
                    // Tìm icon của items đang đóng và chuyển hướng biểu tượng về chevron-down
                    const headerRelatedIcon = headerItems.parentElement.querySelector('.header-subcategory-link-mobi i');
                    if (headerRelatedIcon) {
                        headerRelatedIcon.classList.remove('fa-chevron-up');
                        headerRelatedIcon.classList.add('fa-chevron-down');
                    }
                });

                if (headerIsActive) {
                    // Nếu itemsUl đang mở, đóng nó và chuyển hướng biểu tượng về chevron-down
                    headerItemsUl.classList.remove('slide-down-active');
                    headerIcon.classList.remove('fa-chevron-up');
                    headerIcon.classList.add('fa-chevron-down');
                } else {
                    // Nếu itemsUl chưa mở, mở nó
                    if (headerItemsUl) {
                        headerItemsUl.classList.add('slide-down-active');
                        headerIcon.classList.remove('fa-chevron-down');
                        headerIcon.classList.add('fa-chevron-up');
                    } else {
                        if (headerItemCache[headerSubcategoryId]) {
                            headerRenderItems(headerItemCache[headerSubcategoryId], headerSubcategoryLi);
                            // Chuyển hướng biểu tượng về chevron-up
                            headerIcon.classList.remove('fa-chevron-down');
                            headerIcon.classList.add('fa-chevron-up');
                        } else {
                            // Fetch items via AJAX using XMLHttpRequest
                            fetchItems(headerSubcategoryId, headerSubcategoryLi, headerIcon);
                        }
                    }
                }
            });

            headerSubcategoryLi.appendChild(headerSubcategoryLink);
            headerSubcategoriesMobi.appendChild(headerSubcategoryLi);
        });

        // Trigger the slide-in animation for header-subcategories-mobi
        headerSubcategoriesMobi.classList.add('active-slide-in');
    }

    // Function to fetch items using XMLHttpRequest
    function fetchItems(subcategoryId, headerSubcategoryLi, headerIcon) {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `get_items.php?subcategory_id=${subcategoryId}`, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    try {
                        const headerItemData = JSON.parse(xhr.responseText);
                        if (headerItemData.success) {
                            headerItemCache[subcategoryId] = headerItemData.data; // Cache data
                            headerRenderItems(headerItemData.data, headerSubcategoryLi);
                            // Chuyển hướng biểu tượng về chevron-up
                            headerIcon.classList.remove('fa-chevron-down');
                            headerIcon.classList.add('fa-chevron-up');
                        } else {
                            console.error(headerItemData.message);
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                    }
                } else {
                    console.error('Error fetching items:', xhr.statusText);
                }
            }
        };
        xhr.send();
    }

    // Function to render items with slide-down effect
    function headerRenderItems(headerItems, headerSubcategoryLi) {
        // Create ul.header-items-mobi and append to subcategory-li
        const headerItemsUl = document.createElement('ul');
        headerItemsUl.classList.add('header-items-mobi');

        headerItems.forEach(headerItem => {
            const headerItemLi = document.createElement('li');
            headerItemLi.classList.add('header-item-mobi');

            const headerItemLink = document.createElement('a');
            headerItemLink.href = `san-pham/${headerItem.id}`;
            headerItemLink.textContent = headerItem.name;

            headerItemLi.appendChild(headerItemLink);
            headerItemsUl.appendChild(headerItemLi);
        });

        headerSubcategoryLi.appendChild(headerItemsUl);

        // Trigger the slide-down animation by adding 'slide-down-active' class
        setTimeout(() => {
            headerItemsUl.classList.add('slide-down-active');
        }, 10); // Slight delay to allow DOM insertion
    }

    // Close mobile menu when clicking outside
    document.addEventListener('click', (e) => {
        if (!headerCategoriesMobi.contains(e.target) && !headerHamburger.contains(e.target)) {
            headerCategoriesMobi.classList.remove('active');
            headerHamburger.classList.remove('activeHamburger'); // Remove activeHamburger class
            headerSubcategoriesMobi.classList.remove('active-slide-in'); // Remove slide-in class
            // Hide items
            const headerAllItemsMobi = document.querySelectorAll('.header-items-mobi');
            headerAllItemsMobi.forEach(headerItemsUl => {
                headerItemsUl.classList.remove('slide-down-active');
                // Reset chevron icons
                const headerRelatedIcon = headerItemsUl.parentElement.querySelector('.header-subcategory-link-mobi i');
                if (headerRelatedIcon) {
                    headerRelatedIcon.classList.remove('fa-chevron-up');
                    headerRelatedIcon.classList.add('fa-chevron-down');
                }
            });
            // Loại bỏ lớp active từ tất cả các header-category-link-mobi
            headerRemoveActiveClasses();
        }
    });

    // Prevent closing when clicking inside header-categories-mobi
    headerCategoriesMobi.addEventListener('click', (e) => {
        e.stopPropagation();
    });
});
