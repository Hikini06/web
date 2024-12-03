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

            // If subcategories already loaded, render them
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
            subcategoryLink.textContent = subcategory.name;

            // Add click event for subcategory-link-mobi
            subcategoryLink.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation(); // Prevent event bubbling

                const subcategoryId = subcategoryLink.getAttribute('data-subcategory-id');
                const allItemsMobi = document.querySelectorAll('.items-mobi.slide-down-active');
                allItemsMobi.forEach(items => {
                    items.classList.remove('slide-down-active');
                });
                // Toggle items-mobi with slide effect
                let itemsUl = subcategoryLi.querySelector('.items-mobi');
                if (itemsUl) {
                    itemsUl.classList.toggle('slide-down-active');
                } else {
                    if (itemCache[subcategoryId]) {
                        renderItems(itemCache[subcategoryId], subcategoryLi);
                    } else {
                        // Fetch items via AJAX
                        fetch(`get_items.php?subcategory_id=${subcategoryId}`)
                            .then(response => response.json())
                            .then(itemData => {
                                if (itemData.success) {
                                    itemCache[subcategoryId] = itemData.data; // Cache data
                                    renderItems(itemData.data, subcategoryLi);
                                } else {
                                    console.error(itemData.message);
                                }
                            })
                            .catch(error => console.error('Error fetching items:', error));
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
            });
        }
    });

    // Prevent closing when clicking inside categories-mobi
    categoriesMobi.addEventListener('click', (e) => {
        e.stopPropagation();
    });
});
