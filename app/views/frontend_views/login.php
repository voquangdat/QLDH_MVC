<?php
// app/views/frontend_views/login.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Đăng nhập'; ?></title>
    <link rel="stylesheet" href="/public/css/login.css">
</head>
<body>
    <div class="wapper">
        <div class="wapper-heading">
            <h1>Đăng nhập</h1>
        </div>
        <form id="loginForm">
            <div class="input-fild">
                <input type="text" id="username" name="username" placeholder="Email hoặc tên đăng nhập" required>
            </div>

            <div class="input-fild">
                <input type="password" id="password" name="password" placeholder="Mật khẩu" required>
            </div>
            
            <div class="user-type"> 
                <label>
                    <input type="radio" name="user_type" value="customer" checked> Khách hàng
                </label>
                <!-- <label>
                    <input type="radio" name="user_type" value="admin"> Quản trị viên
                </label> -->
            </div>

            <a href="index.php?page=register" class="register-link">Đăng ký</a>
            <button type="submit" id="btnLogin" class="btn">Đăng nhập</button>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#loginForm').on('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const button = $('#btnLogin');
            
            button.prop('disabled', true).text('Đang đăng nhập...');
            
            $.ajax({
                url: 'index.php?page=auth&action=login',
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
                    alert('Có lỗi xảy ra khi đăng nhập');
                },
                complete: function() {
                    button.prop('disabled', false).text('Đăng nhập');
                }
            });
        });
    });
    </script>
</body>
</html>