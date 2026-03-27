<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Quản lý Sản phẩm - VoxFootball Admin'; ?></title>
    <script src="https://kit.fontawesome.com/54f0cb7e4a.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/public/css/admin-dashboard.css">
    <style>
        .product-management-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }
        
        .section-nav {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            padding: 20px;
        }
        
        .section-nav h2 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .nav-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .nav-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
        }
        
        .nav-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            text-decoration: none;
            color: white;
        }
        
        .nav-card i {
            font-size: 3rem;
            margin-bottom: 15px;
            display: block;
        }
        
        .nav-card h3 {
            font-size: 1.4rem;
            margin-bottom: 10px;
        }
        
        .nav-card p {
            font-size: 0.9rem;
            opacity: 0.9;
            margin: 0;
        }
        
        .nav-card.categories { background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); }
        .nav-card.product-types { background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); }
        .nav-card.products { background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); }
        .nav-card.images { background: linear-gradient(135deg, #ff8a80 0%, #ea4c89 100%); }
        .nav-card.sizes { background: linear-gradient(135deg, #8fd3f4 0%, #84fab0 100%); }
        .nav-card.colors { background: linear-gradient(135deg, #a770ef 0%, #cf8bf3 100%); }
        
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
    </style>
</head>
<body>
    <!-- Admin Header -->
    <div class="admin-header">
        <div class="container">
            <h1><i class="fas fa-boxes"></i> Quản lý Sản phẩm</h1>
            <div class="admin-user-info">
                <span>Xin chào, <strong><?php echo htmlspecialchars($admin_name ?? 'Admin'); ?></strong></span>
                <a href="index.php?page=admin&action=logout" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="product-management-container">
        <a href="index.php?page=admin&action=dashboard" class="back-to-dashboard">
            <i class="fas fa-arrow-left"></i> Quay lại Dashboard
        </a>
        
        <div class="section-nav">
            <h2>Hệ thống Quản lý Sản phẩm VoxFootball</h2>
            <p style="text-align: center; color: #666; margin-bottom: 30px;">
                Chọn chức năng bạn muốn quản lý
            </p>
            
            <div class="nav-grid">
                <a href="index.php?page=admin_product&section=categories" class="nav-card categories">
                    <i class="fas fa-list"></i>
                    <h3>Danh mục sản phẩm</h3>
                    <p>Quản lý các danh mục chính của sản phẩm</p>
                </a>
                
                <a href="index.php?page=admin_product&section=product_types" class="nav-card product-types">
                    <i class="fas fa-tags"></i>
                    <h3>Loại sản phẩm</h3>
                    <p>Quản lý các loại sản phẩm theo từng danh mục</p>
                </a>
                
                <a href="index.php?page=admin_product&section=products" class="nav-card products">
                    <i class="fas fa-box"></i>
                    <h3>Sản phẩm</h3>
                    <p>Thêm, sửa, xóa và quản lý toàn bộ sản phẩm</p>
                </a>
                
                <a href="index.php?page=admin_product&section=product_images" class="nav-card images">
                    <i class="fas fa-images"></i>
                    <h3>Ảnh sản phẩm</h3>
                    <p>Quản lý hình ảnh chi tiết của sản phẩm</p>
                </a>
                
                <a href="index.php?page=admin_product&section=product_sizes" class="nav-card sizes">
                    <i class="fas fa-ruler"></i>
                    <h3>Size sản phẩm</h3>
                    <p>Quản lý các size có sẵn cho từng sản phẩm</p>
                </a>
                
                <a href="index.php?page=admin_product&section=product_colors" class="nav-card colors">
                    <i class="fas fa-palette"></i>
                    <h3>Màu sắc sản phẩm</h3>
                    <p>Quản lý bảng màu và hình ảnh màu sắc</p>
                </a>
            </div>
        </div>
    </div>
</body>
</html>