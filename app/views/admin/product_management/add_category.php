<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Thêm Danh mục'; ?></title>
    <script src="https://kit.fontawesome.com/54f0cb7e4a.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/public/css/admin-dashboard.css">
    <style>
        .form-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
        }
        
        .form-header {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .form-header h2 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .form-wrapper {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 40px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        .form-group .required {
            color: #dc3545;
        }
        
        .form-control {
            width: 100%;
            padding: 15px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
            text-decoration: none;
            color: white;
            transform: translateY(-2px);
        }
        
        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .back-link {
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
        
        .back-link:hover {
            background: #5a6268;
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
        }
        
        .form-help {
            background: #e7f3ff;
            border: 1px solid #b3d4fc;
            color: #004085;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 25px;
        }
        
        .form-help i {
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <!-- Admin Header -->
    <div class="admin-header">
        <div class="container">
            <h1><i class="fas fa-plus"></i> Thêm Danh mục</h1>
            <div class="admin-user-info">
                <span>Xin chào, <strong><?php echo htmlspecialchars($admin_name ?? 'Admin'); ?></strong></span>
                <a href="index.php?page=admin&action=logout" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="form-container">
        <a href="index.php?page=admin_product&section=categories" class="back-link">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
        
        <div class="form-header">
            <h2>Thêm Danh mục Sản phẩm</h2>
            <p>Tạo danh mục mới cho hệ thống sản phẩm</p>
        </div>

        <!-- Hiển thị thông báo lỗi -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php 
                echo $_SESSION['error']; 
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <div class="form-wrapper">
            <div class="form-help">
                <i class="fas fa-info-circle"></i>
                <strong>Hướng dẫn:</strong> Danh mục sẽ là nhóm chính để phân loại sản phẩm (ví dụ: ÁO CLB, ÁO ĐỘI TUYỂN QUỐC GIA). 
                Sau khi tạo danh mục, bạn có thể thêm các loại sản phẩm cụ thể vào danh mục này.
            </div>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="danhmuc_ten">
                        Tên Danh mục <span class="required">*</span>
                    </label>
                    <input type="text" 
                           id="danhmuc_ten" 
                           name="danhmuc_ten" 
                           class="form-control" 
                           placeholder="Nhập tên danh mục (ví dụ: ÁO CLB, ÁO ĐỘI TUYỂN...)"
                           value="<?php echo htmlspecialchars($_POST['danhmuc_ten'] ?? ''); ?>"
                           required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu Danh mục
                    </button>
                    <a href="index.php?page=admin_product&section=categories" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Hủy bỏ
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Auto focus vào input đầu tiên
        document.getElementById('danhmuc_ten').focus();
        
        // Validation client-side
        document.querySelector('form').addEventListener('submit', function(e) {
            const danhmucTen = document.getElementById('danhmuc_ten').value.trim();
            
            if (!danhmucTen) {
                e.preventDefault();
                alert('Vui lòng nhập tên danh mục!');
                document.getElementById('danhmuc_ten').focus();
                return false;
            }
            
            if (danhmucTen.length < 2) {
                e.preventDefault();
                alert('Tên danh mục phải có ít nhất 2 ký tự!');
                document.getElementById('danhmuc_ten').focus();
                return false;
            }
        });
    </script>
</body>
</html>