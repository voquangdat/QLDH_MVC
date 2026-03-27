<?php
// filepath: e:\QLDH_MVC\app\views\admin\inventory\suppliers.php
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Quản lý Nhà cung cấp'; ?></title>
    <script src="https://kit.fontawesome.com/54f0cb7e4a.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/public/css/admin-dashboard.css">
    <style>
        .management-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }
        
        .management-header {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .management-header h2 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .management-header p {
            color: #666;
            margin-bottom: 20px;
        }
        
        .btn {
            padding: 12px 25px;
            border-radius: 25px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }
        
        .coming-soon {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 60px;
            text-align: center;
        }
        
        .coming-soon i {
            font-size: 5rem;
            color: #667eea;
            margin-bottom: 30px;
        }
        
        .coming-soon h3 {
            color: #333;
            margin-bottom: 20px;
            font-size: 2rem;
        }
        
        .coming-soon p {
            color: #666;
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto 30px;
            line-height: 1.6;
        }
        
        .feature-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 40px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .feature-item {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 20px;
            border-radius: 10px;
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
            <h1><i class="fas fa-truck"></i> Quản lý Nhà cung cấp</h1>
            <div class="admin-user-info">
                <span>Xin chào, <strong><?php echo htmlspecialchars($admin_name ?? 'Admin'); ?></strong></span>
                <a href="index.php?page=admin&action=logout" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="management-container">
        <!-- Header Section -->
        <div class="management-header">
            <h2>Quản lý Nhà cung cấp</h2>
            <p>Quản lý thông tin và quan hệ với các nhà cung cấp hàng hóa</p>

            <div>
                <a href="index.php?page=admin_inventory" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>

        <!-- Coming Soon Section -->
        <div class="coming-soon">
            <i class="fas fa-tools"></i>
            <h3>Chức năng đang phát triển</h3>
            <p>
                Tính năng quản lý nhà cung cấp đang được phát triển và sẽ sớm ra mắt. 
                Đây sẽ là một công cụ mạnh mẽ giúp bạn quản lý tất cả các nhà cung cấp 
                và tối ưu hóa chuỗi cung ứng của doanh nghiệp.
            </p>
            
            <a href="index.php?page=admin_product&section=products&action=add" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm sản phẩm ngay
            </a>

            <!-- Feature Preview -->
            <div class="feature-list">
                <div class="feature-item">
                    <i class="fas fa-address-book"></i>
                    <h4>Thông tin NCC</h4>
                    <p>Lưu trữ đầy đủ thông tin liên hệ, địa chỉ và các điều khoản hợp tác</p>
                </div>
                
                <div class="feature-item">
                    <i class="fas fa-file-contract"></i>
                    <h4>Quản lý hợp đồng</h4>
                    <p>Theo dõi các hợp đồng, thời hạn thanh toán và điều kiện cung cấp</p>
                </div>
                
                <div class="feature-item">
                    <i class="fas fa-chart-line"></i>
                    <h4>Đánh giá hiệu suất</h4>
                    <p>Theo dõi chất lượng, thời gian giao hàng và mức độ hài lòng</p>
                </div>
                
                <div class="feature-item">
                    <i class="fas fa-truck-loading"></i>
                    <h4>Quản lý đơn hàng</h4>
                    <p>Tạo và theo dõi đơn đặt hàng từ nhà cung cấp</p>
                </div>
                
                <div class="feature-item">
                    <i class="fas fa-receipt"></i>
                    <h4>Quản lý hóa đơn</h4>
                    <p>Theo dõi các hóa đơn nhập hàng và tình trạng thanh toán</p>
                </div>
                
                <div class="feature-item">
                    <i class="fas fa-balance-scale"></i>
                    <h4>So sánh giá</h4>
                    <p>So sánh giá cả và điều kiện từ nhiều nhà cung cấp khác nhau</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Simple animation for feature items
        document.addEventListener('DOMContentLoaded', function() {
            const featureItems = document.querySelectorAll('.feature-item');
            
            featureItems.forEach((item, index) => {
                setTimeout(() => {
                    item.style.opacity = '0';
                    item.style.transform = 'translateY(20px)';
                    item.style.transition = 'all 0.5s ease';
                    
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'translateY(0)';
                    }, 100);
                }, index * 100);
            });
        });
    </script>
</body>
</html>