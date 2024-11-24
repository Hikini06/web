
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



