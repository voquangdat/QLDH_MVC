<?php
// app/views/frontend_views/customer/profile.php
?>
<style>
/* Main wrapper để tránh bị che khuất bởi header */
.main-wrapper {
    margin-top: 80px; /* Tăng margin để tránh fixed header */
    background: #f8f9fa;
    min-height: calc(100vh - 80px);
}

.customer-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 30px 20px;
}

/* Breadcrumb navigation */
.breadcrumb {
    background: white;
    padding: 15px 30px;
    border-radius: 8px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.breadcrumb a {
    color: #007bff;
    text-decoration: none;
}

.breadcrumb a:hover {
    text-decoration: underline;
}

.profile-header {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    color: #333;
    padding: 30px;
    border-radius: 12px;
    text-align: center;
    margin-bottom: 30px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1), 0 5px 15px rgba(0, 0, 0, 0.08);
    border-left: 4px solid #ff5722;
    backdrop-filter: blur(10px);
}

.profile-header h1 {
    margin: 0 0 15px 0;
    font-size: 2.2em;
    font-weight: bold;
    color: #333;
}

.profile-header .user-info {
    margin-top: 15px;
    font-size: 1.1em;
    color: #666;
}

.profile-tabs {
    display: flex;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-radius: 12px;
    margin-bottom: 20px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1), 0 5px 15px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    backdrop-filter: blur(10px);
}

.tab-button {
    flex: 1;
    padding: 15px 20px;
    background: white;
    border: none;
    cursor: pointer;
    font-size: 16px;
    font-weight: 500;
    transition: all 0.3s ease;
    border-bottom: 3px solid transparent;
    color: #333;
}

.tab-button:hover {
    background: rgba(255, 87, 34, 0.1);
    color: #ff5722;
}

.tab-button.active {
    background: linear-gradient(135deg, #ff5722, #e64a19);
    color: white;
    box-shadow: 0 5px 15px rgba(255, 87, 34, 0.3);
    border-bottom-color: #0056b3;
}

.tab-content {
    display: none;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1), 0 5px 15px rgba(0, 0, 0, 0.08);
    backdrop-filter: blur(10px);
}

.tab-content.active {
    display: block;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #333;
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 16px;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: #ff5722;
    box-shadow: 0 0 0 3px rgba(255, 87, 34, 0.1);
}

.form-row {
    display: flex;
    gap: 20px;
}

.form-row .form-group {
    flex: 1;
}

.btn {
    padding: 12px 30px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
    text-align: center;
}

.btn-primary {
    background: linear-gradient(135deg, #ff5722, #e64a19);
    color: white;
    box-shadow: 0 4px 12px rgba(255, 87, 34, 0.3);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #e64a19, #d84315);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(255, 87, 34, 0.4);
}

.btn-danger {
    background: #dc3545;
    color: white;
}

.btn-danger:hover {
    background: #c82333;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-weight: 500;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.profile-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 25px;
    border-radius: 15px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-card i {
    font-size: 3em;
    color: #007bff;
    margin-bottom: 15px;
}

.stat-card h3 {
    margin: 0;
    font-size: 2em;
    color: #333;
}

.stat-card p {
    margin: 5px 0 0 0;
    color: #666;
    font-weight: 500;
}

@media (max-width: 768px) {
    .profile-tabs {
        flex-direction: column;
    }
    
    .form-row {
        flex-direction: column;
    }
    
    .customer-container {
        padding: 10px;
    }
}
</style>

<div class="main-wrapper">
    <div class="customer-container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="index.php"><i class="fas fa-home"></i> Trang chủ</a> / 
            <span>Thông tin cá nhân</span>
        </div>

        <!-- Profile Header -->
        <div class="profile-header">
            <h1><i class="fas fa-user-circle"></i> Thông tin cá nhân</h1>
            <div class="user-info">
                <p><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($customer['email']); ?></p>
                <p><i class="fas fa-calendar-alt"></i> Tham gia từ: <?php echo date('d/m/Y', strtotime($customer['created_at'])); ?></p>
            </div>
        </div>

    <!-- Profile Stats -->
    <div class="profile-stats">
        <div class="stat-card">
            <i class="fas fa-shopping-bag"></i>
            <h3 id="totalOrders">-</h3>
            <p>Tổng đơn hàng</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-money-bill-wave"></i>
            <h3 id="totalSpent">-</h3>
            <p>Tổng chi tiêu</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-check-circle"></i>
            <h3 id="completedOrders">-</h3>
            <p>Đơn hoàn thành</p>
        </div>
    </div>

    <!-- Profile Tabs -->
    <div class="profile-tabs">
        <button class="tab-button active" onclick="showTab('info')">
            <i class="fas fa-user"></i> Thông tin cá nhân
        </button>
        <button class="tab-button" onclick="showTab('password')">
            <i class="fas fa-lock"></i> Đổi mật khẩu
        </button>
    </div>

    <!-- Tab Contents -->
    <!-- Personal Info Tab -->
    <div id="info-content" class="tab-content active">
        <h3><i class="fas fa-edit"></i> Cập nhật thông tin cá nhân</h3>
        
        <div id="profileMessage"></div>
        
        <form id="profileForm">
            <div class="form-row">
                <div class="form-group">
                    <label for="full_name">Họ và tên *</label>
                    <input type="text" id="full_name" name="full_name" class="form-control" 
                           value="<?php echo htmlspecialchars($customer['full_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="phone">Số điện thoại</label>
                    <input type="tel" id="phone" name="phone" class="form-control" 
                           value="<?php echo htmlspecialchars($customer['phone'] ?? ''); ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="address">Địa chỉ</label>
                <textarea id="address" name="address" class="form-control" rows="3"><?php echo htmlspecialchars($customer['address'] ?? ''); ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="city">Thành phố/Tỉnh</label>
                    <input type="text" id="city" name="city" class="form-control" 
                           value="<?php echo htmlspecialchars($customer['city'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="district">Quận/Huyện</label>
                    <input type="text" id="district" name="district" class="form-control" 
                           value="<?php echo htmlspecialchars($customer['district'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="ward">Phường/Xã</label>
                    <input type="text" id="ward" name="ward" class="form-control" 
                           value="<?php echo htmlspecialchars($customer['ward'] ?? ''); ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="birth_date">Ngày sinh</label>
                    <input type="date" id="birth_date" name="birth_date" class="form-control" 
                           value="<?php echo $customer['birth_date'] ?? ''; ?>">
                </div>
                <div class="form-group">
                    <label for="gender">Giới tính</label>
                    <select id="gender" name="gender" class="form-control">
                        <option value="">Chọn giới tính</option>
                        <option value="male" <?php echo ($customer['gender'] ?? '') === 'male' ? 'selected' : ''; ?>>Nam</option>
                        <option value="female" <?php echo ($customer['gender'] ?? '') === 'female' ? 'selected' : ''; ?>>Nữ</option>
                        <option value="other" <?php echo ($customer['gender'] ?? '') === 'other' ? 'selected' : ''; ?>>Khác</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Cập nhật thông tin
            </button>
        </form>
    </div>

    <!-- Change Password Tab -->
    <div id="password-content" class="tab-content">
        <h3><i class="fas fa-key"></i> Đổi mật khẩu</h3>
        
        <div id="passwordMessage"></div>
        
        <form id="passwordForm">
            <div class="form-group">
                <label for="current_password">Mật khẩu hiện tại *</label>
                <input type="password" id="current_password" name="current_password" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="new_password">Mật khẩu mới *</label>
                <input type="password" id="new_password" name="new_password" class="form-control" minlength="6" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Xác nhận mật khẩu mới *</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" minlength="6" required>
            </div>

            <button type="submit" class="btn btn-danger">
                <i class="fas fa-key"></i> Đổi mật khẩu
            </button>
        </form>
    </div>
    </div>
</div>

<script>
// Tab functionality
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.remove('active');
    });
    
    // Remove active class from all buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active');
    });
    
    // Show selected tab content
    document.getElementById(tabName + '-content').classList.add('active');
    
    // Add active class to clicked button
    event.target.classList.add('active');
}

// Load customer stats
document.addEventListener('DOMContentLoaded', function() {
    loadCustomerStats();
});

function loadCustomerStats() {
    // TODO: Implement stats loading via AJAX
    // For now, just show placeholder data
    document.getElementById('totalOrders').textContent = '0';
    document.getElementById('totalSpent').textContent = '0đ';
    document.getElementById('completedOrders').textContent = '0';
}

// Profile form submission
document.getElementById('profileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const button = this.querySelector('button[type="submit"]');
    const originalText = button.innerHTML;
    
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang cập nhật...';
    
    fetch('index.php?page=customer&action=updateProfile', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const messageDiv = document.getElementById('profileMessage');
        
        if (data.success) {
            messageDiv.innerHTML = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> ' + data.message + '</div>';
        } else {
            messageDiv.innerHTML = '<div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> ' + data.message + '</div>';
        }
        
        // Scroll to message
        messageDiv.scrollIntoView({ behavior: 'smooth' });
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('profileMessage').innerHTML = '<div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> Có lỗi xảy ra khi cập nhật thông tin</div>';
    })
    .finally(() => {
        button.disabled = false;
        button.innerHTML = originalText;
    });
});

// Password form submission
document.getElementById('passwordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (newPassword !== confirmPassword) {
        document.getElementById('passwordMessage').innerHTML = '<div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> Mật khẩu mới không khớp</div>';
        return;
    }
    
    const formData = new FormData(this);
    const button = this.querySelector('button[type="submit"]');
    const originalText = button.innerHTML;
    
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang đổi mật khẩu...';
    
    fetch('index.php?page=customer&action=changePassword', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const messageDiv = document.getElementById('passwordMessage');
        
        if (data.success) {
            messageDiv.innerHTML = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> ' + data.message + '</div>';
            // Clear form
            this.reset();
        } else {
            messageDiv.innerHTML = '<div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> ' + data.message + '</div>';
        }
        
        // Scroll to message
        messageDiv.scrollIntoView({ behavior: 'smooth' });
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('passwordMessage').innerHTML = '<div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> Có lỗi xảy ra khi đổi mật khẩu</div>';
    })
    .finally(() => {
        button.disabled = false;
        button.innerHTML = originalText;
    });
});
</script>