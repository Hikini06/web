
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
document.addEventListener('DOMContentLoaded', function () {
    const popup = document.getElementById('order-popup');
    const openPopupButton = document.querySelector('.product-detail-pic-text-buynow');
    const closePopupButton = document.getElementById('close-popup');
    const orderForm = document.getElementById('order-form');
    const phoneInput = document.getElementById('phone');
    const phoneError = document.getElementById('phone-error');
    const phoneValue = phoneInput.value.trim();


     // Kiểm tra số điện thoại (chỉ chứa số và có độ dài từ 9-10)
     const phoneRegex = /^\d{9,10}$/;
     if (!phoneRegex.test(phoneValue)) {
         event.preventDefault(); // Ngăn không cho gửi form
        //  phoneError.textContent = 'Vui lòng nhập số điện thoại hợp lệ (9-10 số).';
         phoneInput.focus(); // Đưa con trỏ vào ô nhập
     } else {
         phoneError.textContent = ''; // Xóa thông báo lỗi nếu hợp lệ
     }

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
});



