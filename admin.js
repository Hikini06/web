// admin.js

document.addEventListener('DOMContentLoaded', function() {
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


    if (toggleProfileBtn && profileSelectionCont) {
        toggleProfileBtn.addEventListener('click', function () {
            if (profileSelectionCont.style.display === 'none' || profileSelectionCont.style.display === '') {
                hideAllSections('profile');
                profileSelectionCont.style.display = 'block';
                toggleProfileBtn.setAttribute('aria-expanded', 'true');
            } else {
                profileSelectionCont.style.display = 'none';
                toggleProfileBtn.setAttribute('aria-expanded', 'false');
            }
        });
    }
    // Hàm để ẩn tất cả các bảng
    function hideAllSections(except = null) {
        if (except !== 'customer') {
            customerInfoCont.style.display = 'none';
            toggleCustomerBtn.setAttribute('aria-expanded', 'false');
            localStorage.setItem('showCustomerInfo', 'false');
        }
        if (except !== 'product') {
            productInfoCont.style.display = 'none';
            toggleProductBtn.setAttribute('aria-expanded', 'false');
            localStorage.setItem('showProductInfo', 'false');
        }
        if (except !== 'slider') {
            sliderInfoCont.style.display = 'none';
            toggleSliderBtn.setAttribute('aria-expanded', 'false');
            localStorage.setItem('showSliderInfo', 'false');
        }
    }

    // Hàm để hiển thị một bảng
    function showSection(section) {
        if (section === 'customer') {
            customerInfoCont.style.display = 'block';
            toggleCustomerBtn.setAttribute('aria-expanded', 'true');
            localStorage.setItem('showCustomerInfo', 'true');
        } else if (section === 'product') {
            productInfoCont.style.display = 'block';
            toggleProductBtn.setAttribute('aria-expanded', 'true');
            localStorage.setItem('showProductInfo', 'true');
        } else if (section === 'slider') {
            sliderInfoCont.style.display = 'block';
            toggleSliderBtn.setAttribute('aria-expanded', 'true');
            localStorage.setItem('showSliderInfo', 'true');
        }
    }

    // Hàm để toggle một bảng
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
        }
    }

    // Khởi tạo trạng thái ban đầu từ localStorage
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

    // Thêm sự kiện click cho nút "Thông tin khách hàng"
    if (toggleCustomerBtn && customerInfoCont) {
        toggleCustomerBtn.addEventListener('click', function() {
            toggleSection('customer');
        });
    }

    // Thêm sự kiện click cho nút "Sản phẩm"
    if (toggleProductBtn && productInfoCont) {
        toggleProductBtn.addEventListener('click', function() {
            toggleSection('product');
        });
    }

    // Thêm sự kiện click cho nút "Chọn sản phẩm cho slider"
    if (toggleSliderBtn && sliderInfoCont) {
        toggleSliderBtn.addEventListener('click', function() {
            toggleSection('slider');
        });
    }

    // Xử lý chỉnh sửa các ô có lớp 'editable' trong cả hai bảng
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
            }, { once: true }); // Sử dụng { once: true } để đảm bảo sự kiện chỉ chạy một lần

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
            }, { once: true }); // Sử dụng { once: true } để đảm bảo sự kiện chỉ chạy một lần
        });
    });

    // Hàm định dạng số với dấu phẩy
    function numberWithCommas(x) {
        if (isNaN(x)) return x;
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
});
