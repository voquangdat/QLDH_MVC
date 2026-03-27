<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Đăng nhập Quản trị'; ?></title>
    <link rel="stylesheet" href="/public/css/admin-login.css">
    <script src="https://kit.fontawesome.com/54f0cb7e4a.js" crossorigin="anonymous"></script>
</head>
<body class="admin-login-body">
    <div class="admin-login-container">
        <div class="admin-login-box">
            <div class="admin-login-header">
                <div class="admin-logo">
                    <img src="/public/image/logoShop.png" alt="VoxFootball" height="50">
                </div>
                <h1>Quản trị hệ thống</h1>
                <p>Đăng nhập vào trang quản trị VoxFootball</p>
                
                <div class="security-notice">
                    <i class="fas fa-shield-alt"></i> Khu vực bảo mật - Chỉ dành cho quản trị viên được ủy quyền
                </div>
            </div>
            
            <!-- Hiển thị thông báo lỗi/thành công từ session -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error" style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #f5c6cb;">
                    <?php echo $_SESSION['error']; ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #c3e6cb;">
                    <?php echo $_SESSION['success']; ?>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            
            <form id="adminLoginForm" class="admin-login-form" action="index.php?page=admin&action=login" method="post" autocomplete="off">
                <div class="form-group">
                    <label for="username">Tên đăng nhập</label>
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="username" placeholder="Nhập tên đăng nhập admin" autocomplete="off" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" autocomplete="new-password" required>
                        <span class="toggle-password" onclick="togglePassword()">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" id="btnAdminLogin" class="admin-login-btn">
                    <i class="fas fa-sign-in-alt"></i>
                    Đăng nhập
                </button>
            </form>
            
            <div class="admin-login-footer">
                <p><a href="index.php">← Về trang chủ</a></p>
                <p class="admin-note">
                    <i class="fas fa-shield-alt"></i>
                    Khu vực dành riêng cho quản trị viên
                </p>
                <p style="font-size: 12px; color: #999; margin-top: 10px;">
                    <i class="fas fa-lock"></i>
                    Trang này được bảo vệ. Chỉ admin được phép truy cập.
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
    // Toggle password visibility
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
    
    $(document).ready(function() {
        // Clear any saved form data on page load for security
        document.getElementById('username').value = '';
        document.getElementById('password').value = '';
        
        // Disable autocomplete for security
        document.getElementById('username').setAttribute('autocomplete', 'off');
        document.getElementById('password').setAttribute('autocomplete', 'new-password');
        
        $('#adminLoginForm').on('submit', function(e) {
            const useAjax = false; // Đặt false để sử dụng form submit thông thường
            
            if (useAjax) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const button = $('#btnAdminLogin');
                const originalText = button.html();
                
                button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Đang đăng nhập...');
                
                $.ajax({
                    url: 'index.php?page=admin&action=login',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            showNotification('Đăng nhập thành công!', 'success');
                            setTimeout(function() {
                                window.location.href = response.redirect;
                            }, 1000);
                        } else {
                            showNotification('Lỗi: ' + response.message, 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        showNotification('Có lỗi xảy ra khi đăng nhập. Vui lòng thử lại.', 'error');
                    },
                    complete: function() {
                        button.prop('disabled', false).html(originalText);
                    }
                });
            } else {
                // Sử dụng form submit thông thường (đơn giản hơn)
                const button = $('#btnAdminLogin');
                button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Đang đăng nhập...');
                
                // Cho phép form submit bình thường
                return true;
            }
        });
    });
    
    // Notification function
    function showNotification(message, type) {
        const notification = $('<div class="notification ' + type + '">' + message + '</div>');
        $('body').append(notification);
        
        setTimeout(function() {
            notification.addClass('show');
        }, 100);
        
        setTimeout(function() {
            notification.removeClass('show');
            setTimeout(function() {
                notification.remove();
            }, 300);
        }, 3000);
    }
    </script>
</body>
</html>