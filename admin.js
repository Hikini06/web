// admin.js

document.addEventListener('DOMContentLoaded', function() {
    // Xử lý nút "Thông tin khách hàng"
    var toggleCustomerBtn = document.getElementById('toggleCustomerInfo');
    var customerInfoCont = document.getElementById('customerInfoCont');

    if (toggleCustomerBtn && customerInfoCont) {
        var showCustomerInfo = localStorage.getItem('showCustomerInfo');
        if (showCustomerInfo === 'true') {
            customerInfoCont.style.display = 'block';
            toggleCustomerBtn.setAttribute('aria-expanded', 'true');
        }

        toggleCustomerBtn.addEventListener('click', function() {
            if (customerInfoCont.style.display === 'none' || customerInfoCont.style.display === '') {
                customerInfoCont.style.display = 'block';
                toggleCustomerBtn.setAttribute('aria-expanded', 'true');
                localStorage.setItem('showCustomerInfo', 'true');
            } else {
                customerInfoCont.style.display = 'none';
                toggleCustomerBtn.setAttribute('aria-expanded', 'false');
                localStorage.setItem('showCustomerInfo', 'false');
            }
        });
    }

    // Xử lý nút "Sản phẩm"
    var toggleProductBtn = document.getElementById('toggleProductInfo');
    var productInfoCont = document.getElementById('productInfoCont');

    if (toggleProductBtn && productInfoCont) {
        var showProductInfo = localStorage.getItem('showProductInfo');
        if (showProductInfo === 'true') {
            productInfoCont.style.display = 'block';
            toggleProductBtn.setAttribute('aria-expanded', 'true');
        }

        toggleProductBtn.addEventListener('click', function() {
            if (productInfoCont.style.display === 'none' || productInfoCont.style.display === '') {
                productInfoCont.style.display = 'block';
                toggleProductBtn.setAttribute('aria-expanded', 'true');
                localStorage.setItem('showProductInfo', 'true');
            } else {
                productInfoCont.style.display = 'none';
                toggleProductBtn.setAttribute('aria-expanded', 'false');
                localStorage.setItem('showProductInfo', 'false');
            }
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
            input.value = currentText;
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
                                    cell.textContent = newValue;
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
});
