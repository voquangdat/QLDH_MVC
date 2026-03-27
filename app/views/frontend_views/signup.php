<?php
// app/views/frontend_views/signup.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Đăng ký'; ?></title>
    <link rel="stylesheet" href="/public/css/signup.css">
</head>
<body>
    <div class="wapper">
        <div class="wapper-heading">
            <h1>Đăng ký</h1>
        </div>
        <form id="signupForm">
            <div class="input_field">
                <input type="text" id="username" name="username" placeholder="Tên đăng nhập" required>
            </div>

            <div class="input_field">
                <input type="email" id="email" name="email" placeholder="Email" required>
            </div>

            <div class="input_field">
                <input type="password" id="password" name="password" placeholder="Mật khẩu" minlength="6" required>
            </div>

            <div class="input_field">
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Xác nhận mật khẩu" required>
            </div>
            
            <a href="index.php?page=login" class="login-link">Đăng nhập</a>
            <button type="submit" id="btnSignup" class="btn">Đăng ký</button>
        </form>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#signupForm').on('submit', function(e) {
            e.preventDefault();
            
            const password = $('#password').val();
            const confirmPassword = $('#confirm_password').val();
            
            if (password !== confirmPassword) {
                alert('Mật khẩu xác nhận không khớp');
                return;
            }
            
            const formData = new FormData(this);
            const button = $('#btnSignup');
            
            button.prop('disabled', true).text('Đang đăng ký...');
            
            $.ajax({
                url: 'index.php?page=auth&action=register',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        window.location.href = response.redirect;
                    } else {
                        alert('Lỗi: ' + response.message);
                    }
                },
                error: function() {
                    alert('Có lỗi xảy ra khi đăng ký');
                },
                complete: function() {
                    button.prop('disabled', false).text('Đăng ký');
                }
            });
        });
    });
    </script>
</body>
</html>