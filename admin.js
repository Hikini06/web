// admin.js

document.addEventListener('DOMContentLoaded', function() {
    var currentPage = 1;
    var totalPages = 1;

    // ===== Sidebar Active State =====
    var sidebarButtons = document.querySelectorAll('.sidebar-button');

    sidebarButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            // Loại bỏ lớp 'active' từ tất cả các nút
            sidebarButtons.forEach(function(btn) {
                btn.classList.remove('active');
            });

            // Thêm lớp 'active' cho nút được nhấn
            this.classList.add('active');

            // Lưu ID của nút vào localStorage để giữ trạng thái khi tải lại trang
            var activeButtonId = this.id;
            localStorage.setItem('activeSidebarButton', activeButtonId);
        });
    });

    // Khôi phục trạng thái active từ localStorage khi tải lại trang
    var activeSidebarButtonId = localStorage.getItem('activeSidebarButton');
    if (activeSidebarButtonId) {
        var activeButton = document.getElementById(activeSidebarButtonId);
        if (activeButton) {
            // Loại bỏ lớp 'active' từ tất cả các nút trước khi thêm lại lớp 'active'
            sidebarButtons.forEach(function(btn) {
                btn.classList.remove('active');
            });
            // Thêm lớp 'active' cho nút được lưu
            activeButton.classList.add('active');
        }
    } else {
        // Nếu không có nút nào được active, có thể thiết lập mặc định (ví dụ: nút đầu tiên)
        if (sidebarButtons.length > 0) {
            sidebarButtons[0].classList.add('active');
            localStorage.setItem('activeSidebarButton', sidebarButtons[0].id);
        }
    }

    // ===== Thêm chức năng thêm và xoá hạng mục =====

    // ===== Add Category =====
    var addCategoryBtns = document.querySelectorAll('.add-category-btn');

    addCategoryBtns.forEach(function(button) {
        button.addEventListener('click', function() {
            var categoryName = prompt('Nhập tên Category mới:');
            if (categoryName) {
                // Gửi yêu cầu AJAX để thêm category
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'add_category.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                // Thêm category mới vào select
                                var newOption = document.createElement('option');
                                newOption.value = response.data.id;
                                newOption.textContent = response.data.name;
                                document.getElementById('categoriesSelect').appendChild(newOption);
                                alert('Thêm Category thành công.');
                                updateButtons(); // Cập nhật trạng thái các nút
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

    // ===== Delete Category =====
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
                // Gửi yêu cầu AJAX để xoá category
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete_category.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                // Xoá category khỏi select
                                categoriesSelect.remove(categoriesSelect.selectedIndex);
                                // Reset các selects phụ
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
                                updateButtons(); // Cập nhật trạng thái các nút
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

    // ===== Add Subcategory =====
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
                // Gửi yêu cầu AJAX để thêm subcategory
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'add_subcategory.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                // Thêm subcategory mới vào select
                                var newOption = document.createElement('option');
                                newOption.value = response.data.id;
                                newOption.textContent = response.data.name;
                                document.getElementById('subcategoriesSelect').appendChild(newOption);
                                document.getElementById('subcategoriesSelect').disabled = false;
                                alert('Thêm Subcategory thành công.');
                                updateButtons(); // Cập nhật trạng thái các nút
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

    // ===== Delete Subcategory =====
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
                // Gửi yêu cầu AJAX để xoá subcategory
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete_subcategory.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                // Xoá subcategory khỏi select
                                subcategoriesSelect.remove(subcategoriesSelect.selectedIndex);
                                // Reset các selects phụ
                                var itemsSelect = document.getElementById('itemsSelect');
                                var itemsDetailSelect = document.getElementById('itemsDetailSelect');

                                itemsSelect.innerHTML = '<option value="">-- Chọn Item --</option>';
                                itemsSelect.disabled = true;
                                itemsDetailSelect.innerHTML = '<option value="">-- Chọn Items Detail --</option>';
                                itemsDetailSelect.disabled = true;

                                alert('Đã xoá Subcategory thành công.');
                                updateButtons(); // Cập nhật trạng thái các nút
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

    // ===== Add Item =====
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
                // Gửi yêu cầu AJAX để thêm item
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'add_item.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                // Thêm item mới vào select
                                var newOption = document.createElement('option');
                                newOption.value = response.data.id;
                                newOption.textContent = response.data.name;
                                document.getElementById('itemsSelect').appendChild(newOption);
                                document.getElementById('itemsSelect').disabled = false;
                                alert('Thêm Item thành công.');
                                updateButtons(); // Cập nhật trạng thái các nút
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

    // ===== Delete Item =====
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
                // Gửi yêu cầu AJAX để xoá item
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete_item.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                // Xoá item khỏi select
                                itemsSelect.remove(itemsSelect.selectedIndex);
                                // Reset các selects phụ
                                var itemsDetailSelect = document.getElementById('itemsDetailSelect');

                                itemsDetailSelect.innerHTML = '<option value="">-- Chọn Items Detail --</option>';
                                itemsDetailSelect.disabled = true;

                                alert('Đã xoá Item thành công.');
                                updateButtons(); // Cập nhật trạng thái các nút
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

    // ===== Add Items Detail =====
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
                // Gửi yêu cầu AJAX để thêm items_detail
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'add_item_detail.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                // Thêm items_detail mới vào select
                                var newOption = document.createElement('option');
                                newOption.value = response.data.id;
                                newOption.textContent = response.data.name;
                                document.getElementById('itemsDetailSelect').appendChild(newOption);
                                document.getElementById('itemsDetailSelect').disabled = false;
                                alert('Thêm Items Detail thành công.');
                                updateButtons(); // Cập nhật trạng thái các nút
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

    // ===== Delete Items Detail =====
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
                // Gửi yêu cầu AJAX để xoá items_detail
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete_item_detail.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                // Xoá items_detail khỏi select
                                itemsDetailSelect.remove(itemsDetailSelect.selectedIndex);
                                alert('Đã xoá Items Detail thành công.');
                                updateButtons(); // Cập nhật trạng thái các nút
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

    // Event listener cho ô tìm kiếm sản phẩm
    var productSearchInput = document.getElementById('productSearchInput');
    if (productSearchInput) {
        productSearchInput.addEventListener('input', function() {
            // Đặt lại currentPage về 1 khi thực hiện tìm kiếm
            currentPage = 1;
            fetchAndUpdateProducts();
        });
    }
    // Event listener cho nút Trang trước
    var prevPageBtn = document.getElementById('prevPageBtn');
    prevPageBtn.addEventListener('click', function() {
        if (currentPage > 1) {
            currentPage--;
            fetchAndUpdateProducts();
        }
    });

    // Event listener cho nút Trang sau
    var nextPageBtn = document.getElementById('nextPageBtn');
    nextPageBtn.addEventListener('click', function() {
        if (currentPage < totalPages) {
            currentPage++;
            fetchAndUpdateProducts();
        }
    });



    // ===== Cập Nhật và Vô Hiệu Hóa Các Nút =====
    function updateButtons() {
        var categoriesSelect = document.getElementById('categoriesSelect');
        var subcategoriesSelect = document.getElementById('subcategoriesSelect');
        var itemsSelect = document.getElementById('itemsSelect');
        var itemsDetailSelect = document.getElementById('itemsDetailSelect');

        // Categories buttons
        var addCategoryBtn = document.querySelector('.add-category-btn');
        var deleteCategoryBtn = document.querySelector('.delete-category-btn');
        addCategoryBtn.disabled = false; // Always enabled
        if (categoriesSelect.value) {
            deleteCategoryBtn.disabled = false;
            // Enable Add Subcategory button
            var addSubcategoryBtn = document.querySelector('.add-subcategory-btn');
            addSubcategoryBtn.disabled = false;
        } else {
            deleteCategoryBtn.disabled = true;
            var addSubcategoryBtn = document.querySelector('.add-subcategory-btn');
            addSubcategoryBtn.disabled = true;
        }

        // Subcategories buttons
        var deleteSubcategoryBtn = document.querySelector('.delete-subcategory-btn');
        if (subcategoriesSelect.value) {
            deleteSubcategoryBtn.disabled = false;
            // Enable Add Item button
            var addItemBtn = document.querySelector('.add-item-btn');
            addItemBtn.disabled = false;
        } else {
            deleteSubcategoryBtn.disabled = true;
            var addItemBtn = document.querySelector('.add-item-btn');
            addItemBtn.disabled = true;
        }

        // Items buttons
        var deleteItemBtn = document.querySelector('.delete-item-btn');
        if (itemsSelect.value) {
            deleteItemBtn.disabled = false;
            // Enable Add Items Detail button
            var addItemDetailBtn = document.querySelector('.add-item-detail-btn');
            addItemDetailBtn.disabled = false;
        } else {
            deleteItemBtn.disabled = true;
            var addItemDetailBtn = document.querySelector('.add-item-detail-btn');
            addItemDetailBtn.disabled = true;
        }

        // Items Detail buttons
        var deleteItemDetailBtn = document.querySelector('.delete-item-detail-btn');
        if (itemsDetailSelect.value) {
            deleteItemDetailBtn.disabled = false;
        } else {
            deleteItemDetailBtn.disabled = true;
        }
    }

    // Gọi hàm updateButtons khi các select thay đổi
    document.getElementById('categoriesSelect').addEventListener('change', function() {
        // Khi thay đổi category, reset subcategories, items, và items_detail
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
        // Khi thay đổi subcategory, reset items và items_detail
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
        // Khi thay đổi item, reset items_detail
        var itemsDetailSelect = document.getElementById('itemsDetailSelect');

        itemsDetailSelect.innerHTML = '<option value="">-- Chọn Items Detail --</option>';
        itemsDetailSelect.disabled = true;

        if (this.value) {
            fetchItemsDetail(this.value);
        }

        updateButtons();
        currentPage = 1;
        fetchAndUpdateProducts();
    });

    document.getElementById('itemsDetailSelect').addEventListener('change', function() {
        // Khi thay đổi items_detail, chỉ cần cập nhật nút delete
        updateButtons();
        currentPage = 1;
        fetchAndUpdateProducts();

    });

    // Khởi tạo trạng thái ban đầu
    updateButtons();

    // ===== Xử Lý Toggle Sections =====

    // Xử lý nút "Chọn sản phẩm đầu trang"
    var toggleProfileBtn = document.getElementById('toggleProfileSelection');
    var profileSelectionCont = document.getElementById('profileSelectionCont');

    // Xử lý nút "Thông tin khách hàng"
    var toggleCustomerBtn = document.getElementById('toggleCustomerInfo');
    var customerInfoCont = document.getElementById('customerInfoCont');

    // Xử lý nút "Sản phẩm"
    var toggleProductBtn = document.getElementById('toggleProductInfo');
    var productInfoCont = document.getElementById('productInfoCont');

    // Xử lý nút "Chọn sản phẩm cho slider"
    var toggleSliderBtn = document.getElementById('toggleIndexSliderInfo');
    var sliderInfoCont = document.getElementById('indexSliderInfoCont');

    // Xử lý nút "Chỉnh sửa sản phẩm"
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

    // ===== Hàm Hide All Sections =====
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

    // ===== Hàm Show Section =====
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

    // ===== Hàm Toggle Section =====
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

    // ===== Khởi Tạo Trạng Thái Ban Đầu =====
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
        // Nếu cả hai đều được lưu là hiển thị, chỉ hiển thị bảng khách hàng
        hideAllSections('customer');
        showSection('customer');
    } else if (showCustomerInfo && showSliderInfo && !showProductInfo) {
        hideAllSections('slider');
        showSection('slider');
    } else if (showProductInfo && showSliderInfo && !showCustomerInfo) {
        hideAllSections('product');
        showSection('slider');
    } else if (showCustomerInfo && showProductInfo && showSliderInfo) {
        // Nếu cả ba đều được lưu là hiển thị, chỉ hiển thị bảng khách hàng
        hideAllSections('customer');
        showSection('customer');
    }

    // ===== Xử Lý Upload Ảnh =====

    // Xử lý nút "Tải lên ảnh"
    var uploadButtons = document.querySelectorAll('.upload-image-btn');
    uploadButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var productId = this.getAttribute('data-id');
            var fileInput = document.querySelector('.upload-image-input[data-id="' + productId + '"]');
            if (fileInput) {
                fileInput.click();
            }
        });
    });

    // Xử lý khi người dùng chọn tệp ảnh
    var fileInputs = document.querySelectorAll('.upload-image-input');

    fileInputs.forEach(function(input) {
        input.addEventListener('change', function() {
            var productId = this.getAttribute('data-id');
            var file = this.files[0];

            if (file) {
                // Tạo đối tượng FormData
                var formData = new FormData();
                formData.append('product_id', productId);
                formData.append('image', file);

                // Gửi yêu cầu AJAX để tải lên ảnh
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'upload_image.php', true);

                xhr.onload = function () {
                    if (xhr.status === 200) {
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                // Cập nhật ảnh trong bảng
                                var imgElement = document.querySelector('tr[data-id="' + productId + '"] img');
                                if (imgElement) {
                                    imgElement.src = response.image_path + '?' + new Date().getTime(); // Thêm timestamp để tránh cache
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

    // ===== Xử Lý Chỉnh Sửa Các Ô Có Lớp 'editable' =====
    var editableCells = document.querySelectorAll('.editable');

    editableCells.forEach(function(cell) {
        cell.addEventListener('click', function() {
            if (cell.querySelector('input')) return; // Nếu đã có input, không làm gì

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

            // Hiển thị nút "Lưu" và "Hủy"
            var saveBtn = cell.parentElement.querySelector('.save-btn');
            var cancelBtn = cell.parentElement.querySelector('.cancel-btn');
            saveBtn.style.display = 'inline-block';
            cancelBtn.style.display = 'inline-block';

            // Xử lý nút "Hủy"
            cancelBtn.addEventListener('click', function() {
                cell.textContent = currentText;
                saveBtn.style.display = 'none';
                cancelBtn.style.display = 'none';
            }, { once: true });

            // Xử lý nút "Lưu"
            saveBtn.addEventListener('click', function() {
                var newValue = input.value.trim();
                if (newValue === '') {
                    alert('Giá trị không được để trống.');
                    return;
                }

                // Nếu trường là 'price', đảm bảo giá trị là số
                if (field === 'price' && isNaN(newValue)) {
                    alert('Giá trị phải là số.');
                    return;
                }

                // Gửi yêu cầu AJAX để cập nhật dữ liệu
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'update_product.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4) {
                        console.log('Response Status:', xhr.status);
                        console.log('Response Text:', xhr.responseText);
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

    // Hàm định dạng số với dấu phẩy
    function numberWithCommas(x) {
        if (isNaN(x)) return x;
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    
    // ===== Hàm Hide All Sections =====
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

    // ===== Hàm Show Section =====
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

    // ===== Hàm Toggle Section =====
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

    // ===== Khởi Tạo Trạng Thái Ban Đầu =====
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
        // Nếu cả hai đều được lưu là hiển thị, chỉ hiển thị bảng khách hàng
        hideAllSections('customer');
        showSection('customer');
    } else if (showCustomerInfo && showSliderInfo && !showProductInfo) {
        hideAllSections('slider');
        showSection('slider');
    } else if (showProductInfo && showSliderInfo && !showCustomerInfo) {
        hideAllSections('product');
        showSection('slider');
    } else if (showCustomerInfo && showProductInfo && showSliderInfo) {
        // Nếu cả ba đều được lưu là hiển thị, chỉ hiển thị bảng khách hàng
        hideAllSections('customer');
        showSection('customer');
    }

    // ===== Hàm Fetch Subcategories =====
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

    // ===== Hàm Fetch Items =====
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

    // ===== Hàm Fetch Items Detail =====
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
        var searchQuery = document.getElementById('productSearchInput').value.trim(); // Lấy giá trị tìm kiếm
    
        var params = {};
        if (category_id) params['category_id'] = category_id;
        if (subcategory_id) params['subcategory_id'] = subcategory_id;
        if (item_id) params['item_id'] = item_id;
        if (items_detail_id) params['items_detail_id'] = items_detail_id;
        if (searchQuery) params['search'] = searchQuery; // Thêm tham số tìm kiếm nếu có
    
        params['page'] = currentPage; // Thêm tham số trang
    
        var queryString = Object.keys(params).map(key => key + '=' + encodeURIComponent(params[key])).join('&');
        
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'get_product.php?' + queryString, true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        updateProductsTable(response); // Truyền toàn bộ response
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
        totalPages = responseData.total_pages; // Cập nhật tổng số trang
    
        // Cập nhật hiển thị trang hiện tại
        document.getElementById('currentPage').textContent = 'Trang ' + currentPage + ' / ' + totalPages;
    
        // Cập nhật trạng thái của các nút phân trang
        prevPageBtn.disabled = currentPage <= 1;
        nextPageBtn.disabled = currentPage >= totalPages;
    
        // Phần xử lý hiển thị sản phẩm
        var productsTableBody = document.querySelector('#productsTable tbody');
        productsTableBody.innerHTML = ''; // Xóa nội dung cũ
    
        if (products.length > 0) {
            products.forEach(function(product, index) {
                var row = document.createElement('tr');
                row.setAttribute('data-id', product.id);
    
                // Ô số thứ tự
                var cellIndex = document.createElement('td');
                cellIndex.textContent = index + 1 + (currentPage - 1) * 50;
                row.appendChild(cellIndex);
                
                // Ô ID sản phẩm
                var cellID = document.createElement('td');
                cellID.textContent = product.id; // Hiển thị ID từ items_detail
                row.appendChild(cellID);

                // Ô tên sản phẩm
                var cellName = document.createElement('td');
                cellName.textContent = product.name;
                cellName.classList.add('editable');
                cellName.setAttribute('data-field', 'name');
                row.appendChild(cellName);
    
                // Ô mô tả
                var cellDescription = document.createElement('td');
                cellDescription.textContent = product.description;
                cellDescription.classList.add('editable');
                cellDescription.setAttribute('data-field', 'description');
                row.appendChild(cellDescription);
    
                // Ô ảnh
                var cellImg = document.createElement('td');
                var imgElement = document.createElement('img');
                imgElement.src = product.img ? 'https://tiemhoamimi.com/image/upload/' + product.img : 'path/to/default/image.jpg';
                imgElement.style.width = '50px';
                cellImg.appendChild(imgElement);

                // Tạo nút upload ảnh
                var uploadBtn = document.createElement('button');
                uploadBtn.textContent = 'Tải lên ảnh';
                uploadBtn.classList.add('upload-image-btn');
                uploadBtn.setAttribute('data-id', product.id);
                

                // Tạo input file ẩn để chọn ảnh
                var fileInput = document.createElement('input');
                fileInput.type = 'file';
                fileInput.style.display = 'none';
                fileInput.classList.add('upload-image-input');
                fileInput.setAttribute('data-id', product.id);

                cellImg.appendChild(uploadBtn);
                cellImg.appendChild(fileInput);

                row.appendChild(cellImg);
    
                // Ô giá
                var cellPrice = document.createElement('td');
                cellPrice.textContent = numberWithCommas(product.price) + 'đ';
                cellPrice.classList.add('editable');
                cellPrice.setAttribute('data-field', 'price');
                row.appendChild(cellPrice);
    
                // Ô hành động (nút lưu và hủy)
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
    
            // Khởi tạo lại các sự kiện cho các ô editable
            initializeEditableCells();

            // Khởi tạo lại các sự kiện cho các nút upload ảnh
            initializeUploadButtons();

        } else {
            var row = document.createElement('tr');
            var cell = document.createElement('td');
            cell.setAttribute('colspan', '7'); // Số cột trong bảng
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
    
    function initializeUploadButtons() {
        var uploadButtons = document.querySelectorAll('.upload-image-btn');
    
        uploadButtons.forEach(function(button) {
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
