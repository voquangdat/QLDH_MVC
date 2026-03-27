<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Sửa Loại sản phẩm'; ?></title>
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
            border-color: #28a745;
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
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
        
        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
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
        
        .btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
        }
        
        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
            text-decoration: none;
            color: white;
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
        
        .product-type-info {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
        }
        
        .product-type-info h4 {
            color: #495057;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #dee2e6;
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 600;
            color: #666;
        }
        
        .info-value {
            font-weight: 500;
            color: #333;
        }
        
        .category-badge {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            color: #8b4513;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.9rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!-- Admin Header -->
    <div class="admin-header">
        <div class="container">
            <h1><i class="fas fa-edit"></i> Sửa Loại sản phẩm</h1>
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
        <a href="index.php?page=admin_product&section=product_types" class="back-link">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
        
        <div class="form-header">
            <h2>Sửa Loại Sản phẩm</h2>
            <p>Cập nhật thông tin loại sản phẩm</p>
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

        <?php if ($productType): ?>
            <!-- Thông tin loại sản phẩm hiện tại -->
            <div class="product-type-info">
                <h4>
                    <i class="fas fa-info-circle"></i>
                    Thông tin loại sản phẩm hiện tại
                </h4>
                <div class="info-item">
                    <span class="info-label">ID:</span>
                    <span class="info-value">#<?php echo $productType['loaisanpham_id']; ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Danh mục hiện tại:</span>
                    <span class="info-value">
                        <span class="category-badge">
                            <?php echo htmlspecialchars($productType['danhmuc_ten'] ?? 'Chưa có danh mục'); ?>
                        </span>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tên hiện tại:</span>
                    <span class="info-value"><?php echo htmlspecialchars($productType['loaisanpham_ten']); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Ngày tạo:</span>
                    <span class="info-value">
                        <i class="fas fa-calendar"></i>
                        <?php echo date('d/m/Y H:i', strtotime($productType['created_at'] ?? 'now')); ?>
                    </span>
                </div>
            </div>

            <div class="form-wrapper">
                <div class="form-help">
                    <i class="fas fa-info-circle"></i>
                    <strong>Lưu ý:</strong> Việc thay đổi thông tin loại sản phẩm sẽ ảnh hưởng đến tất cả sản phẩm thuộc loại này. 
                    Hãy đảm bảo bạn thực sự muốn thay đổi trước khi lưu.
                </div>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="danhmuc_id">
                            Chọn danh mục <span class="required">*</span>
                        </label>
                        <select id="danhmuc_id" 
                                name="danhmuc_id" 
                                class="form-control" 
                                required>
                            <option value="">-- Chọn danh mục --</option>
                            <?php if ($categories && $categories->num_rows > 0): ?>
                                <?php while ($category = $categories->fetch_assoc()): ?>
                                    <option value="<?php echo $category['danhmuc_id']; ?>"
                                            <?php 
                                            $selected = '';
                                            if (isset($_POST['danhmuc_id'])) {
                                                $selected = ($_POST['danhmuc_id'] == $category['danhmuc_id']) ? 'selected' : '';
                                            } else {
                                                $selected = ($productType['danhmuc_id'] == $category['danhmuc_id']) ? 'selected' : '';
                                            }
                                            echo $selected;
                                            ?>>
                                        <?php echo htmlspecialchars($category['danhmuc_ten']); ?>
                                    </option>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="loaisanpham_ten">
                            Tên loại sản phẩm <span class="required">*</span>
                        </label>
                        <input type="text" 
                               id="loaisanpham_ten" 
                               name="loaisanpham_ten" 
                               class="form-control" 
                               placeholder="Nhập tên loại sản phẩm (ví dụ: NGOẠI HẠNG ANH, CHÂU ÂU...)"
                               value="<?php echo htmlspecialchars($_POST['loaisanpham_ten'] ?? $productType['loaisanpham_ten']); ?>"
                               required>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Cập nhật Loại sản phẩm
                        </button>
                        <a href="index.php?page=admin_product&section=product_types" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Hủy bỏ
                        </a>
                        <a href="index.php?page=admin_product&section=product_types&action=delete&id=<?php echo $productType['loaisanpham_id']; ?>" 
                           class="btn btn-danger"
                           onclick="return confirm('⚠️ CẢNH BÁO: Bạn có chắc chắn muốn xóa loại sản phẩm này?\n\nViệc xóa loại sản phẩm sẽ:\n- Xóa vĩnh viễn loại sản phẩm\n- Có thể ảnh hưởng đến các sản phẩm liên quan\n\nHành động này KHÔNG THỂ HOÀN TÁC!')">
                            <i class="fas fa-trash"></i> Xóa Loại sản phẩm
                        </a>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                Không tìm thấy loại sản phẩm để sửa!
            </div>
            <div style="text-align: center; margin-top: 30px;">
                <a href="index.php?page=admin_product&section=product_types" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại danh sách
                </a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Auto focus vào select đầu tiên
        document.getElementById('danhmuc_id')?.focus();
        
        // Validation client-side
        document.querySelector('form')?.addEventListener('submit', function(e) {
            const danhmucId = document.getElementById('danhmuc_id').value;
            const loaisanphamTen = document.getElementById('loaisanpham_ten').value.trim();
            const originalDanhmucId = '<?php echo $productType['danhmuc_id'] ?? ''; ?>';
            const originalName = '<?php echo addslashes($productType['loaisanpham_ten'] ?? ''); ?>';
            
            if (!danhmucId) {
                e.preventDefault();
                alert('Vui lòng chọn danh mục!');
                document.getElementById('danhmuc_id').focus();
                return false;
            }
            
            if (!loaisanphamTen) {
                e.preventDefault();
                alert('Vui lòng nhập tên loại sản phẩm!');
                document.getElementById('loaisanpham_ten').focus();
                return false;
            }
            
            if (loaisanphamTen.length < 2) {
                e.preventDefault();
                alert('Tên loại sản phẩm phải có ít nhất 2 ký tự!');
                document.getElementById('loaisanpham_ten').focus();
                return false;
            }
            
            // Kiểm tra xem có thay đổi gì không
            if (danhmucId === originalDanhmucId && loaisanphamTen === originalName) {
                e.preventDefault();
                alert('Bạn chưa thay đổi gì cả!');
                document.getElementById('loaisanpham_ten').focus();
                return false;
            }
            
            // Xác nhận cập nhật
            const confirmUpdate = confirm('Bạn có chắc chắn muốn cập nhật loại sản phẩm này?\n\nTên mới: ' + loaisanphamTen);
            if (!confirmUpdate) {
                e.preventDefault();
                return false;
            }
        });
        
        // Highlight thay đổi cho select
        const selectDanhmuc = document.getElementById('danhmuc_id');
        const originalDanhmucValue = selectDanhmuc?.value;
        
        selectDanhmuc?.addEventListener('change', function() {
            if (this.value !== originalDanhmucValue) {
                this.style.borderColor = '#28a745';
                this.style.backgroundColor = '#f8fff9';
            } else {
                this.style.borderColor = '#e9ecef';
                this.style.backgroundColor = '#fff';
            }
        });
        
        // Highlight thay đổi cho input
        const input = document.getElementById('loaisanpham_ten');
        const originalValue = input?.value;
        
        input?.addEventListener('input', function() {
            if (this.value !== originalValue) {
                this.style.borderColor = '#28a745';
                this.style.backgroundColor = '#f8fff9';
            } else {
                this.style.borderColor = '#e9ecef';
                this.style.backgroundColor = '#fff';
            }
        });
    </script>
</body>
</html>