<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Dashboard Admin'; ?></title>
    <script src="https://kit.fontawesome.com/54f0cb7e4a.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/public/css/admin-dashboard.css">
</head>
<body>
    <!-- Admin Header -->
    <div class="admin-header">
        <div class="container">
            <h1><i class="fas fa-tachometer-alt"></i> Dashboard Quản Trị</h1>
            <div class="admin-user-info">
                <span>Xin chào, <strong><?php echo htmlspecialchars($admin_name ?? 'Admin'); ?></strong></span>
                <a href="index.php?page=admin&action=logout" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="admin-container">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <h2>Chào mừng đến với hệ thống quản trị VoxFootball</h2>
            <p>Sử dụng các chức năng bên dưới để quản lý website của bạn</p>
        </div>

        <!-- Stats Cards -->
        <div class="admin-grid">
            <!-- <div class="admin-card">
                <i class="fas fa-users"></i>
                <h3>Khách hàng</h3>
                <p>Quản lý thông tin khách hàng</p>
            </div> -->
            <div class="admin-card" onclick="location.href='index.php?page=admin_product'" style="cursor: pointer;">
                <i class="fas fa-box"></i>
                <h3>Sản phẩm</h3>
                <p>Quản lý danh mục sản phẩm</p>
            </div>
            <div class="admin-card" onclick="location.href='index.php?page=admin_order'" style="cursor: pointer;">
                <i class="fas fa-shopping-cart"></i>
                <h3>Đơn hàng</h3>
                <p>Theo dõi và xử lý đơn hàng</p>
            </div>
            <div class="admin-card" onclick="location.href='index.php?page=admin_reports'" style="cursor: pointer;">
                <i class="fas fa-chart-bar"></i>
                <h3>Báo cáo Thống kê</h3>
                <p>Báo cáo doanh thu và phân tích kinh doanh</p>
            </div>
            <div class="admin-card" onclick="location.href='index.php?page=admin_inventory'" style="cursor: pointer;">
                <i class="fas fa-warehouse"></i>
                <h3>Quản lý tồn kho</h3>
                <p>Theo dõi và quản lý hàng tồn kho</p>
            </div>
        </div>

        <!-- Admin Menu -->
        <div class="admin-menu">
            <h2>Menu Quản Trị</h2>
            <div class="menu-items">
                <a href="#" class="menu-item">
                    <i class="fas fa-users"></i>
                    <span>Quản lý Khách hàng</span>
                </a>
                <a href="index.php?page=admin_product&section=products" class="menu-item">
                    <i class="fas fa-box"></i>
                    <span>Quản lý Sản phẩm</span>
                </a>
                <a href="index.php?page=admin_product&section=categories" class="menu-item">
                    <i class="fas fa-tags"></i>
                    <span>Quản lý Danh mục</span>
                </a>
                <a href="index.php?page=admin_inventory" class="menu-item">
                    <i class="fas fa-warehouse"></i>
                    <span>Quản lý Tồn kho</span>
                </a>
                <a href="index.php?page=admin_order" class="menu-item">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Quản lý Đơn hàng</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-credit-card"></i>
                    <span>Quản lý Thanh toán</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-newspaper"></i>
                    <span>Quản lý Tin tức</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-comments"></i>
                    <span>Quản lý Bình luận</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-cog"></i>
                    <span>Cài đặt Hệ thống</span>
                </a>
                <a href="index.php" class="menu-item">
                    <i class="fas fa-globe"></i>
                    <span>Xem Website</span>
                </a>
            </div>
        </div>
    </div>
</body>
</html>