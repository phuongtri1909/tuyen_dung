//hidden password

$(document).on('click', '#togglePassword', function() {
    const passwordField = $('#password');
    const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
    passwordField.attr('type', type);

    $(this).toggleClass('fa-eye fa-eye-slash');
});

//hidden password confirm

$(document).on('click', '#togglePasswordConfirm', function() {
    const passwordField = $('#password_confirmation');
    const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
    passwordField.attr('type', type);

    $(this).toggleClass('fa-eye fa-eye-slash');
});

//toast
function showToast(message, status) {
    const toastElement = $('#liveToast');
    const toastBody = toastElement.find('.toast-body');

    // Cập nhật nội dung tin nhắn
    toastBody.text(message);

    // Xóa các lớp nền cũ
    toastElement.removeClass('bg-success bg-danger text-white');

    // Thêm lớp nền dựa trên trạng thái
    if (status === 'success') {
        toastElement.addClass('bg-success text-white');
    } else if (status === 'error') {
        toastElement.addClass('bg-danger text-white');
    }

    // Hiển thị toast
    const toast = new bootstrap.Toast(toastElement[0]);
    toast.show();
}

//save toast

function saveToast(message, status) {
    sessionStorage.setItem('toastMessage', message);
    sessionStorage.setItem('toastStatus', status);
}

//show toast
function showSavedToast() {
    const message = sessionStorage.getItem('toastMessage');
    const status = sessionStorage.getItem('toastStatus');

    if (message && status) {
        showToast(message, status);
        sessionStorage.removeItem('toastMessage');
        sessionStorage.removeItem('toastStatus');
    }
}

//otp

function handleInput(element) {
    $(element).val($(element).val().replace(/[^0-9]/g, ''));

    if ($(element).val().length === 1) {
        const nextInput = $(element).next('.otp-input');
        if (nextInput.length) {
            nextInput.focus();
        }
    }
}


// Lưu vị trí cuộn trang khi chuyển trang
$(document).ready(function () {
    $('.click-scroll').on('click', function (e) {
        localStorage.setItem('scrollPosition', $(window).scrollTop());
        window.location.href = $(this).attr('href');
    });

    const scrollPosition = localStorage.getItem('scrollPosition');
    if (scrollPosition) {
        $(window).scrollTop(scrollPosition);
        localStorage.removeItem('scrollPosition');
    }
});
// Lưu vị trí cuộn trang khi chuyển trang