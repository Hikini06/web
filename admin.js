document.addEventListener('DOMContentLoaded', function() {
    var currentPage = 1;
    var totalPages = 1;

    var sidebarButtons = document.querySelectorAll('.sidebar-button');
    sidebarButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            sidebarButtons.forEach(function(btn) {
                btn.classList.remove('active');
            });
            this.classList.add('active');
            var activeButtonId = this.id;
            localStorage.setItem('activeSidebarButton', activeButtonId);
        });
    });
    var activeSidebarButtonId = localStorage.getItem('activeSidebarButton');
    if (activeSidebarButtonId) {
        var activeButton = document.getElementById(activeSidebarButtonId);
        if (activeButton) {
            sidebarButtons.forEach(function(btn) {
                btn.classList.remove('active');
            });
            activeButton.classList.add('active');
        }
    } else {
        if (sidebarButtons.length > 0) {
            sidebarButtons[0].classList.add('active');
            localStorage.setItem('activeSidebarButton', sidebarButtons[0].id);
        }
    }

    var addCategoryBtns = document.querySelectorAll('.add-category-btn');
    addCategoryBtns.forEach(function(button) {
        button.addEventListener('click', function() {
            var categoryName = prompt('Nhập tên Category mới:');
            if (categoryName) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'add_category.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                var newOption = document.createElement('option');
                                newOption.value = response.data.id;
                                newOption.textContent = response.data.name;
                                document.getElementById('categoriesSelect').appendChild(newOption);
                                alert('Thêm Category thành công.');
                                updateButtons();
                            } else {
                                alert('Lỗi: ' + response.message);
                            }
                        } catch (e) {
                            alert('Phản hồi từ server không hợp lệ.');
                        }
                    } else {
                        alert('Yêu cầu không thành công. Mã lỗi: ' + xhr.status);
                    }
                };
                xhr.send('name=' + encodeURIComponent(categoryName));
            }
        });
    });

    var deleteCategoryBtns = document.querySelectorAll('.delete-category-btn');
    deleteCategoryBtns.forEach(function(button) {
        button.addEventListener('click', function() {
            var categoriesSelect = document.getElementById('categoriesSelect');
            var selectedCategoryId = categoriesSelect.value;
            var selectedCategoryName = categoriesSelect.options[categoriesSelect.selectedIndex].text;
            if (!selectedCategoryId) {
                alert('Vui lòng chọn một Category để xoá.');
                return;
            }
            if (confirm('Bạn có chắc chắn muốn xoá Category "' + selectedCategoryName + '"?')) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete_category.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                categoriesSelect.remove(categoriesSelect.selectedIndex);
                                var subcategoriesSelect = document.getElementById('subcategoriesSelect');
                                var itemsSelect = document.getElementById('itemsSelect');
                                var itemsDetailSelect = document.getElementById('itemsDetailSelect');
                                subcategoriesSelect.innerHTML = '<option value="">-- Chọn Subcategory --</option>';
                                subcategoriesSelect.disabled = true;
                                itemsSelect.innerHTML = '<option value="">-- Chọn Item --</option>';
                                itemsSelect.disabled = true;
                                itemsDetailSelect.innerHTML = '<option value="">-- Chọn Items Detail --</option>';
                                itemsDetailSelect.disabled = true;
                                alert('Đã xoá Category thành công.');
                                updateButtons();
                            } else {
                                alert('Lỗi: ' + response.message);
                            }
                        } catch (e) {
                            alert('Phản hồi từ server không hợp lệ.');
                        }
                    } else {
                        alert('Yêu cầu không thành công. Mã lỗi: ' + xhr.status);
                    }
                };
                xhr.send('id=' + encodeURIComponent(selectedCategoryId));
            }
        });
    });

    var addSubcategoryBtns = document.querySelectorAll('.add-subcategory-btn');
    addSubcategoryBtns.forEach(function(button) {
        button.addEventListener('click', function() {
            var categoriesSelect = document.getElementById('categoriesSelect');
            var selectedCategoryId = categoriesSelect.value;
            if (!selectedCategoryId) {
                alert('Vui lòng chọn Category trước khi thêm Subcategory.');
                return;
            }
            var subcategoryName = prompt('Nhập tên Subcategory mới:');
            if (subcategoryName) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'add_subcategory.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                var newOption = document.createElement('option');
                                newOption.value = response.data.id;
                                newOption.textContent = response.data.name;
                                document.getElementById('subcategoriesSelect').appendChild(newOption);
                                document.getElementById('subcategoriesSelect').disabled = false;
                                alert('Thêm Subcategory thành công.');
                                updateButtons();
                            } else {
                                alert('Lỗi: ' + response.message);
                            }
                        } catch (e) {
                            alert('Phản hồi từ server không hợp lệ.');
                        }
                    } else {
                        alert('Yêu cầu không thành công. Mã lỗi: ' + xhr.status);
                    }
                };
                xhr.send('name=' + encodeURIComponent(subcategoryName) + '&category_id=' + encodeURIComponent(selectedCategoryId));
            }
        });
    });

    var deleteSubcategoryBtns = document.querySelectorAll('.delete-subcategory-btn');
    deleteSubcategoryBtns.forEach(function(button) {
        button.addEventListener('click', function() {
            var subcategoriesSelect = document.getElementById('subcategoriesSelect');
            var selectedSubcategoryId = subcategoriesSelect.value;
            var selectedSubcategoryName = subcategoriesSelect.options[subcategoriesSelect.selectedIndex].text;
            if (!selectedSubcategoryId) {
                alert('Vui lòng chọn một Subcategory để xoá.');
                return;
            }
            if (confirm('Bạn có chắc chắn muốn xoá Subcategory "' + selectedSubcategoryName + '"?')) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete_subcategory.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                subcategoriesSelect.remove(subcategoriesSelect.selectedIndex);
                                var itemsSelect = document.getElementById('itemsSelect');
                                var itemsDetailSelect = document.getElementById('itemsDetailSelect');
                                itemsSelect.innerHTML = '<option value="">-- Chọn Item --</option>';
                                itemsSelect.disabled = true;
                                itemsDetailSelect.innerHTML = '<option value="">-- Chọn Items Detail --</option>';
                                itemsDetailSelect.disabled = true;
                                alert('Đã xoá Subcategory thành công.');
                                updateButtons();
                            } else {
                                alert('Lỗi: ' + response.message);
                            }
                        } catch (e) {
                            alert('Phản hồi từ server không hợp lệ.');
                        }
                    } else {
                        alert('Yêu cầu không thành công. Mã lỗi: ' + xhr.status);
                    }
                };
                xhr.send('id=' + encodeURIComponent(selectedSubcategoryId));
            }
        });
    });

    var addItemBtns = document.querySelectorAll('.add-item-btn');
    addItemBtns.forEach(function(button) {
        button.addEventListener('click', function() {
            var subcategoriesSelect = document.getElementById('subcategoriesSelect');
            var selectedSubcategoryId = subcategoriesSelect.value;
            if (!selectedSubcategoryId) {
                alert('Vui lòng chọn Subcategory trước khi thêm Item.');
                return;
            }
            var itemName = prompt('Nhập tên Item mới:');
            if (itemName) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'add_item.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                var newOption = document.createElement('option');
                                newOption.value = response.data.id;
                                newOption.textContent = response.data.name;
                                document.getElementById('itemsSelect').appendChild(newOption);
                                document.getElementById('itemsSelect').disabled = false;
                                alert('Thêm Item thành công.');
                                updateButtons();
                            } else {
                                alert('Lỗi: ' + response.message);
                            }
                        } catch (e) {
                            alert('Phản hồi từ server không hợp lệ.');
                        }
                    } else {
                        alert('Yêu cầu không thành công. Mã lỗi: ' + xhr.status);
                    }
                };
                xhr.send('name=' + encodeURIComponent(itemName) + '&subcategory_id=' + encodeURIComponent(selectedSubcategoryId));
            }
        });
    });

    var deleteItemBtns = document.querySelectorAll('.delete-item-btn');
    deleteItemBtns.forEach(function(button) {
        button.addEventListener('click', function() {
            var itemsSelect = document.getElementById('itemsSelect');
            var selectedItemId = itemsSelect.value;
            var selectedItemName = itemsSelect.options[itemsSelect.selectedIndex].text;
            if (!selectedItemId) {
                alert('Vui lòng chọn một Item để xoá.');
                return;
            }
            if (confirm('Bạn có chắc chắn muốn xoá Item "' + selectedItemName + '"?')) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete_item.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                itemsSelect.remove(itemsSelect.selectedIndex);
                                var itemsDetailSelect = document.getElementById('itemsDetailSelect');
                                itemsDetailSelect.innerHTML = '<option value="">-- Chọn Items Detail --</option>';
                                itemsDetailSelect.disabled = true;
                                alert('Đã xoá Item thành công.');
                                updateButtons();
                            } else {
                                alert('Lỗi: ' + response.message);
                            }
                        } catch (e) {
                            alert('Phản hồi từ server không hợp lệ.');
                        }
                    } else {
                        alert('Yêu cầu không thành công. Mã lỗi: ' + xhr.status);
                    }
                };
                xhr.send('id=' + encodeURIComponent(selectedItemId));
            }
        });
    });

    var addItemDetailBtns = document.querySelectorAll('.add-item-detail-btn');
    addItemDetailBtns.forEach(function(button) {
        button.addEventListener('click', function() {
            var itemsSelect = document.getElementById('itemsSelect');
            var selectedItemId = itemsSelect.value;
            if (!selectedItemId) {
                alert('Vui lòng chọn Item trước khi thêm Items Detail.');
                return;
            }
            var itemDetailName = prompt('Nhập tên Items Detail mới:');
            if (itemDetailName) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'add_item_detail.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                var newOption = document.createElement('option');
                                newOption.value = response.data.id;
                                newOption.textContent = response.data.name;
                                document.getElementById('itemsDetailSelect').appendChild(newOption);
                                document.getElementById('itemsDetailSelect').disabled = false;
                                alert('Thêm Items Detail thành công.');
                                updateButtons();
                            } else {
                                alert('Lỗi: ' + response.message);
                            }
                        } catch (e) {
                            alert('Phản hồi từ server không hợp lệ.');
                        }
                    } else {
                        alert('Yêu cầu không thành công. Mã lỗi: ' + xhr.status);
                    }
                };
                xhr.send('name=' + encodeURIComponent(itemDetailName) + '&item_id=' + encodeURIComponent(selectedItemId));
            }
        });
    });

    var deleteItemDetailBtns = document.querySelectorAll('.delete-item-detail-btn');
    deleteItemDetailBtns.forEach(function(button) {
        button.addEventListener('click', function() {
            var itemsDetailSelect = document.getElementById('itemsDetailSelect');
            var selectedItemDetailId = itemsDetailSelect.value;
            var selectedItemDetailName = itemsDetailSelect.options[itemsDetailSelect.selectedIndex].text;
            if (!selectedItemDetailId) {
                alert('Vui lòng chọn một Items Detail để xoá.');
                return;
            }
            if (confirm('Bạn có chắc chắn muốn xoá Items Detail "' + selectedItemDetailName + '"?')) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete_item_detail.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                itemsDetailSelect.remove(itemsDetailSelect.selectedIndex);
                                alert('Đã xoá Items Detail thành công.');
                                updateButtons();
                            } else {
                                alert('Lỗi: ' + response.message);
                            }
                        } catch (e) {
                            alert('Phản hồi từ server không hợp lệ.');
                        }
                    } else {
                        alert('Yêu cầu không thành công. Mã lỗi: ' + xhr.status);
                    }
                };
                xhr.send('id=' + encodeURIComponent(selectedItemDetailId));
            }
        });
    });

    var productSearchInput = document.getElementById('productSearchInput');
    if (productSearchInput) {
        productSearchInput.addEventListener('input', function() {
            currentPage = 1;
            fetchAndUpdateProducts();
        });
    }
    var prevPageBtn = document.getElementById('prevPageBtn');
    prevPageBtn.addEventListener('click', function() {
        if (currentPage > 1) {
            currentPage--;
            fetchAndUpdateProducts();
        }
    });
    var nextPageBtn = document.getElementById('nextPageBtn');
    nextPageBtn.addEventListener('click', function() {
        if (currentPage < totalPages) {
            currentPage++;
            fetchAndUpdateProducts();
        }
    });

    function updateButtons() {
        var categoriesSelect = document.getElementById('categoriesSelect');
        var subcategoriesSelect = document.getElementById('subcategoriesSelect');
        var itemsSelect = document.getElementById('itemsSelect');
        var itemsDetailSelect = document.getElementById('itemsDetailSelect');

        var addCategoryBtn = document.querySelector('.add-category-btn');
        var deleteCategoryBtn = document.querySelector('.delete-category-btn');
        addCategoryBtn.disabled = false;
        if (categoriesSelect.value) {
            deleteCategoryBtn.disabled = false;
            var addSubcategoryBtn = document.querySelector('.add-subcategory-btn');
            addSubcategoryBtn.disabled = false;
        } else {
            deleteCategoryBtn.disabled = true;
            var addSubcategoryBtn = document.querySelector('.add-subcategory-btn');
            addSubcategoryBtn.disabled = true;
        }

        var deleteSubcategoryBtn = document.querySelector('.delete-subcategory-btn');
        if (subcategoriesSelect.value) {
            deleteSubcategoryBtn.disabled = false;
            var addItemBtn = document.querySelector('.add-item-btn');
            addItemBtn.disabled = false;
        } else {
            deleteSubcategoryBtn.disabled = true;
            var addItemBtn = document.querySelector('.add-item-btn');
            addItemBtn.disabled = true;
        }

        var deleteItemBtn = document.querySelector('.delete-item-btn');
        if (itemsSelect.value) {
            deleteItemBtn.disabled = false;
            var addItemDetailBtn = document.querySelector('.add-item-detail-btn');
            addItemDetailBtn.disabled = false;
        } else {
            deleteItemBtn.disabled = true;
            var addItemDetailBtn = document.querySelector('.add-item-detail-btn');
            addItemDetailBtn.disabled = true;
        }

        var deleteItemDetailBtn = document.querySelector('.delete-item-detail-btn');
        if (itemsDetailSelect.value) {
            deleteItemDetailBtn.disabled = false;
        } else {
            deleteItemDetailBtn.disabled = true;
        }
    }

    document.getElementById('categoriesSelect').addEventListener('change', function() {
        var subcategoriesSelect = document.getElementById('subcategoriesSelect');
        var itemsSelect = document.getElementById('itemsSelect');
        var itemsDetailSelect = document.getElementById('itemsDetailSelect');
        subcategoriesSelect.innerHTML = '<option value="">-- Chọn Subcategory --</option>';
        subcategoriesSelect.disabled = true;
        itemsSelect.innerHTML = '<option value="">-- Chọn Item --</option>';
        itemsSelect.disabled = true;
        itemsDetailSelect.innerHTML = '<option value="">-- Chọn Items Detail --</option>';
        itemsDetailSelect.disabled = true;
        if (this.value) {
            fetchSubcategories(this.value);
        }
        updateButtons();
        currentPage = 1;
        fetchAndUpdateProducts();
    });

    document.getElementById('subcategoriesSelect').addEventListener('change', function() {
        var itemsSelect = document.getElementById('itemsSelect');
        var itemsDetailSelect = document.getElementById('itemsDetailSelect');
        itemsSelect.innerHTML = '<option value="">-- Chọn Item --</option>';
        itemsSelect.disabled = true;
        itemsDetailSelect.innerHTML = '<option value="">-- Chọn Items Detail --</option>';
        itemsDetailSelect.disabled = true;
        if (this.value) {
            fetchItems(this.value);
        }
        updateButtons();
        currentPage = 1;
        fetchAndUpdateProducts();
    });

    document.getElementById('itemsSelect').addEventListener('change', function() {
        var itemsDetailSelect = document.getElementById('itemsDetailSelect');
        itemsDetailSelect.innerHTML = '<option value="">-- Chọn Items Detail --</option>';
        itemsDetailSelect.disabled = true;
        if (this.value) {
            fetchItemsDetail(this.value);
            fetchItemsOption(this.value);
        }
        updateButtons();
        currentPage = 1;
        fetchAndUpdateProducts();
    });

    document.getElementById('itemsDetailSelect').addEventListener('change', function() {
        if (this.value) {
            fetchItemsOption(this.value);
        }
        updateButtons();
        currentPage = 1;
        fetchAndUpdateProducts();
    });

    updateButtons();

    var toggleProfileBtn = document.getElementById('toggleProfileSelection');
    var profileSelectionCont = document.getElementById('profileSelectionCont');

    var toggleCustomerBtn = document.getElementById('toggleCustomerInfo');
    var customerInfoCont = document.getElementById('customerInfoCont');

    var toggleProductBtn = document.getElementById('toggleProductInfo');
    var productInfoCont = document.getElementById('productInfoCont');

    var toggleSliderBtn = document.getElementById('toggleIndexSliderInfo');
    var sliderInfoCont = document.getElementById('indexSliderInfoCont');

    var toggleProductChangeBtn = document.getElementById('toggleProductChange');
    var productChangeCont = document.getElementById('productChangeCont');

    if (toggleProfileBtn && profileSelectionCont) {
        toggleProfileBtn.addEventListener('click', function () {
            hideAllSections('profile');
            if (profileSelectionCont.style.display === 'none' || profileSelectionCont.style.display === '') {
                profileSelectionCont.style.display = 'block';
                toggleProfileBtn.setAttribute('aria-expanded', 'true');
            } else {
                profileSelectionCont.style.display = 'none';
                toggleProfileBtn.setAttribute('aria-expanded', 'false');
            }
        });
    }

    if (toggleCustomerBtn && customerInfoCont) {
        toggleCustomerBtn.addEventListener('click', function() {
            toggleSection('customer');
        });
    }

    if (toggleProductBtn && productInfoCont) {
        toggleProductBtn.addEventListener('click', function() {
            toggleSection('product');
        });
    }

    if (toggleSliderBtn && sliderInfoCont) {
        toggleSliderBtn.addEventListener('click', function() {
            toggleSection('slider');
        });
    }

    if (toggleProductChangeBtn && productChangeCont) {
        toggleProductChangeBtn.addEventListener('click', function () {
            toggleSection('productChange');
        });
    }

    function hideAllSections(except = null) {
        if (except !== 'customer' && customerInfoCont) {
            customerInfoCont.style.display = 'none';
            toggleCustomerBtn.setAttribute('aria-expanded', 'false');
            localStorage.setItem('showCustomerInfo', 'false');
        }
        if (except !== 'product' && productInfoCont) {
            productInfoCont.style.display = 'none';
            toggleProductBtn.setAttribute('aria-expanded', 'false');
            localStorage.setItem('showProductInfo', 'false');
        }
        if (except !== 'slider' && sliderInfoCont) {
            sliderInfoCont.style.display = 'none';
            toggleSliderBtn.setAttribute('aria-expanded', 'false');
            localStorage.setItem('showSliderInfo', 'false');
        }
        if (except !== 'productChange' && productChangeCont) {
            productChangeCont.style.display = 'none';
            toggleProductChangeBtn.setAttribute('aria-expanded', 'false');
        }
        if (except !== 'profile' && profileSelectionCont) {
            profileSelectionCont.style.display = 'none';
            toggleProfileBtn.setAttribute('aria-expanded', 'false');
        }
    }

    function showSection(section) {
        if (section === 'customer' && customerInfoCont && toggleCustomerBtn) {
            customerInfoCont.style.display = 'block';
            toggleCustomerBtn.setAttribute('aria-expanded', 'true');
            localStorage.setItem('showCustomerInfo', 'true');
        } else if (section === 'product' && productInfoCont && toggleProductBtn) {
            productInfoCont.style.display = 'block';
            toggleProductBtn.setAttribute('aria-expanded', 'true');
            localStorage.setItem('showProductInfo', 'true');
        } else if (section === 'slider' && sliderInfoCont && toggleSliderBtn) {
            sliderInfoCont.style.display = 'block';
            toggleSliderBtn.setAttribute('aria-expanded', 'true');
            localStorage.setItem('showSliderInfo', 'true');
        } else if (section === 'productChange' && productChangeCont && toggleProductChangeBtn) {
            productChangeCont.style.display = 'block';
            toggleProductChangeBtn.setAttribute('aria-expanded', 'true');
        } else if (section === 'profile' && profileSelectionCont && toggleProfileBtn) {
            profileSelectionCont.style.display = 'block';
            toggleProfileBtn.setAttribute('aria-expanded', 'true');
        }
    }

    function toggleSection(section) {
        if (section === 'customer') {
            if (customerInfoCont.style.display === 'none' || customerInfoCont.style.display === '') {
                hideAllSections('customer');
                showSection('customer');
            } else {
                customerInfoCont.style.display = 'none';
                toggleCustomerBtn.setAttribute('aria-expanded', 'false');
                localStorage.setItem('showCustomerInfo', 'false');
            }
        } else if (section === 'product') {
            if (productInfoCont.style.display === 'none' || productInfoCont.style.display === '') {
                hideAllSections('product');
                showSection('product');
            } else {
                productInfoCont.style.display = 'none';
                toggleProductBtn.setAttribute('aria-expanded', 'false');
                localStorage.setItem('showProductInfo', 'false');
            }
        } else if (section === 'slider') {
            if (sliderInfoCont.style.display === 'none' || sliderInfoCont.style.display === '') {
                hideAllSections('slider');
                showSection('slider');
            } else {
                sliderInfoCont.style.display = 'none';
                toggleSliderBtn.setAttribute('aria-expanded', 'false');
                localStorage.setItem('showSliderInfo', 'false');
            }
        } else if (section === 'productChange') {
            if (productChangeCont.style.display === 'none' || productChangeCont.style.display === '') {
                hideAllSections('productChange');
                showSection('productChange');
            } else {
                productChangeCont.style.display = 'none';
                toggleProductChangeBtn.setAttribute('aria-expanded', 'false');
            }
        } else if (section === 'profile') {
            if (profileSelectionCont.style.display === 'none' || profileSelectionCont.style.display === '') {
                hideAllSections('profile');
                showSection('profile');
            } else {
                profileSelectionCont.style.display = 'none';
                toggleProfileBtn.setAttribute('aria-expanded', 'false');
            }
        }
    }

    var showCustomerInfo = localStorage.getItem('showCustomerInfo') === 'true';
    var showProductInfo = localStorage.getItem('showProductInfo') === 'true';
    var showSliderInfo = localStorage.getItem('showSliderInfo') === 'true';

    if (showCustomerInfo && !showProductInfo && !showSliderInfo) {
        customerInfoCont.style.display = 'block';
        toggleCustomerBtn.setAttribute('aria-expanded', 'true');
    } else if (showProductInfo && !showCustomerInfo && !showSliderInfo) {
        productInfoCont.style.display = 'block';
        toggleProductBtn.setAttribute('aria-expanded', 'true');
    } else if (showSliderInfo && !showCustomerInfo && !showProductInfo) {
        sliderInfoCont.style.display = 'block';
        toggleSliderBtn.setAttribute('aria-expanded', 'true');
    } else if (showCustomerInfo && showProductInfo && !showSliderInfo) {
        hideAllSections('customer');
        showSection('customer');
    } else if (showCustomerInfo && showSliderInfo && !showProductInfo) {
        hideAllSections('slider');
        showSection('slider');
    } else if (showProductInfo && showSliderInfo && !showCustomerInfo) {
        hideAllSections('product');
        showSection('slider');
    } else if (showCustomerInfo && showProductInfo && showSliderInfo) {
        hideAllSections('customer');
        showSection('customer');
    }

    function fetchSubcategories(categoryId) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'get_subcategories.php?category_id=' + encodeURIComponent(categoryId), true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        var subcategoriesSelect = document.getElementById('subcategoriesSelect');
                        var options = '<option value="">-- Chọn Subcategory --</option>';
                        response.data.forEach(function (subcat) {
                            options += '<option value="' + subcat.id + '">' + subcat.name + '</option>';
                        });
                        subcategoriesSelect.innerHTML = options;
                        subcategoriesSelect.disabled = false;
                        updateButtons();
                    } else {
                        alert('Lỗi: ' + response.message);
                    }
                } catch (e) {
                    alert('Phản hồi từ server không hợp lệ.');
                }
            } else {
                alert('Yêu cầu không thành công. Mã lỗi: ' + xhr.status);
            }
        };
        xhr.send();
    }

    function fetchItems(subcategoryId) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'get_items.php?subcategory_id=' + encodeURIComponent(subcategoryId), true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        var itemsSelect = document.getElementById('itemsSelect');
                        var options = '<option value="">-- Chọn Item --</option>';
                        response.data.forEach(function (item) {
                            options += '<option value="' + item.id + '">' + item.name + '</option>';
                        });
                        itemsSelect.innerHTML = options;
                        itemsSelect.disabled = false;
                        updateButtons();
                    } else {
                        alert('Lỗi: ' + response.message);
                    }
                } catch (e) {
                    alert('Phản hồi từ server không hợp lệ.');
                }
            } else {
                alert('Yêu cầu không thành công. Mã lỗi: ' + xhr.status);
            }
        };
        xhr.send();
    }

    function fetchItemsDetail(itemId) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'get_items_detail.php?item_id=' + encodeURIComponent(itemId), true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        var itemsDetailSelect = document.getElementById('itemsDetailSelect');
                        var options = '<option value="">-- Chọn Items Detail --</option>';
                        response.data.forEach(function (itemDetail) {
                            options += '<option value="' + itemDetail.id + '">' + itemDetail.name + '</option>';
                        });
                        itemsDetailSelect.innerHTML = options;
                        itemsDetailSelect.disabled = false;
                        updateButtons();
                    } else {
                        alert('Lỗi: ' + response.message);
                    }
                } catch (e) {
                    alert('Phản hồi từ server không hợp lệ.');
                }
            } else {
                alert('Yêu cầu không thành công. Mã lỗi: ' + xhr.status);
            }
        };
        xhr.send();
    }

    function fetchAndUpdateProducts() {
        var category_id = document.getElementById('categoriesSelect').value;
        var subcategory_id = document.getElementById('subcategoriesSelect').value;
        var item_id = document.getElementById('itemsSelect').value;
        var items_detail_id = document.getElementById('itemsDetailSelect').value;
        var searchQuery = document.getElementById('productSearchInput').value.trim();

        var params = {};
        if (category_id) params['category_id'] = category_id;
        if (subcategory_id) params['subcategory_id'] = subcategory_id;
        if (item_id) params['item_id'] = item_id;
        if (items_detail_id) params['items_detail_id'] = items_detail_id;
        if (searchQuery) params['search'] = searchQuery;
        params['page'] = currentPage;

        var queryString = Object.keys(params).map(key => key + '=' + encodeURIComponent(params[key])).join('&');
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'get_product.php?' + queryString, true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        updateProductsTable(response);
                    } else {
                        alert('Lỗi: ' + response.message);
                    }
                } catch (e) {
                    alert('Phản hồi từ server không hợp lệ.');
                }
            } else {
                alert('Yêu cầu không thành công. Mã lỗi: ' + xhr.status);
            }
        };
        xhr.send();
    }

    function updateProductsTable(responseData) {
        var products = responseData.data;
        totalPages = responseData.total_pages;
        document.getElementById('currentPage').textContent = 'Trang ' + currentPage + ' / ' + totalPages;
        prevPageBtn.disabled = currentPage <= 1;
        nextPageBtn.disabled = currentPage >= totalPages;
        var productsTableBody = document.querySelector('#productsTable tbody');
        productsTableBody.innerHTML = '';
        if (products.length > 0) {
            products.forEach(function(product, index) {
                var row = document.createElement('tr');
                row.setAttribute('data-id', product.id);
                var cellIndex = document.createElement('td');
                cellIndex.textContent = index + 1 + (currentPage - 1) * 50;
                row.appendChild(cellIndex);
                var cellID = document.createElement('td');
                cellID.textContent = product.id;
                row.appendChild(cellID);
                var cellName = document.createElement('td');
                cellName.textContent = product.name;
                cellName.classList.add('editable');
                cellName.setAttribute('data-field', 'name');
                row.appendChild(cellName);
                var cellDescription = document.createElement('td');
                cellDescription.textContent = product.description;
                cellDescription.classList.add('editable');
                cellDescription.setAttribute('data-field', 'description');
                row.appendChild(cellDescription);
                var cellImg = document.createElement('td');
                var imgElement = document.createElement('img');
                imgElement.src = product.img ? 'https://tiemhoamimi.com/image/upload/' + product.img : 'path/to/default/image.jpg';
                imgElement.style.width = '50px';
                cellImg.appendChild(imgElement);
                var uploadBtn = document.createElement('button');
                uploadBtn.textContent = 'Tải lên ảnh';
                uploadBtn.classList.add('upload-image-btn');
                uploadBtn.setAttribute('data-id', product.id);
                var fileInput = document.createElement('input');
                fileInput.type = 'file';
                fileInput.style.display = 'none';
                fileInput.classList.add('upload-image-input');
                fileInput.setAttribute('data-id', product.id);
                cellImg.appendChild(uploadBtn);
                cellImg.appendChild(fileInput);
                row.appendChild(cellImg);
                var cellPrice = document.createElement('td');
                cellPrice.textContent = numberWithCommas(product.price) + 'đ';
                cellPrice.classList.add('editable');
                cellPrice.setAttribute('data-field', 'price');
                row.appendChild(cellPrice);
                var cellActions = document.createElement('td');
                var saveBtn = document.createElement('button');
                saveBtn.textContent = 'Lưu';
                saveBtn.classList.add('save-btn');
                saveBtn.style.display = 'none';
                var cancelBtn = document.createElement('button');
                cancelBtn.textContent = 'Hủy';
                cancelBtn.classList.add('cancel-btn');
                cancelBtn.style.display = 'none';
                cellActions.appendChild(saveBtn);
                cellActions.appendChild(cancelBtn);
                row.appendChild(cellActions);
                productsTableBody.appendChild(row);
            });
            initializeEditableCells();
            initializeUploadButtons();
            document.querySelectorAll('#productsTable tbody tr td img').forEach(function(img) {
                img.addEventListener('mouseover', function(event) {
                    var preview = document.getElementById('imagePreview');
                    preview.querySelector('img').src = this.src;
                    preview.style.display = 'block';
                    var rect = this.getBoundingClientRect();
                    preview.style.top = (rect.top + window.scrollY) + 'px';
                    preview.style.left = (rect.right + window.scrollX + 10) + 'px';
                });
                img.addEventListener('mouseout', function(event) {
                    var preview = document.getElementById('imagePreview');
                    preview.style.display = 'none';
                });
            });
        } else {
            var row = document.createElement('tr');
            var cell = document.createElement('td');
            cell.setAttribute('colspan', '7');
            cell.textContent = 'Không có dữ liệu';
            row.appendChild(cell);
            productsTableBody.appendChild(row);
        }
    }

    function initializeEditableCells() {
        var editableCells = document.querySelectorAll('.editable');
        editableCells.forEach(function(cell) {
            cell.addEventListener('click', function() {
                if (cell.querySelector('input')) return;
                var currentText = cell.textContent.trim();
                var field = cell.getAttribute('data-field');
                var id = cell.parentElement.getAttribute('data-id');
                var input = document.createElement('input');
                input.type = field === 'price' ? 'number' : 'text';
                input.value = field === 'price' ? parseFloat(currentText.replace(/[^0-9.-]+/g,"")) : currentText;
                input.className = 'edit-input';
                input.style.width = '100%';
                cell.textContent = '';
                cell.appendChild(input);
                input.focus();
                var saveBtn = cell.parentElement.querySelector('.save-btn');
                var cancelBtn = cell.parentElement.querySelector('.cancel-btn');
                saveBtn.style.display = 'inline-block';
                cancelBtn.style.display = 'inline-block';
                cancelBtn.addEventListener('click', function() {
                    cell.textContent = currentText;
                    saveBtn.style.display = 'none';
                    cancelBtn.style.display = 'none';
                }, { once: true });
                saveBtn.addEventListener('click', function() {
                    var newValue = input.value.trim();
                    if (newValue === '') {
                        alert('Giá trị không được để trống.');
                        return;
                    }
                    if (field === 'price' && isNaN(newValue)) {
                        alert('Giá trị phải là số.');
                        return;
                    }
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'update_product.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4) {
                            if (xhr.status === 200) {
                                try {
                                    var response = JSON.parse(xhr.responseText);
                                    if (response.success) {
                                        if (field === 'price') {
                                            cell.textContent = numberWithCommas(parseFloat(newValue)) + 'đ';
                                        } else {
                                            cell.textContent = newValue;
                                        }
                                        saveBtn.style.display = 'none';
                                        cancelBtn.style.display = 'none';
                                    } else {
                                        alert('Lỗi: ' + response.message);
                                    }
                                } catch (e) {
                                    alert('Phản hồi từ server không hợp lệ.');
                                }
                            } else {
                                alert('Yêu cầu không thành công. Mã lỗi: ' + xhr.status);
                            }
                        }
                    };
                    xhr.send('id=' + encodeURIComponent(id) + '&field=' + encodeURIComponent(field) + '&value=' + encodeURIComponent(newValue));
                }, { once: true });
            });
        });
    }

    function numberWithCommas(x) {
        if (isNaN(x)) return x;
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function initializeUploadButtons() {
        var uploadButtons = document.querySelectorAll('.upload-image-btn');
        uploadButtons.forEach(function(button) {
            button.onclick = null;
            button.addEventListener('click', function() {
                var productId = this.getAttribute('data-id');
                var fileInput = document.querySelector('.upload-image-input[data-id="' + productId + '"]');
                if (fileInput) {
                    fileInput.click();
                }
            });
        });
        var fileInputs = document.querySelectorAll('.upload-image-input');
        fileInputs.forEach(function(input) {
            input.onchange = null;
            input.addEventListener('change', function() {
                var productId = this.getAttribute('data-id');
                var file = this.files[0];
                if (file) {
                    var formData = new FormData();
                    formData.append('product_id', productId);
                    formData.append('image', file);
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'upload_image.php', true);
                    xhr.onload = function () {
                        if (xhr.status === 200) {
                            try {
                                var response = JSON.parse(xhr.responseText);
                                if (response.success) {
                                    var imgElement = document.querySelector('tr[data-id="' + productId + '"] img');
                                    if (imgElement) {
                                        imgElement.src = response.image_path + '?' + new Date().getTime();
                                    }
                                } else {
                                    alert('Lỗi: ' + response.message);
                                }
                            } catch (e) {
                                alert('Phản hồi từ server không hợp lệ.');
                            }
                        } else {
                            alert('Yêu cầu không thành công. Mã lỗi: ' + xhr.status);
                        }
                    };
                    xhr.send(formData);
                }
            });
        });
    }

    function fetchItemsOption(detailId) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'get_items_option.php?detail_id=' + encodeURIComponent(detailId), true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        displayItemsOption(response.data);
                    } else {
                        alert('Lỗi: ' + response.message);
                    }
                } catch (e) {
                    alert('Phản hồi từ server không hợp lệ.');
                }
            } else {
                alert('Yêu cầu không thành công. Mã lỗi: ' + xhr.status);
            }
        };
        xhr.send();
    }

    function displayItemsOption(options) {
        var itemsOptionTableBody = document.querySelector('#itemsOptionTable tbody');
        itemsOptionTableBody.innerHTML = '';
        var groups = ['Màu sắc', 'Số lượng', 'Tùy chọn', 'Phụ kiện'];
        groups.forEach(function(group) {
            var groupHeaderRow = document.createElement('tr');
            groupHeaderRow.classList.add('items-option-group-header');
            var groupCell = document.createElement('td');
            groupCell.setAttribute('colspan', '5');
            groupCell.textContent = group;
            groupHeaderRow.appendChild(groupCell);
            itemsOptionTableBody.appendChild(groupHeaderRow);
            var groupOptions = options.filter(function(option) {
                return option.group_name.trim() === group;
            });
            if (groupOptions.length > 0) {
                groupOptions.forEach(function(option) {
                    var row = document.createElement('tr');
                    row.classList.add('items-option-item');
                    row.setAttribute('data-id', option.id);
                    var groupNameCell = document.createElement('td');
                    groupNameCell.textContent = group;
                    row.appendChild(groupNameCell);
                    var optionNameCell = document.createElement('td');
                    optionNameCell.textContent = option.option_name;
                    optionNameCell.classList.add('editable');
                    optionNameCell.setAttribute('data-field', 'option_name');
                    row.appendChild(optionNameCell);
                    var addPriceCell = document.createElement('td');
                    addPriceCell.textContent = formatPrice(option.add_price);
                    addPriceCell.classList.add('editable');
                    addPriceCell.setAttribute('data-field', 'add_price');
                    row.appendChild(addPriceCell);
                    var imgCell = document.createElement('td');
                    var imgElement = document.createElement('img');
                    imgElement.src = option.img ? 'image/option-img/' + option.img : 'path/to/default/image.jpg';
                    imgElement.style.width = '50px';
                    imgElement.style.height = '50px';
                    imgCell.appendChild(imgElement);
                    var uploadBtn = document.createElement('button');
                    uploadBtn.textContent = 'Tải lên ảnh';
                    uploadBtn.classList.add('upload-option-image-btn');
                    uploadBtn.setAttribute('data-id', option.id);
                    imgCell.appendChild(uploadBtn);
                    var fileInput = document.createElement('input');
                    fileInput.type = 'file';
                    fileInput.style.display = 'none';
                    fileInput.classList.add('upload-option-image-input');
                    fileInput.setAttribute('data-id', option.id);
                    imgCell.appendChild(fileInput);
                    row.appendChild(imgCell);
                    var actionCell = document.createElement('td');
                    var saveBtn = document.createElement('button');
                    saveBtn.textContent = 'Lưu';
                    saveBtn.classList.add('save-btn');
                    saveBtn.style.display = 'none';
                    var cancelBtn = document.createElement('button');
                    cancelBtn.textContent = 'Hủy';
                    cancelBtn.classList.add('cancel-btn');
                    cancelBtn.style.display = 'none';
                    actionCell.appendChild(saveBtn);
                    actionCell.appendChild(cancelBtn);
                    row.appendChild(actionCell);
                    itemsOptionTableBody.appendChild(row);
                });
            } else {
                var noOptionRow = document.createElement('tr');
                var noOptionCell = document.createElement('td');
                noOptionCell.setAttribute('colspan', '5');
                noOptionCell.textContent = 'Không có tùy chọn cho nhóm này.';
                noOptionRow.appendChild(noOptionCell);
                itemsOptionTableBody.appendChild(noOptionRow);
            }
        });
        initializeItemsOptionEditableCells();
        initializeOptionUploadButtons();
    }

    function initializeItemsOptionEditableCells() {
        var editableCells = document.querySelectorAll('#itemsOptionTable .editable');
        editableCells.forEach(function(cell) {
            cell.addEventListener('click', function() {
                if (cell.querySelector('input')) return;
                var currentText = cell.textContent.trim();
                var field = cell.getAttribute('data-field');
                var id = cell.parentElement.getAttribute('data-id');
                var input = document.createElement('input');
                input.type = (field === 'add_price') ? 'number' : 'text';
                input.value = (field === 'add_price') ? parseFloat(currentText.replace(/[^0-9.-]+/g,"")) : currentText;
                input.className = 'edit-input';
                input.style.width = '100%';
                cell.textContent = '';
                cell.appendChild(input);
                input.focus();
                var saveBtn = cell.parentElement.querySelector('.save-btn');
                var cancelBtn = cell.parentElement.querySelector('.cancel-btn');
                saveBtn.style.display = 'inline-block';
                cancelBtn.style.display = 'inline-block';
                cancelBtn.addEventListener('click', function() {
                    cell.textContent = currentText;
                    saveBtn.style.display = 'none';
                    cancelBtn.style.display = 'none';
                }, { once: true });
                saveBtn.addEventListener('click', function() {
                    var newValue = input.value.trim();
                    if (newValue === '') {
                        alert('Giá trị không được để trống.');
                        return;
                    }
                    if (field === 'add_price' && isNaN(newValue)) {
                        alert('Giá trị phải là số.');
                        return;
                    }
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'update_items_option.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4) {
                            if (xhr.status === 200) {
                                try {
                                    var response = JSON.parse(xhr.responseText);
                                    if (response.success) {
                                        if (field === 'add_price') {
                                            cell.textContent = formatPrice(parseFloat(newValue));
                                        } else {
                                            cell.textContent = newValue;
                                        }
                                        saveBtn.style.display = 'none';
                                        cancelBtn.style.display = 'none';
                                    } else {
                                        alert('Lỗi: ' + response.message);
                                    }
                                } catch (e) {
                                    alert('Phản hồi từ server không hợp lệ.');
                                }
                            } else {
                                alert('Yêu cầu không thành công. Mã lỗi: ' + xhr.status);
                            }
                        }
                    };
                    xhr.send('id=' + encodeURIComponent(id) + '&field=' + encodeURIComponent(field) + '&value=' + encodeURIComponent(newValue));
                }, { once: true });
            });
        });
    }

    function formatPrice(price) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price);
    }

    function initializeOptionUploadButtons() {
        var uploadBtns = document.querySelectorAll('.upload-option-image-btn');
        uploadBtns.forEach(function(button) {
            button.onclick = null;
            button.addEventListener('click', function() {
                var optionId = this.getAttribute('data-id');
                var fileInput = document.querySelector('.upload-option-image-input[data-id="' + optionId + '"]');
                if (fileInput) {
                    fileInput.click();
                }
            });
        });
        var fileInputs = document.querySelectorAll('.upload-option-image-input');
        fileInputs.forEach(function(input) {
            input.onchange = null;
            input.addEventListener('change', function() {
                var optionId = this.getAttribute('data-id');
                var file = this.files[0];
                if (file) {
                    var formData = new FormData();
                    formData.append('option_id', optionId);
                    formData.append('image', file);
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'upload_option_image.php', true);
                    xhr.onload = function () {
                        if (xhr.status === 200) {
                            try {
                                var response = JSON.parse(xhr.responseText);
                                if (response.success) {
                                    var imgElement = document.querySelector('.upload-option-image-btn[data-id="' + optionId + '"]').previousElementSibling;
                                    if (imgElement) {
                                        imgElement.src = response.image_path + '?' + new Date().getTime();
                                    }
                                    alert('Tải lên ảnh thành công!');
                                } else {
                                    alert('Lỗi: ' + response.message);
                                }
                            } catch (e) {
                                alert('Phản hồi từ server không hợp lệ.');
                            }
                        } else {
                            alert('Yêu cầu không thành công. Mã lỗi: ' + xhr.status);
                        }
                    };
                    xhr.send(formData);
                }
            });
        });
    }

    fetchAndUpdateProducts();
});
