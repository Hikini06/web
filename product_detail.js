document.addEventListener('DOMContentLoaded', function () {
    const popup = document.getElementById('order-popup');
    const openPopupButton = document.querySelector('.product-detail-pic-text-buynow');
    const closePopupButton = document.getElementById('close-popup');
    const orderForm = document.getElementById('order-form');
    const phoneInput = document.getElementById('phone');
    const phoneError = document.getElementById('phone-error');

    let basePrice = <?php echo $basePrice; ?>;
    let selectedOptions = {
        color: 0,
        quantity: 0,
        option: 0,
        accessory: 0,
    };

    function updatePrice(addPrice, type) {
        selectedOptions[type] = addPrice;
        let totalPrice = basePrice 
            + parseFloat(selectedOptions.color || 0) 
            + parseFloat(selectedOptions.quantity || 0) 
            + parseFloat(selectedOptions.option || 0) 
            + parseFloat(selectedOptions.accessory || 0);

        // Định dạng lại tổng giá theo kiểu tiền tệ Việt Nam
        document.querySelector('.product-detail-pic-text-nameandprice h3').textContent = 
            totalPrice.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });
    }

    // Bind click event to all buttons dynamically
    document.querySelectorAll('.option-button').forEach(button => {
        button.addEventListener('click', function () {
            const addPrice = parseFloat(this.getAttribute('data-add-price') || 0);
            const type = this.getAttribute('data-type'); // Sử dụng data-type đã được cập nhật

            updatePrice(addPrice, type);
        });
    });

    // Xử lý form Quick Buy
    document.getElementById('quick-buy-form').addEventListener('submit', function(event) {
        var sdtInput = document.querySelector('input[name="sdt"]');
        var sdtValue = sdtInput.value.trim();
        var errorMessage = document.querySelector('.error-message');

        // Kiểm tra xem sdtValue có phải là 9 đến 10 chữ số không
        var phoneRegex = /^\d{9,10}$/;
        if (!phoneRegex.test(sdtValue)) {
            event.preventDefault(); // Ngăn chặn form gửi đi
            if (errorMessage) {
                errorMessage.textContent = 'Vui lòng nhập đúng số điện thoại';
            } else {
                var errorDiv = document.createElement('div');
                errorDiv.className = 'error-message';
                errorDiv.textContent = 'Vui lòng nhập đúng số điện thoại';
                sdtInput.parentNode.appendChild(errorDiv);
            }
        } else {
            if (errorMessage) {
                errorMessage.textContent = '';
            }
        }
    });

    // Mở popup
    openPopupButton.addEventListener('click', function () {
        popup.style.display = 'flex';
    });

    // Đóng popup
    closePopupButton.addEventListener('click', function () {
        popup.style.display = 'none';
    });

    // Gửi dữ liệu form
    orderForm.addEventListener('submit', function (event) {
        event.preventDefault(); // Ngăn gửi form mặc định

        const formData = new FormData(orderForm);

        // Gửi AJAX request
        fetch('order-handler.php', {
            method: 'POST',
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Đặt hàng thành công!');
                } else {
                    alert('Lỗi: ' + data.message);
                }
                popup.style.display = 'none'; // Đóng popup
            })
            .catch(error => {
                console.error('Lỗi:', error);
                alert('Đã xảy ra lỗi!');
            });
    });

    // Hiển thị popup thông báo thành công nếu có
    <?php if (isset($successMessage)): ?>
        showPopup("<?php echo htmlspecialchars($successMessage); ?>");
    <?php endif; ?>

    function showPopup(message) {
        var popupMsg = document.getElementById('popup-message');
        var popupText = document.getElementById('popup-text');
        popupText.textContent = message;
        popupMsg.classList.add('show');

        // Ẩn popup sau 3 giây
        setTimeout(function() {
            popupMsg.classList.remove('show');
        }, 3000);
    }
});
