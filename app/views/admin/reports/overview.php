<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Báo cáo Thống kê'; ?></title>
    <script src="https://kit.fontawesome.com/54f0cb7e4a.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/public/css/admin-dashboard.css">
    <style>
        .reports-container {
            max-width: 1400px;
            margin: 20px auto;
            padding: 0 20px;
        }
        
        .reports-header {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .reports-header h2 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .reports-header p {
            color: #666;
            margin: 0;
        }
        
        .reports-nav {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .report-card {
            background: #fff;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            text-decoration: none;
            color: #333;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .report-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--card-color);
        }
        
        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            text-decoration: none;
            color: #333;
        }
        
        .report-card i {
            font-size: 3rem;
            margin-bottom: 20px;
            color: var(--card-color);
        }
        
        .report-card h3 {
            font-size: 1.3rem;
            margin-bottom: 10px;
            color: #333;
        }
        
        .report-card p {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.5;
            margin: 0;
        }
        
        .report-card.orders {
            --card-color: #667eea;
        }
        
        .report-card.products {
            --card-color: #f093fb;
        }
        
        .report-card.revenue {
            --card-color: #4facfe;
        }
        
        .back-to-dashboard {
            background: #6c757d;
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        
        .back-to-dashboard:hover {
            background: #5a6268;
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .feature-item {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
        }
        
        .feature-item i {
            font-size: 2rem;
            color: #667eea;
            margin-bottom: 15px;
        }
        
        .feature-item h4 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .feature-item p {
            color: #666;
            font-size: 0.9rem;
            margin: 0;
        }
    </style>
</head>
<body>
    <!-- Admin Header -->
    <div class="admin-header">
        <div class="container">
            <h1><i class="fas fa-chart-line"></i> Báo cáo Thống kê</h1>
            <div class="admin-user-info">
                <span>Xin chào, <strong><?php echo htmlspecialchars($admin_name ?? 'Admin'); ?></strong></span>
                <a href="index.php?page=admin&action=logout" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="reports-container">
        <a href="index.php?page=admin&action=dashboard" class="back-to-dashboard">
            <i class="fas fa-arrow-left"></i> Quay lại Dashboard
        </a>
        
        <div class="reports-header">
            <h2>Hệ thống Báo cáo Thống kê VoxFootball</h2>
            <p>Phân tích chi tiết dữ liệu kinh doanh và vận hành</p>
        </div>
        
        <div class="reports-nav">
            <a href="index.php?page=admin_reports&action=orders" class="report-card orders">
                <i class="fas fa-shopping-cart"></i>
                <h3>Báo cáo Đơn hàng</h3>
                <p>Thống kê tổng quan, trạng thái đơn hàng, phân tích theo khu vực địa lý và tỷ lệ thành công</p>
            </a>
            
            <a href="index.php?page=admin_reports&action=products" class="report-card products">
                <i class="fas fa-box-open"></i>
                <h3>Báo cáo Sản phẩm & Tồn kho</h3>
                <p>Sản phẩm bán chạy, tình trạng tồn kho, cảnh báo hết hàng và phân tích danh mục</p>
            </a>
            
            <a href="index.php?page=admin_reports&action=revenue" class="report-card revenue">
                <i class="fas fa-chart-bar"></i>
                <h3>Báo cáo Doanh thu</h3>
                <p>Doanh thu tổng thể theo thời gian, phương thức thanh toán và so sánh với kỳ trước</p>
            </a>
        </div>
        
        <div class="reports-header">
            <h3>Tính năng Báo cáo</h3>
        </div>
        
        <div class="features-grid">
            <div class="feature-item">
                <i class="fas fa-calendar-alt"></i>
                <h4>Lọc theo Thời gian</h4>
                <p>Chọn khoảng thời gian tùy chỉnh để phân tích dữ liệu</p>
            </div>
            
            <div class="feature-item">
                <i class="fas fa-chart-pie"></i>
                <h4>Biểu đồ Trực quan</h4>
                <p>Hiển thị dữ liệu dưới dạng bảng và biểu đồ dễ hiểu</p>
            </div>
            
            <div class="feature-item">
                <i class="fas fa-map-marker-alt"></i>
                <h4>Phân tích Địa lý</h4>
                <p>Thống kê đơn hàng theo tỉnh thành và khu vực</p>
            </div>
            
            <div class="feature-item">
                <i class="fas fa-percentage"></i>
                <h4>Tỷ lệ Thành công</h4>
                <p>Phân tích tỷ lệ đơn hàng thành công và thất bại</p>
            </div>
            
            <div class="feature-item">
                <i class="fas fa-trophy"></i>
                <h4>Top Sản phẩm</h4>
                <p>Xếp hạng sản phẩm bán chạy nhất theo doanh số</p>
            </div>
            
            <div class="feature-item">
                <i class="fas fa-exclamation-triangle"></i>
                <h4>Cảnh báo Tồn kho</h4>
                <p>Theo dõi sản phẩm sắp hết hàng và cần nhập thêm</p>
            </div>
        </div>
    </div>
</body>
</html>